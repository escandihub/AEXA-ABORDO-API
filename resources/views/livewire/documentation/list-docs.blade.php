<div>
    <div class="shadow-lg rounded-lg m-3 px-12 bg-blue-500">
        <span>Filtro</span>
        <div class="flex flex-row gap-8 items-start justify-start">
            <div>
                <form class="max-w-sm mx-auto">
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecione una opcion</label>
                    <select id="countries"
                    wire:model.live="origen"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="">elija una terminal</option>
                        @foreach ($terminales as $terminal)
                        <option value="{{ $terminal->abreviacion }}">{{ $terminal->abreviacion }}</option>   
                        {{-- <option value="PTE">PTE</option>   
                        <option value="TGP">TGP</option>    --}}
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="max-w-sm mx-auto" @post-created.window="setChart($event.detail.data)">
                {{-- <x-date-piker /> --}}
                {{-- <x-date-piker /> --}}
            </div>
        </div>  
    {{-- //@includeWhen($showDetails, 'livewire.documentation.show-passager', ['toShow' => $showDetails]) --}}

    @if($showDetails)
        {{-- <x-show-passager :mostrar="$showDetails"></x-show-passager> --}}
        @include('livewire.documentation.show-passager', ["passenger" => $passangerDocs ])
    @endIf
    <div wire:loading role="status">
        <svg aria-hidden="true" class="w-8 h-8 text-black animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
        <span class="sr-only">Cargando...</span>
    </div>
    </div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-blue-700 rounded-sm  dark:bg-gray-700 dark:text-gray-400">
            <tr class="rounded-sm">
                <th scope="col" class="px-6 py-3">
                    identificador
                </th>
                <th scope="col" class="px-6 py-3">
                    pasajero

                </th>
                <th scope="col" class="px-6 py-3">
                    RUTA
                </th>
                <th scope="col" class="px-6 py-3">
                    Fecha
                </th>
                <th scope="col" class="px-6 py-3">
                    Terminal
                </th>
                <th scope="col" class="px-4 py-3">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documents as $document)
            <tr
                class="odd:bg-black odd:dark:bg-gray-900 even:bg-blue-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <span class="text-blue-400">{{ $document->pasajero_id }}</span>
                </th>
                <td class="px-6 py-4">
                    <div class="flex flex-col items-start justify-start gap-4">
                        <span class="bg-blue-300 text-blue-900 shadow-md rounded-md p-2"> {{ $document->nombre }}</span>
                        <span>{{ $document->origen }} - <span class="text-green-400">{{ $document->destino
                                }}  -  <span class="text-purple-600">{{ $document->horario }}</span> </span></span>
                        <span> {{ $document->nMaletas }} </span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col justify-center">
                        <span class="text-green-400">{{ $document->destino }}</span>
                    </div>
                </td>
                {{-- fecha --}}
                <td class="px-6 py-4">
                    <div class="flex flex-col justify-center">
                        <span class="text-green-400">{{ $document->created_at }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <button
                        class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        data-ripple-light="true" {{-- :href="route('document.list', [" from"=> 'document-list',
                        'document'
                        => {{ $document->id }}])" --}}
                        {{-- wire:click='showMore()' --}}
                        wire:click="getMaletas({{ $document->pasajero_id }})"
                        >
                        ver
                    </button>
                    <button
                        class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        data-ripple-light="true">
                        ubicacion
                    </button>
                </td>
                <td>
                    <div class="relative flex w-96 flex-col rounded-lg bg-white">
                        <div class="p-6">
                            <h4 class="mb-2 block font-sans text-xl"> Seguimiento de Maletas </h4>
                            <div>
                                {{-- aqui va la lista de maletas  --}}
                            </div>
                        </div>
                    </div>
                </td>
                
            </tr>
        </tbody>
            @endforeach
            {{ $documents->links() }}
        
    </table>
</div>