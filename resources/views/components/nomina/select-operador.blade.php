<main>
    <div x-data="select" class="relative w-[30rem]" @click.outside="open = false">
        <button @click="toggle" :class="(open) && 'ring-blue-600'" @click="$refs.searchBox.focus()"
            class="flex w-full items-center justify-between rounded-md bg-white p-2 ring-1 ring-gray-300">
            <span x-text="(language == '') ? 'Selecionar operador' : language"></span>
            <i class="fas fa-chevron-down text-xl"></i>
        </button>
        <ul class="z-20 absolute mt-1 w-full rounded bg-gray-50 ring-1 ring-gray-300" x-show="open"
            x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
            <li>
                <input type="text" x-ref="searchBox" text="{{ $default }}" placeholder="Buscar nombre"
                    class="w-full p-2 border-b-2 border-gray-300 focus:outline-none focus:border-blue-600"
                    x-model="language" @input="operadores = findByNombre(language)">
            </li>
            <template x-for="operador in operadores" :key="operador.id">
                <li class="cursor-pointer select-none p-2 hover:bg-gray-200" @click="setLanguage(operador.Nombre)">
                    <span x-text="operador.Nombre"></span>
                </li>
            </template>
        </ul>
    </div>
</main>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("select", () => ({
            open: false,
            language: '',
            operadores: @json($operadores),
            filteredOperadores: @json($operadores),
            diario: @js($diario),
            watch: {
                language(val) {
                    console.log('Language changed:', val);
                    this.operadores = this.operadores.filter((operador) => {
                        return operador.Nombre.toLowerCase().includes(val.toLowerCase());
                    });
                },
            },

            toggle() {
                // alert('Toggle clicked');
                this.language = '';
                this.open = !this.open;
                 // Aseguramos que el DOM se actualice antes de hacer foco
               this.$nextTick(() => {
                this.$refs.searchBox.focus();
            });
            },

            setLanguage(val) {
                this.language = val;
                this.open = false;
                this.nameSelected(val);
            },
            findByNombre(textoFind) {
                 console.log('Buscando por nombre:', textoFind);
                return this.operadores.reduce((result, operador) => {
                console.log('Buscando operador:', operador.Nombre);
                if(NoNull)
                const nombre = operador.Nombre.toLowerCase();
                
                    if (nombre.includes(textoFind.toLowerCase())) {
                        result.push(operador);
                    }
                    return result;
                }, []);
            },
            nameSelected(name) {
                // envia el nombre del operador seleccionado
                console.log('Nombre seleccionado:', name);
                console.log('diario c:', this.diario);
                // this.$dispatch('name-selected', {
                //     name: name,
                // });
            }
        }));
    });
</script>