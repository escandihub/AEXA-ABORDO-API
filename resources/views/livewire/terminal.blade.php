<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Terminal') }}
        </h2>
    </x-slot>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-blue-700 rounded-sm  dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Origen

                    </th>
                    <th scope="col" class="px-6 py-3">
                        Abreviacion
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>

            <body>
                @foreach ($terminales as $terminal)
                <tr
                    class="odd:bg-black odd:dark:bg-gray-900 even:bg-blue-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $terminal->nombre }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $terminal->origen }}
                    </td>
                    <td class="px-6 py-4">

                        {{ $terminal->abreviacion }}
                    </td>
                    <td class="">
                        <button wire:click="showMap({{ $terminal->id_terminal }})" class="btn btn-primary btn-sm">Editar</button>
                        {{-- <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        --}}
                    </td>
                </tr>
                @endforeach
            </body>
        </table>
      
    </div>
    {{-- @livewire('map.container') --}}
    @livewire('map.modal-form')
</div>

