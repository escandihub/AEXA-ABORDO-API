<div class="min-h-screen bg-gradient-to-b from-[#F2F6FC] to-[#E3EEFF] flex items-center justify-center p-6"
     x-data="{ shown: false, particles: false }"
     x-init="setTimeout(() => { shown = true; setTimeout(() => particles = true, 200); }, 100)"
     x-cloak>

    <div class="w-full max-w-[420px] bg-white rounded-[36px] p-8 text-center relative overflow-hidden shadow-[0_8px_30px_rgb(0,90,193,0.12)]"
         x-show="shown"
         x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-700"
         x-transition:enter-start="opacity-0 translate-y-12 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100">

        <div class="relative flex justify-center items-center mb-8">
            @if ($pasajero->status)
              
            <div class="relative z-10 flex items-center justify-center w-28 h-28 rounded-full bg-green-50">
                <svg class="w-16 h-16 text-green-600 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 13l4 4L19 7"
                          x-show="shown"
                          x-transition:enter="transition ease-out duration-500 delay-300"
                          x-transition:enter-start="[stroke-dasharray:100] [stroke-dashoffset:100] opacity-0"
                          x-transition:enter-end="[stroke-dasharray:100] [stroke-dashoffset:0] opacity-100" />
                </svg>
            </div>

            <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-0">
                <template x-if="particles">
                    <div class="relative w-full h-full">
                        <div class="absolute top-1/2 left-1/2 w-3 h-3 bg-[#FFD35B] rounded-full"
                             x-transition:enter="transition-all ease-out duration-700"
                             x-transition:enter-start="opacity-100 translate-x-0 translate-y-0 scale-50"
                             x-transition:enter-end="opacity-0 -translate-x-16 -translate-y-14 rotate-45 scale-110"></div>
                        <div class="absolute top-1/2 left-1/2 w-2.5 h-2.5 bg-[#C4EED0] rounded-full"
                             x-transition:enter="transition-all ease-out duration-800 delay-75"
                             x-transition:enter-start="opacity-100 translate-x-0 translate-y-0 scale-50"
                             x-transition:enter-end="opacity-0 translate-x-14 -translate-y-16 rotate-90 scale-125"></div>
                        <div class="absolute top-1/2 left-1/2 w-3 h-3 bg-[#A8C7FA] rounded-sm"
                             x-transition:enter="transition-all ease-out duration-600 delay-50"
                             x-transition:enter-start="opacity-100 translate-x-0 translate-y-0 scale-50"
                             x-transition:enter-end="opacity-0 -translate-x-12 translate-y-12 rotate-180 scale-105"></div>
                         <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-[#FFD35B] rounded-full"
                             x-transition:enter="transition-all ease-out duration-900"
                             x-transition:enter-start="opacity-100 translate-x-0 translate-y-0 scale-50"
                             x-transition:enter-end="opacity-0 translate-x-16 translate-y-10 rotate-12 scale-90"></div>
                        <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-[#005AC1] rounded-full"
                             x-transition:enter="transition-all ease-out duration-500 delay-100"
                             x-transition:enter-start="opacity-100 translate-x-0 translate-y-0 scale-50"
                             x-transition:enter-end="opacity-0 translate-y-20 scale-150"></div>
                    </div>
                </template>
            </div>
              @else
{{-- cuando una operacion es fallida  --}}
<div class="relative z-10 flex items-center justify-center w-28 h-28 rounded-full bg-red-50 border-4 border-red-100/50 shadow-inner"
         :class="{ 'animate-shake': !shown }"> <svg class="w-14 h-14 text-red-600 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 18L18 6M6 6l12 12"
                  x-show="shown"
                  x-transition:enter="transition ease-out duration-500 delay-300"
                  x-transition:enter-start="[stroke-dasharray:100] [stroke-dashoffset:100] opacity-0"
                  x-transition:enter-end="[stroke-dasharray:100] [stroke-dashoffset:0] opacity-100" />
        </svg>
    </div>

    <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-0">
        <template x-if="particles">
            <div class="relative w-full h-full">
                <div class="absolute top-1/2 left-1/2 w-3 h-3 bg-red-500 rounded-full" x-transition:enter="transition-all duration-700" x-transition:enter-start="opacity-100 scale-50" x-transition:enter-end="opacity-0 -translate-x-16 -translate-y-14 rotate-45 scale-110"></div>
                <div class="absolute top-1/2 left-1/2 w-2.5 h-2.5 bg-red-300 rounded-full" x-transition:enter="transition-all duration-800 delay-75" x-transition:enter-start="opacity-100 scale-50" x-transition:enter-end="opacity-0 translate-x-14 -translate-y-16 rotate-90 scale-125"></div>
                <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-slate-500 rounded-full" x-transition:enter="transition-all duration-500 delay-100" x-transition:enter-start="opacity-100 scale-50" x-transition:enter-end="opacity-0 translate-y-20 scale-150"></div>
            </div>
        </template>
    </div>
            @endif
        </div>
        @if ($pasajero->status)
             <div x-show="shown"
             x-transition:enter="transition ease-out duration-500 delay-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <h1 class="text-[32px] font-semibold text-[#1F1F1F] leading-tight mb-3">
                ¡Pago exitoso!
            </h1>
            <p class="text-gray-600 mb-8 text-lg">Tu dinero ha sido enviado.</p>
        </div>

        <div class="bg-[#F2F6FC] rounded-[24px] p-6 mb-8 text-left space-y-5"
             x-show="shown"
             x-transition:enter="transition ease-out duration-500 delay-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <div class="text-center mb-4">
                <span class="block text-sm font-medium text-gray-500 mb-1">Monto total</span>
                <span class="text-4xl font-bold text-[#005AC1] tracking-tight">${{ number_format($pasajero->monto, 2) }}</span>
            </div>

             <div class="h-px bg-gray-200 w-full"></div>

            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-500">Autorizado por</span>
                <div class="text-right">
                    <span class="block text-lg font-semibold text-gray-900">{{ $pasajero->nombre_completo  }}</span>
                </div>
            </div>

            @if($comentario)
            <div class="bg-white/70 rounded-xl p-4 mt-2 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#A8C7FA]"></div>
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider pl-2">Nota</span>
                <p class="text-sm text-gray-700 mt-1 italic pl-2 relative z-10">
                    "{{ $comentario ?? 'Sin comentarios' }}"
                </p>
            </div>
            @endif
        </div>
        @else
        {{-- segunda parte de pago fallido  --}}

        <div x-show="shown"
         x-transition:enter="transition ease-out duration-500 delay-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="mt-6">
        <h1 class="text-3xl font-bold text-red-600 leading-tight">
            Transacción Declinada
        </h1>
        <p class="text-gray-500 text-sm mt-1">Tu pago no pudo ser procesado</p>
    </div>

    <div class="w-full bg-white border border-gray-100 rounded-[32px] p-8 mt-8 shadow-xl shadow-red-900/5 relative overflow-hidden"
         x-show="shown"
         x-transition:enter="transition ease-out duration-500 delay-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0">
        
        <div class="absolute left-0 top-0 bottom-0 w-2 bg-red-500"></div>

        <div class="text-center mb-6">
            <span class="block text-xs uppercase tracking-widest font-bold text-gray-400 mb-1">Monto de Intento</span>
            <span class="text-5xl font-black text-slate-800 tracking-tighter">
                ${{ number_format($pasajero->monto, 2) }}
            </span>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-50">
                <span class="text-sm font-medium text-gray-400">Concepto</span>
                <span class="text-sm font-bold text-slate-700">{{ $pasajero->concepto }}</span>
            </div>

            <div class="bg-red-50 rounded-2xl p-4 mt-4 border border-red-100">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="text-left">
                        <p class="text-sm font-bold text-red-800 mb-1">¿Qué sucedió?</p>
                        <p class="text-xs text-red-700 leading-relaxed">
                            No logramos validar la transacción con tu banco. Por favor, 
                            <span class="font-bold underline">verifica los datos de tu tarjeta</span> 
                            o comunícate directamente con tu <strong>asesor asignado</strong> para asistencia inmediata.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        @endif
       

        {{-- <div class="space-y-3"
             x-show="shown"
             x-transition:enter="transition ease-out duration-500 delay-400"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <button class="w-full bg-[#005AC1] text-white h-14 rounded-full font-semibold text-lg shadow-md hover:bg-[#004494] hover:shadow-lg transition-all active:scale-[0.98]">
                Listo
            </button>

            <button class="w-full bg-[#DDE9FF] text-[#005AC1] h-14 rounded-full font-semibold text-lg hover:bg-[#CDE0FF] transition-colors active:scale-[0.98]">
                 Ver comprobante
            </button>
        </div> --}}
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    /* Necesario para que el stroke-dasharray funcione en la primera carga */
    svg path { stroke-dasharray: 100; }
</style>