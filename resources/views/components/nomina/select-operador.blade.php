<main>
    <div x-data="select" class="relative w-[30rem]" @click.outside="open = false">
        <button @click="toggle" :class="(open) && 'ring-blue-600'"
            class="flex w-full items-center justify-between rounded-md bg-white p-2 ring-1 ring-gray-300">
            <span x-text="(language == '') ? 'Choose language' : language"></span>
            <i class="fas fa-chevron-down text-xl"></i>
        </button>
        <ul class="z-20 absolute mt-1 w-full rounded bg-gray-50 ring-1 ring-gray-300" x-show="open"
            x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
<li>
    <input type="text" placeholder="Buscar nombre" class="w-full p-2 border-b-2 border-gray-300 focus:outline-none focus:border-blue-600"
        x-model="language" @input="operadores = findByNombre(language)">
</li>
            <template x-for="operador in operadores" :key="operador.id">
                <li class="cursor-pointer select-none p-2 hover:bg-gray-200" @click="setLanguage(operador.nombre)">
                    <span x-text="operador.nombre"></span>
                </li>
            </template>
        </ul>
    </div>
</main>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("select", () => ({
            open: false,
            language: ""  ||  @json($default),
            operadores: @json($operadores),
            filteredOperadores: @json($operadores),
            watch: {
                language(val) {
                    this.operadores = this.operadores.filter((operador) => {
                        return operador.nombre.toLowerCase().includes(val.toLowerCase());
                    });
                },
            },

            toggle() {
                this.open = !this.open;
            },

            setLanguage(val) {
                this.language = val;
                this.open = false;
            },
            findByNombre(nombre) {
        
                return this.operadores.reduce((result, operador) => {
                    if (operador.nombre.toLowerCase().includes(nombre.toLowerCase())) {
                        result.push(operador);
                    }
                    return result;
                }, []);
            },
        }));
    });
</script>