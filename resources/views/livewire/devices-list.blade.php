<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Dispositivos') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto overflow-x-auto shadow-md sm:rounded-lg">
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
                    </td>
                </tr>
                @endforeach
            </thead>
        </table>
    </div>
</div>