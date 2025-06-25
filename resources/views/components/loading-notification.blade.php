<?php
// resources/views/components/loading-notification.blade.php
?>
<div x-data="loadingNotification" 
x-on:task-updating.window="startLoading($event)"
x-on:task-updated.window="taskUpdated($event)"
x-on:task-error.window="taskError($event)"
class="fixed top-4 right-4 z-50 space-y-4">

    <!-- Loading Indicator -->
    <div x-show="loading" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-full"
         class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
        
        <div class="flex items-center space-x-3">
            <!-- Spinner animado -->
            <div class="animate-spin rounded-full h-6 w-6 border-2 border-blue-500 border-t-transparent"></div>
            
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900"  x-text="message"></p>
                <p class="text-xs text-gray-500">Por favor espera un momento</p>
            </div>
        </div>
        
        <!-- Barra de progreso -->
        <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
            <div class="bg-blue-500 h-1.5 rounded-full animate-pulse" style="width: 70%"></div>
        </div>
    </div>

    <!-- Notification -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
         :class="{
             'bg-green-50 border-green-200': notification.type === 'success',
             'bg-red-50 border-red-200': notification.type === 'error',
             'bg-yellow-50 border-yellow-200': notification.type === 'warning',
             'bg-blue-50 border-blue-200': notification.type === 'info'
         }"
         class="border rounded-lg shadow-lg p-4 max-w-sm">
        
        <div class="flex items-start space-x-3">
            <!-- Iconos según el tipo -->
            <div class="flex-shrink-0">
                <!-- Success Icon -->
                <svg x-show="notification.type === 'success'" class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                
                <!-- Error Icon -->
                <svg x-show="notification.type === 'error'" class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                
                <!-- Warning Icon -->
                <svg x-show="notification.type === 'warning'" class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            
            <div class="flex-1">
                <p x-text="notification.message" 
                   :class="{
                       'text-green-800': notification.type === 'success',
                       'text-red-800': notification.type === 'error',
                       'text-yellow-800': notification.type === 'warning',
                       'text-blue-800': notification.type === 'info'
                   }"
                   class="text-sm font-medium"></p>
            </div>
            
            <!-- Botón de cerrar -->
            <button @click="notification.show = false"
                    :class="{
                        'text-green-500 hover:text-green-700': notification.type === 'success',
                        'text-red-500 hover:text-red-700': notification.type === 'error',
                        'text-yellow-500 hover:text-yellow-700': notification.type === 'warning',
                        'text-blue-500 hover:text-blue-700': notification.type === 'info'
                    }"
                    class="flex-shrink-0 rounded-md p-1.5 hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Botón de ejemplo para probar -->
    <button @click="handleTaskUpdate()" 
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition-colors duration-200">
        Actualizar Tarea
    </button>
</div>

<!-- Eventos personalizados de Laravel -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener("alpine:init", () => {

        Alpine.data("loadingNotification", () => ({
            loading: false,
            message: '',
            notification: {
                show: false,
                message: '',
                type: 'success'
            },
            
            startLoading(event) {
                console.log('Loading started:', event.detail);
                
                this.message = event.detail.message;
                this.loading = true;
                this.notification.show = false;
            },
            
            stopLoading() {
                this.loading = false;
            },
            
            showNotification(message, type = 'success') {
                this.notification.message = message;
                this.notification.type = type;
                this.notification.show = true;
                
                // Auto-hide after 3 seconds
                setTimeout(() => {
                    this.notification.show = false;
                }, 3000);
            },
            handleTaskUpdate() {
                this.startLoading();
                
                // Simulate a task update
                setTimeout(() => {
                    this.stopLoading();
                    this.showNotification('Tarea actualizada correctamente', 'success');
                }, 2000);
            },
            taskUpdated(event) {
                console.log('Task updated:', event.detail);
                
                this.stopLoading();
                this.showNotification(event.detail.message || 'Tarea actualizada con éxito', 'success');
            },
            taskError(event) {
                this.stopLoading();
                this.showNotification(event.detail.message || 'Error al actualizar la tarea', 'error');
            }
        }));
    });
});
</script>