@props(["selectedPayment"])
 <div class="fixed inset-0 z-50 overflow-y-auto" wire:transition>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="cerrarModal">
                </div>

                {{-- Modal --}}
                <div
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white/90 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/20">
                    {{-- Encabezado del modal --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-r {{ $selectedPayment['pagado'] ? 'from-green-400 to-emerald-500' : 'from-orange-400 to-red-500' }} flex items-center justify-center">
                                @if($selectedPayment['status'] == 'completed')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                @else
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @endif
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Estado del Pago</h3>
                        </div>
                        <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Contenido del modal --}}
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-500">Cliente:</span>
                                    <p class="text-gray-900 font-semibold">{{ $selectedPayment['cliente'] }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-500">Monto:</span>
                                    <p class="text-green-600 font-bold">${{ number_format($selectedPayment['amount'], 2)
                                        }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="font-medium text-gray-500">Descripción:</span>
                                    <p class="text-gray-900">{{ $selectedPayment['descripcion'] }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="font-medium text-gray-500">Metodo:</span>
                                    <p class="text-gray-900">{{ $selectedPayment['method'] }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Estado del pago --}}
                        <div class="text-center py-4">
                            @if($selectedPayment['status']== 'completed')
                            <div
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-100 to-emerald-100 rounded-2xl border border-green-200">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-green-800 font-semibold">Pago Completado</span>
                            </div>
                            @else
                            <div
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-100 to-red-100 rounded-2xl border border-orange-200">
                                <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                <span class="text-orange-800 font-semibold">Pago Pendiente</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Botón cerrar --}}
                    <div class="mt-6 flex justify-end">
                        <button wire:click="cerrarModal"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 border border-blue-600 hover:border-blue-700">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>