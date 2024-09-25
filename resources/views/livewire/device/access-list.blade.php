<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <x-slot name="header">
        {{-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Geo Localizacion') }}
        </h2> --}}
        <ol class="flex w-full flex-wrap items-center rounded-md bg-blue-gray-50 bg-opacity-60 py-2 px-4">
            <li
                class="flex cursor-pointer items-center font-sans text-sm font-normal leading-normal text-blue-gray-900 antialiased transition-colors duration-300 hover:text-pink-500">
                <a class="opacity-60">
                    <span>Lista de Dispositivos</span>
                </a>
                <span
                    class="pointer-events-none mx-2 select-none font-sans text-sm font-normal leading-normal text-blue-gray-500 antialiased">
                    /
                </span>
            </li>
            <li
                class="flex cursor-pointer items-center font-sans text-sm font-normal leading-normal text-blue-gray-900 antialiased transition-colors duration-300 hover:text-pink-500">
                <a 
                    class="font-medium text-blue-gray-900 transition-colors hover:text-pink-500" href="#">
                    Accesos
                </a>
            </li>
        </ol>
    </x-slot>

    <div class="max-w-7xl mx-auto overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-blue-700 rounded-sm  dark:bg-gray-700 dark:text-gray-400">
                <tr class="rounded-sm">
                    <th scope="col" class="px-6 py-3">
                        Modelo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Ubicacion

                    </th>
                    <th scope="col" class="px-4 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
                @foreach ($locations as $location)
                <tr
                    class="odd:bg-black odd:dark:bg-gray-900 even:bg-blue-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="text-blue-400">{{ $location->device_id }}</span>
                        {{-- <span class="text-bold text-blue-400">{{ $device->versionApp }}</span> --}}
                    </td>
                    <td class="px-6 py-4">
                        ({{ $location->latitud }},{{ $location->longitud }})
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col justify-center">

                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <button
                        class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        data-ripple-light="true" {{-- :href="route('device.list', [" from"=> 'device-list', 'device'
                        => {{ $device->id }}])" --}}
                        >
                        mapa
                    </button> 
                    </td>
                </tr>
                @endforeach
        </table>
    </div>
</div>