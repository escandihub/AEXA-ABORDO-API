<div class="fixed inset-0 z-50 overflow-y-auto"
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
                    <h3 class="text-xl font-bold text-gray-900">Historial de Transacciones</h3>
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
            <div class="space-y-4 max-h-90 overflow-y-auto pr-2 custom-scrollbar">
                @forelse($transactions as $transaction)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200
                                transform hover:scale-[1.01] transition-all duration-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4 text-sm">
                            <div>
                                <span class="font-semibold text-gray-600">ID Transacción:</span>
                                <p class="text-gray-900 font-medium break-words">{{ $transaction['transaction_id'] }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">Monto:</span>
                                <p class="text-green-600 font-bold text-lg">${{ number_format($transaction['amount'], 2) }} {{ $transaction['currency'] }}</p>
                            </div>
                            <div class="col-span-1 sm:col-span-2">
                                <span class="font-semibold text-gray-600">Método:</span>
                                <p class="text-gray-900">{{ ucfirst($transaction['method']) }}</p>
                            </div>
                            <div class="col-span-1 sm:col-span-2">
                                <span class="font-semibold text-gray-600">Estado:</span>
                                @php
                                    $statusColorClass = '';
                                    switch($transaction['status']) {
                                        case 'completed': $statusColorClass = 'bg-green-100 text-green-800 border-green-200'; break;
                                        case 'charge_pending': $statusColorClass = 'bg-yellow-100 text-yellow-800 border-yellow-200'; break;
                                        case 'pending': $statusColorClass = 'bg-blue-100 text-blue-800 border-blue-200'; break;
                                        case 'failed': $statusColorClass = 'bg-red-100 text-red-800 border-red-200'; break;
                                        default: $statusColorClass = 'bg-gray-100 text-gray-800 border-gray-200'; break;
                                    }
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColorClass }} border">
                                    {{ ucfirst(str_replace('_', ' ', $transaction['status'])) }}
                                </span>
                            </div>
                            <div class="col-span-1 sm:col-span-2">
                                <span class="font-semibold text-gray-600">Fecha de Creación:</span>
                                <p class="text-gray-800">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        {{-- Collapsible (Drawable) para los logs --}}
                        @if(!empty($transaction['logs']))
                            <div x-data="{ openLogs: false }" class="mt-4 border-t border-gray-100 pt-3">
                                <button @click="openLogs = !openLogs"
                                        class="flex justify-between items-center w-full text-blue-600 hover:text-blue-800
                                               font-semibold py-2 px-3 -mx-3 rounded-lg transition-colors duration-150">
                                    <span>Ver Logs de la Transacción ({{ count($transaction['logs']) }})</span>
                                    <svg class="w-5 h-5 transform transition-transform duration-200"
                                        :class="{ 'rotate-180': openLogs }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="openLogs"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                    class="mt-2 space-y-3 bg-gray-50 p-4 rounded-lg border border-gray-100"
                                    style="display: none;"
                                >
                                    @foreach($transaction['logs'] as $log)
                                        <div class="bg-white p-3 rounded-md shadow-sm border border-gray-100">
                                            <div class="grid grid-cols-1 gap-1 text-xs">
                                                <div>
                                                    <span class="font-semibold text-gray-600">Estado Log:</span>
                                                    @php
                                                        $logStatusColorClass = '';
                                                        switch($log['status']) {
                                                            case 'failed': $logStatusColorClass = 'bg-red-50 text-red-700'; break;
                                                            case 'attempted': $logStatusColorClass = 'bg-blue-50 text-blue-700'; break;
                                                            case 'received': $logStatusColorClass = 'bg-green-50 text-green-700'; break;
                                                            case 'initiated': $logStatusColorClass = 'bg-purple-50 text-purple-700'; break;
                                                            default: $logStatusColorClass = 'bg-gray-50 text-gray-700'; break;
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium {{ $logStatusColorClass }}">
                                                        {{ ucfirst($log['status']) }}
                                                    </span>
                                                </div>
                                                @if($log['error_message'])
                                                    <div class="text-red-600">
                                                        <span class="font-semibold">Error:</span>
                                                        <p>{{ $log['error_message'] }}</p>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="font-semibold text-gray-600">Código Gateway:</span>
                                                    <p>{{ $log['gateway_response_code'] ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <span class="font-semibold text-gray-600">Monto Intentado:</span>
                                                    <p>${{ number_format($log['attempted_amount'], 2) }}</p>
                                                </div>
                                                <div>
                                                    <span class="font-semibold text-gray-600">Fecha Log:</span>
                                                    <p>{{ \Carbon\Carbon::parse($log['created_at'])->format('d/m/Y H:i:s') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-gray-600 py-8 italic">
                        No hay transacciones disponibles en este momento.
                    </div>
                @endforelse
            </div>

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