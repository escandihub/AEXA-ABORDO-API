<div x-data="{ darkMode: false }" :class="{ 'dark': darkMode }" class="py-12 bg-blend-lighten bg-slate-300">

    {{-- Botón para alternar entre modos claro y oscuro --}}
    {{-- <div class="container mx-auto mb-4">
        <button @click="darkMode = !darkMode" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600">
            <span x-text="darkMode ? 'Modo Claro' : 'Modo Oscuro'"></span>
        </button>
    </div> --}}

    {{-- inicia otro bloque chido --}}
    <x-loading-notification />

    
    <section class="container px-4 mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
    
                    {{-- <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">240 vendors</span> --}}
                </div>
    
                {{-- <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">These companies have purchased in the last 12 months.</p> --}}
            </div>
    
            <div class="flex items-center mt-4 gap-x-3">
                <button  wire:click="generateReport()" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">
                    <svg width="20" height="20" class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V4M7 14H5a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2m-1-5-4 5-4-5m9 8h.01"/>
                    </svg>
                    <span>Generar reporte</span>
                </button>
    
                {{-- <button class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
    
                    <span>Add vendor</span>
                </button> --}}
            </div>
        </div>
    
        <div class="mt-6 md:flex md:items-center md:justify-between">
            <div class="inline-flex overflow-hidden bg-white border divide-x rounded-lg dark:bg-gray-900 rtl:flex-row-reverse dark:border-gray-700 dark:divide-gray-700">
                <button wire:click="$set('filter', 'all')"  class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 bg-gray-100 sm:text-sm dark:bg-gray-800 dark:text-gray-300">
                   Todos
                </button>
    
                <button wire:click="$set('filter', 'now')" class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">
                    Ahora
                </button>
                <input type="date" wire:model="date" class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">
    
                {{-- <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">
                    Unmonitored
                </button> --}}
            </div>
    
            <div class="relative flex items-center mt-4 md:mt-0">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-3 text-gray-400 dark:text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
    
                <input wire:model.lazy='search' type="text" placeholder="Buscar" class="block w-full py-1.5 pr-5 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
            </div>
        </div>
    
        <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:mx-2 sm:w-full md:w-full lg:-mx-6">
                <div class="inline-block min-w-full py-2 align-middle sm:w-full md:w-full lg:px-8 2xl:px-16">
                    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg relative">
                        {{-- Loading overlay específico para el contenido de la tabla --}}
                        <div wire:loading class="absolute inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50 rounded-lg">
                            <div class="flex flex-col justify-center items-center p-4 rounded-lg h-full shadow-lg opacity-25 ">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-white"></div>
                                <p class="mt-3 text-white font-medium">Actualizando operador...</p>
                            </div>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <button class="flex items-center gap-x-3 focus:outline-none">
                                            <span>Ruta</span>
    
                                            <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                            </svg>
                                        </button>
                                    </th>
    
                                    <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Autobus
                                    </th>
    
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Fecha
                                    </th>
    
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">Operador 1</th>
    
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">Acciones</th>
    
                                    <th scope="col" class="relative py-3.5 px-4">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                @foreach ($corridas as $corrida)
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="flex flex-col items-center gap-x-3">
                                            <div>
                                            <h2 class="font-medium text-gray-800 dark:text-white "><span> {{ $corrida->id_diario_c }} -- {{ $corrida->origen }} - <span class="text-green-400"> {{ $corrida->destino }}
                                            </span></span></h2>
                                            </div>
                                            <span class="text-sm inline px-3 py-1 font-normal rounded-full text-blue-600 bg-gray-800">{{ $corrida->clase }}</span>
                                        </div>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-gray-800">
                                            {{  $corrida->autobus }}
                                        </div>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-gray-800">
                                            {{ $corrida->fecha }}
                                        </div>
                                        <div class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-gray-800">
                                            {{  $corrida->hora }}: {{ $corrida->minutos }}
                                        </div>
                                    </td>
                                   
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div>
                                            {{-- <h4 class="text-gray-700 dark:text-gray-200">{{  $corrida->fecha }}</h4> --}}
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
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        
                                    </td>
    
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        {{-- <div class="w-48 h-1.5 bg-blue-200 overflow-hidden rounded-full">
                                            <div class="bg-blue-500 w-2/3 h-1.5"></div>
                                        </div> --}}
                                    </td>
    
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <button class="px-1 py-1 text-gray-500 transition-colors duration-200 rounded-lg dark:text-gray-300 hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{ $corridas->links() }}

    </section>
</div>