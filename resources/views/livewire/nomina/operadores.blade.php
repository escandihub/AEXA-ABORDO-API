<div>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 p-4">
         <x-loading-notification />
        <div class="w-full max-w-6xl">

            {{-- Contenedor de la tabla con Material Expressive --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                {{-- Superficie elevada con efecto glassmorphism --}}
                <div class="bg-gradient-to-r from-purple-500/10 to-blue-500/10 p-6 border-b border-white/20">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Asignacion de operadores</h2>
                        </div>

                        {{-- Botón limpiar filtros --}}
                        <button wire:click="resetFilters"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Limpiar Filtros
                        </button>
                        <button wire:click="generateReport()">Generar</button>
                    </div>

                    {{-- Panel de filtros --}}
                    <div
                        class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Por fecha</label>
                            <div class="relative">
                                <div class="inset-y-0 left-0 pl-3 flex items-center">
                                    <button wire:click="$set('filter', 'all')"
                                        class="px-5 py-2 text-xs font-medium text-black transition-colors duration-300 rounded-md bg-blue-500 hover:bg-blue-600 sm:text-sm">
                                        Todos
                                    </button>

                                    <button wire:click="$set('filter', 'now')"
                                        class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm bg-blue-500 hover:bg-blue-600 dark:text-gray-300 ">
                                        Ahora
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- Filtro por nombre --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar por nombre o
                                autobus</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input wire:model.live="searchName" type="text" placeholder="Buscar por nombre..."
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white/70 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150 text-sm">
                            </div>
                        </div>

                        <div class="relative z-50">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filtro rango de fechas</label>
                            <x-date-piker />
                        </div>
                    </div>



                    {{-- Tabla responsiva --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            {{-- Encabezado de tabla con estilo Material Expressive --}}
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                        Ruta</th>
                                    <th
                                        class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                        Autobus</th>
                                    <th
                                        class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                        Fecha-hora</th>
                                    <th
                                        class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                        Operador 1</th>
                                </tr>
                            </thead>
                            {{-- Cuerpo de la tabla --}}
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($corridas as $corrida)
                                <tr
                                    class="hover:bg-gradient-to-r hover:from-slate-50/50  hover:to-blue-100/60 transition-all duration-300 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="rounded-md px-3 bg-gradient-to-r from-indigo-100 to-blue-200 flex items-center justify-center group-hover:from-indigo-200 group-hover:to-blue-300 transition-colors">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ $corrida->origen }} - {{ $corrida->destino }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs font-semibold text-gray-700">
                                                {{ $corrida->autobus }} - {{ $corrida->clase }}
                                            </span>
                                        </div>
                                    </td>
                                     <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <div class="flex flex-row items-center space-x-3">
                                                <span class="flex rounded-t-3xl bg-emerald-200 shadow-cyan-50 px-3 font-bold"> {{ \Carbon\Carbon::parse($corrida['fecha'])->format('d/m/Y');
                                                    }}</span>
                                                <span class="flex rounded-3xl bg-blue-300 text-blue-800 shadow-cyan-50 px-2.5">{{ $corrida->hora }}:{{ $corrida->minutos }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <p class="text-gray-500 dark:text-gray-400"> {{ $corrida->operador1 }}</p>
                                         <section class="z-10"> 
                                            {{-- <x-nomina.select-operador id="operador-{{ $corrida->id_diario_c }}" 
                                                :operadores="$operadores" :default="$corrida->operador1"
                                                :diario=" $corrida->id_diario_c" /> --}}
                                                <livewire:components.operador-selector 
                                                    :operadores="$operadores" 
                                                    :diario="$corrida->id_diario_c"
                                                    :default="$corrida->operador1"
                                                    {{-- key="selector-{{ $corrida->id_diario_c }}" --}}
                                                    wire:key="diario-{{ $corrida->id_diario_c }}"
                                                     />
                                         </section>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <p class="text-gray-500 text-lg font-medium">No se encontraron registros</p>
                                            <p class="text-gray-400 text-sm mt-1">Intenta ajustar los filtros de
                                                búsqueda
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $corridas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>