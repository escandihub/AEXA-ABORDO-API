<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador de versiones') }}
        </h2>
    </x-slot>

        @include('livewire.trakingApp.form-upload')
    <div>
         
        <div class="max-w-7xl mx-auto overflow-x-auto shadow-md sm:rounded-lg">
            <button type="button" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900 float-right">nueva version</button>
  
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="text-xs text-gray-700 uppercase bg-blue-700 rounded-sm  dark:bg-gray-700 dark:text-gray-400">
                    <tr class="rounded-sm">
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            comentarios

                        </th>
                        <th scope="col" class="px-6 py-3">
                            estatus
                        </th>
                        <th scope="col" class="px-4 py-3">
                            afectados
                        </th>
                    </tr>
                </thead>
                @foreach ($versiones as $versionApp)
                <tr
                    class="odd:bg-black odd:dark:bg-gray-900 even:bg-blue-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $versionApp->nombre }}
                        <span class="text-bold text-blue-400">{{ $versionApp->versionName }}</span>
                    </th>
                    <td class="px-6 py-4">
                        {{ $versionApp->comentarios }}
                    </td>
                    <td class="px-6 py-4">

                        {{ $versionApp->active ? 'currrent' : 'old' }}
                    </td>
                    <td class="hover:bg-grey-400">
                        {{-- <button x-data x-on:click="$dispatch('show-noti')">aaas</button> --}}
                        <button wire:click=""
                            class="btn btn-primary btn-sm">Editar</button>
                        {{-- <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        --}}
                    </td>
                </tr>
                @endforeach
            </table>

        </div>
    </div>
</div>