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
        </div>

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
                <span class="text-4xl font-bold text-[#005AC1] tracking-tight">${{ number_format($monto, 2) }}</span>
            </div>

             <div class="h-px bg-gray-200 w-full"></div>

            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-500">Enviado a</span>
                <div class="text-right">
                    <span class="block text-lg font-semibold text-gray-900">{{ $nombre }} {{ $apellido }}</span>
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

        <div class="space-y-3"
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
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    /* Necesario para que el stroke-dasharray funcione en la primera carga */
    svg path { stroke-dasharray: 100; }
</style>