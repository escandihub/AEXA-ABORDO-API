@props(['title',
'maxWidth'])
<div class="fixed inset-0 z-50"
    x-data="{ show: @entangle('show'), loading: @entangle('loading') }"
    x-show="show" {{-- Controla la visibilidad de todo el contenedor del modal/overlay --}}
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;"
>
    {{-- Alpine.js Watcher para la variable 'loading' (útil para depuración) --}}
    <div x-effect="console.log('El estado de carga ha cambiado a:', loading)"></div>

    {{-- Overlay oscuro y con efecto de desenfoque --}}
    <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" @click="!loading && show = false">
        {{-- Solo permite cerrar si no está cargando --}}
    </div>

    {{-- Contenedor para el Spinner de carga (centrado sobre el overlay) --}}
    <div x-show="loading"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="flex items-center justify-center min-h-screen p-4 sm:p-0"
        style="display: none;"
    >
        <div class="flex flex-col items-center justify-center p-8 bg-white/90 backdrop-blur-sm
                    rounded-3xl shadow-2xl border border-white/20 transform-gpu">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
            <p class="mt-6 text-gray-800 text-xl font-semibold">Cargando transacciones...</p>
        </div>
    </div>

    {{-- Contenedor principal del modal de contenido (centrado y animado, visible solo si NO está cargando) --}}
    <div x-show="!loading"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="flex items-center justify-center min-h-screen p-4 sm:p-0"
        style="display: none;"
    >
        <div class="inline-block max-w-dvw  max-w-90 p-6 my-8 text-left align-middle
                    transition-all transform bg-white/90 backdrop-blur-sm shadow-2xl rounded-3xl
                    border border-white/20 overflow-hidden">

            {{-- Encabezado del modal --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    {{-- Icono representativo para transacciones/historial --}}
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500
                                flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $title }}</h3>
                </div>
                {{-- Botón para cerrar el modal --}}
                <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors
                                                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 rounded-full p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Contenido del modal: listado de transacciones --}}
            {{-- <div class="space-y-4  overflow-y-auto pr-2 custom-scrollbar"> --}}
                 <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
               {{ $slot }}

            {{-- Estilos personalizados para el scrollbar --}}
            <style>
                .custom-scrollbar::-webkit-scrollbar {
                    width: 8px;
                }
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 10px;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #cbd5e0; /* gray-300 */
                    border-radius: 10px;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #a0aec0; /* gray-400 */
                }
            </style>

            {{-- Botón de cierre en el pie del modal --}}
            <div class="mt-6 flex justify-end">
                <button @click="show = false"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                                text-white font-semibold rounded-lg shadow-sm hover:shadow-md
                                transition-all duration-150 focus:outline-none focus:ring-2
                                focus:ring-blue-500 focus:ring-offset-2 border border-blue-600
                                transform hover:scale-105 active:scale-95">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>