pipeline{
    agent any

    environment {
        // Define any environment variables here
        MY_ENV_VAR = 'example_value'
        DOCKER_IMAGE = 'laravel-app'
        DOCKER_TAG = "${env.BUILD_NUMBER}"
        CONTAINER_NAME = 'laravel-app-container'
        APP_PORT = '8000'

        // Credenciales (configurar en Jenkins)
        DOCKER_CREDENTIALS = credentials('docker-hub-credentials')
        ENV_FILE = credentials('laravel-env-file')
    }

    stages {
        stage('checkout') {
            steps {
                // Checkout the code from the repository
                echo 'Clonando el repositorio...'
                checkout scm
            }
        }
        stage('environment septup'){
            steps {
                script {
                    echo 'Configuracion variable de entrono...'
                    sh 'cp $ENV_FILE .env'
                    echo "Build Number: ${env.BUILD_NUMBER}"
                    echo "Branch: ${env.BRANCH_NAME} ?: 'openpay'"
                }
            }
        }
        stage('Install dependencies') {
            steps {
                echo 'Instalando dependencias...'
                // docker run --rm -v $PWD:/app -w /app ${DOCKER_IMAGE}:${DOCKER_TAG} composer install --no-interaction
                script {
                    sh '''
                docker run --rm -v $(pwd):/app -w /app composer:2 install --no-dev --optimize-autoloader --no-interaction
                '''
                }
            }
        }
        stage('Build Docker Image') {
            steps {
                echo 'Construyendo la imagen de Docker...'
                script {
                    // Build de la imagen Docker
                    def image = docker.build("${DOCKER_IMAGE}:${DOCKER_TAG}")
                    // También tagear como latest
                    sh "docker tag ${DOCKER_IMAGE}:${DOCKER_TAG} ${DOCKER_IMAGE}:latest"
                    echo "Imagen construida: ${DOCKER_IMAGE}:${DOCKER_TAG}"
                }
            }
        }
        stage('Stop container previo') {
            steps {
                echo 'Deteniendo contenedor previo...'
                script {
                    // Detener y eliminar el contenedor si existe
                    sh "docker stop ${CONTAINER_NAME} || true"
                    sh "docker rm ${CONTAINER_NAME} || true"
                }
            }
        }

        stage('deploy Application') {
            steps {
                echo 'Desplegando la aplicacion...'
                script {
                    sh """
                        # Ejecutar nuevo contenedor
                        docker run -d \
                            --name ${CONTAINER_NAME} \
                            --restart unless-stopped \
                            -p ${APP_PORT}:80 \
                            -v laravel_storage:/var/www/html/storage \
                            -v laravel_logs:/var/www/html/storage/logs \
                            --env-file .env \
                            ${DOCKER_IMAGE}:${DOCKER_TAG}
                    """

                    sh '''
                        echo "Esperando a que la aplicación esté lista..."
                        for i in {1..30}; do
                            if curl -f http://localhost:${APP_PORT}/health 2>/dev/null; then
                                echo "Aplicación lista!"
                                break
                            fi
                            echo "Esperando... ($i/30)"
                            sleep 10
                        done
                    '''
                }
            }
        }
        stage('run Migrations') {
            steps {
                echo 'Ejecutando migraciones solo de openpay...'
                script {
                    sh """
                        docker exec ${CONTAINER_NAME} php artisan migrate --database=openpay --path=database/migrations/payments/ --force
                    """
                }
            }
        }
    }
     post {
        always {
            echo 'Limpiando workspace...'
            
            // Limpiar imágenes no utilizadas
            sh '''
                # Eliminar imágenes dangling
                docker image prune -f || true
                
                # Limpiar volúmenes no utilizados
                docker volume prune -f || true
            '''
            
            // Limpiar workspace
            cleanWs()
        }
        
        success {
            echo '🎉 Pipeline ejecutado exitosamente!'
            
            // Notificaciones de éxito (opcional)
        }
        
        failure {
            echo '❌ Pipeline falló!'
            
            // Cleanup en caso de fallo
            sh '''
                # Detener contenedor si existe
                docker stop ${CONTAINER_NAME} || true
                // docker rm ${CONTAINER_NAME} || true
            '''
        }
        
        unstable {
            echo '⚠️ Pipeline inestable!'
        }
    }
}