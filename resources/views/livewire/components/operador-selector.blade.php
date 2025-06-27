{{-- wire:key="diario-{{ $diario }}" --}}
<main  data-diario-id="{{ $diario }}">
    <div class="relative w-[30rem]" x-data="{ open: @entangle('open') }" @click.outside="open = false">
        <button 
            wire:click="toggle" 
            :class="open && 'ring-blue-600'"
            class="flex w-full items-center justify-between rounded-md bg-white p-2 ring-1 ring-gray-300"
        >
            <span>{{ empty($language) ? 'Seleccionar operador' : $language }}</span>
            <i class="fas fa-chevron-down text-xl"></i>
        </button>
        
        @if($open)
            <ul class="z-20 absolute mt-1 w-full rounded bg-gray-50 ring-1 ring-gray-300"
                x-show="open"
                x-transition:enter="transition ease-out duration-100" 
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0" 
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 translate-y-0" 
                x-transition:leave-end="opacity-0 -translate-y-2"
            >
                <li>
                    <input type="hidden" value="{{ $diario }}">
                    <button wire:click="cambiar" type="button" class="text-sm text-blue-600 p-1">
                        Cambiar Diario
                    </button>
                    <input 
                        type="text" 
                        wire:model.live="searchTerm"
                        placeholder="Buscar nombre"
                        class="w-full p-2 border-b-2 border-gray-300 focus:outline-none focus:border-blue-600"
                        x-ref="searchBox"
                        x-init="$refs.searchBox.focus()"
                    >
                </li>
                
                @forelse($filteredOperadores as $operador)
                    <li class="cursor-pointer select-none p-2 hover:bg-gray-200" 
                        wire:click="setLanguage('{{ $operador['nombre'] }}')"
                    >
                        <span>{{ $operador['nombre'] }}</span>
                    </li>
                @empty
                    <li class="p-2 text-gray-500">
                        No se encontraron operadores
                    </li>
                @endforelse
            </ul>
        @endif
    </div>
</main>