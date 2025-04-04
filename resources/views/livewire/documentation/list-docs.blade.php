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
                        wire:click='ListAccess({{ $document->destino }})'
                        >
                        ver
                    </button>
                    <button
                        class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        data-ripple-light="true">
                        ubicacion
                    </button>
                </td>
            </tr>
            @endforeach
            {{ $documents->links() }}
        </thead>
    </table>
</div>