# Imagen base con PHP 8.1.10 CLI
FROM php:8.2.0-cli

# Instalar dependencias para PHP y Node.js
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libltdl-dev \
     build-essential \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install opcache \
    && rm -rf /var/lib/apt/lists/*

#     # ---- COMPILAR unixODBC desde fuente ----
# RUN curl -fsSL https://www.unixodbc.org/unixODBC-2.3.12.tar.gz -o unixODBC.tar.gz \
#     && tar xzf unixODBC.tar.gz \
#     && cd unixODBC-2.3.12 \
#     && ./configure --prefix=/usr --sysconfdir=/etc --enable-gui=no \
#     && make \
#     && make install \
#     && cd .. && rm -rf unixODBC-2.3.12 unixODBC.tar.gz

# # ---- Instalar controladores Microsoft ODBC ----
# RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
#     && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list \
#     && apt-get update \
#     && ACCEPT_EULA=Y apt-get install -y msodbcsql17

# # Instalar controladores SQLSRV
# # RUN apt-get update && apt-get install -y gnupg2
# # RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - 
# # RUN curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list 
# # RUN apt-get update 
# # RUN ACCEPT_EULA=Y apt-get -y --no-install-recommends install msodbcsql17 
# # unixodbc-dev 
# RUN touch /usr/lib/x86_64-linux-gnu/libltdl.la
# RUN pecl install sqlsrv
# RUN pecl install pdo_sqlsrv
# RUN docker-php-ext-enable sqlsrv pdo_sqlsrv

# Instalar Composer 2.6.5
RUN curl -sS https://getcomposer.org/installer | php -- \
    --version=2.6.5 \
    --install-dir=/usr/local/bin \
    --filename=composer

# Instalar Node.js 20 y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@10.2.4 yarn

# Directorio de trabajo
WORKDIR /var/www/html

# No copiar código aquí para que sea manejado por volúmenes (hot reload)