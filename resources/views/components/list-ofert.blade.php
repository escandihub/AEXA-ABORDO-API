@props(['wireModel' => 'select'])


<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100" x-data="BrandList('{{ $wireModel }}')">
    <div class="flex flex-col gap-4">

        <h3 class="mb-4 text-lg font-medium text-heading">Selecciona la marca:</h3>
        <ul class="select-none grid w-full gap-4 md:grid-cols-3">
            <template x-for="brand in marcas" :key="brand.name">
            <li >
                <input type="radio" :id="`option-'${brand.name}`" :value="brand.name" class="hidden peer"
                    required="" wire:model="{{ $wireModel }}">
                <label :for="`option-'${brand.name}`"  @click="emitOrSet(brand.name)"
                    :class="selectOption ==  brand.name ? 'bg-blue-400 rounded-md' : ''"
                    class="inline-flex items-center justify-between w-full p-5 text-body bg-neutral-primary-soft border-1 border-default rounded-base cursor-pointer peer-checked:hover:bg-brand-softer peer-checked:border-brand-subtle peer-checked:bg-brand-softer hover:bg-neutral-secondary-medium peer-checked:text-fg-brand-strong">
                    <div class="block">
                        <template x-for="file in brand.brands" :key="file">
                            <img  :src="`${assetPath}${file}`" alt="brand logo" class="w-36 h-10">
                        </template>
                        <div class="w-full font-medium mb-1" x-text="brand.tag"> </div>
                        <div class="w-full text-sm">A JavaScript library for building user
                            interfaces.</div>
                    </div>
                </label>
            </li>
            </template>
        </ul>
        @error('selectOption')
        <p class="text-red-500 text-xs mt-1 animate-pulse flex items-center">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
        </p>
        @enderror

    </div>
    <script>
        document.addEventListener("alpine:init", () => {
        Alpine.data("BrandList", (wireModel) => ({ 
            wireModel: wireModel,
            selectOption: 'aexa',
            marcas: [
                {
                    id: 1,
                    name: "aexa",
                    tag: "aexa - blue",
                    brands: ['AE/AexaLogo_azul.png', 'AE/Logo_GranExpresoBlue.png']
                },
                {
                    id: 2,
                    name: "titanium",
                    tag: "titanium",
                    brands: ['TI/Autobuses_TITANIUM.png']
                },
                {
                    // Transportista
                    id: 3,
                    name: "Transportista",
                    tag: "Panchera - aexa light",
                    brands: ['Aexa_ligh.png', 'PANCHERA_color.png']
                },
                {
                    // expreso
                    id: 4,
                    name: 'expreso',
                    tag: "TLA Titanium",
                    brands: ['TI/Logo_TLATitanium.png']
                }
            ],
            emitOrSet(name){
                console.log(this.file)
                console.log(this.wireModel)
                this.selectOption = name; 
                @this.$set('selectOption', name)
            },
             get assetPath() {
                return '{{ Vite::asset('resources/images/marcas/') }}';
            }
        }) ) })
    </script>
</div>