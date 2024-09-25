<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <x-slot name="header">
        {{-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Dispositivos') }}
        </h2> --}}

        <li
            class="flex cursor-pointer items-center font-sans text-sm font-normal leading-normal text-blue-gray-900 antialiased transition-colors duration-300 hover:text-pink-500">
            <a class="opacity-60" :active="request()->routeIs('device.list')">
                <span>Lista de Dispositivos</span>
            </a>
            <span
                class="pointer-events-none mx-2 select-none font-sans text-sm font-normal leading-normal text-blue-gray-500 antialiased">
                /
            </span>
        </li>
    </x-slot>

    <div class="max-w-7xl mx-auto overflow-x-auto shadow-md sm:rounded-lg pt-2">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-blue-700 rounded-sm  dark:bg-gray-700 dark:text-gray-400">
                <tr class="rounded-sm">
                    <th scope="col" class="px-6 py-3">
                        identificador
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Modelo

                    </th>
                    <th scope="col" class="px-6 py-3">
                        OS
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Terminal
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Acciones
                    </th>
                </tr>

                @foreach ($devices as $device)
                <tr
                    class="odd:bg-black odd:dark:bg-gray-900 even:bg-blue-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="text-blue-400">{{ $device->identifier }}</span>
                        <span class="text-bold text-blue-400">{{ $device->versionApp }}</span>
                    </th>
                    <td class="px-6 py-4">
                        {{ $device->model }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col justify-center">
                            <span class="text-green-400">{{ $device->operatingSystem }}</span>
                            <span>{{ $device->osVersion }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        TGZ Terminal
                    </td>
                    <td class="px-6 py-4">
                        <button
                            class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            data-ripple-light="true" {{-- :href="route('device.list', [" from"=> 'device-list', 'device'
                            => {{ $device->id }}])" --}}
                            wire:click='ListAccess({{ $device->id }})'
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
            </thead>
        </table>
    </div>
</div>