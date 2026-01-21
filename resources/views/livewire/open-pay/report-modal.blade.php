<div x-data="report()" class="overflow-visible shadow-md sm:rounded-lg scroll-m-2">
    <button @click="open()"
        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <g id="Interface / Download">
                    <path id="Vector" d="M6 21H18M12 3V17M12 17L17 12M12 17L7 12" stroke="#000000" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
            </g>
        </svg>
        Generar Reporte
    </button>
    <x-modal-G3 title="Reporte de Transacciones">
        {{-- formulario de captura de fechas --}}
        <x-date-piker />
    </x-modal-G3>
</div>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("report", () => ({
             open(){
                this.show = true;
                this.$wire.show = true;
             },
             }));
    });
</script>