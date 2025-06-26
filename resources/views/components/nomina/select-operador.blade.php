@props([
    'operadores' => [],
    'default' => '',
    'diario' => NULL,
])
<main
 key="diario-{{ $diario }}"
 data-diario-id="{{ $diario }}"
>
    <div x-data="{
            // Datos del componente
            diario_id: {{ $diario }},
            default: '{{ $default }}',
            operadores: @js($operadores),
            
            // Estado del componente
            open: false,
            language: '{{ $default }}',
            searchTerm: '{{ $default }}',
            filteredOperadores: @js($operadores),
            
            // Métodos
            init() {
                console.log('Iniciado con diario ID:', this.diario_id);

                $watch('diario_id', (val) => {
                   console.log('Diario ID cambiado:', val);
                });
            },

            cambiar(){
             this.$nextTick(() => this.$refs.searchBox?.focus());
            this.diario_id =  this.$refs.id_diario?.value
            },
            
            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.searchTerm = '';
                    this.language = '';
                    this.filteredOperadores = [...this.operadores];
                    this.$nextTick(() => this.$refs.searchBox?.focus());
                }
            },
            
            filterOperadores() {
                if (this.searchTerm === '') {
                    this.filteredOperadores = [...this.operadores];
                    return;
                }
                this.filteredOperadores = this.operdaores.filter(op => 
                    op.nombre.toLowerCase().includes(this.searchTerm.toLowerCase())
                );
            },
            
            setLanguage(val) {
                this.language = val;
                this.searchTerm = val;
                this.open = false;
                this.nameSelected(val);
            },
            
            nameSelected(name) {
            {{-- this.cambiar(); --}}
                console.log('Seleccionado:', name, 'Diario:', this.diario_id );
                this.$dispatch('task-updating', { message: 'Actualizando...' });
                this.$dispatch('name-selected', {
                    diario: this.diario_id,
                    name: name
                });
            }
        }" class="relative w-[30rem]" @click.outside="open = false">
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
                <input hidden x-ref="id_diario" value="{{ $diario }}">{{ $diario }}</input>
                <button @click="cambiar">cambio we</button>
                <input type="text" x-ref="searchBox" text="{{ $default }}" placeholder="Buscar nombre"
                    class="w-full p-2 border-b-2 border-gray-300 focus:outline-none focus:border-blue-600"
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
        document.addEventListener('livewire:navigated', () => {
        // This forces Alpine to re-scan the entire DOM for x-data components.
        // Use with caution, as it can be less performant than Alpine's default behavior.
        // It's usually a last resort if components aren't initializing properly.
        // window.Alpine.discoverUninitialized(); // For Alpine v2
        window.Alpine.start(); // For Alpine v3 (less likely needed as it's typically auto-started)
    });

    document.addEventListener("alpine:init", () => {
        Alpine.data("select", (operadores, defaultValue, diario) => ({
            open: false,
            language: defaultValue,
            operadores: operadores,
            id_diario: diario,
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
                    console.log('Operador actual:', operador);
                // console.log('Buscando operador:', operador.nombre);

                let nombre = operador.nombre.toLowerCase();
                
                    if (nombre.includes(textoFind.toLowerCase())) {
                        result.push(operador);
                    }
                    return result;
                }, []);
            },
            nameSelected(name) {
                // envia el nombre del operador seleccionado
                console.log('Nombre seleccionado:', name);
                console.log('diario c:', this.id_diario);
                this.$dispatch('task-updating',  { message: 'Actualizando Operador...'});
                 this.$dispatch('name-selected', {
                    diario: this.id_diario,
                     name: name,
                 });
            }
        }));
    });
</script>