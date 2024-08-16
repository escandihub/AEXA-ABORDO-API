<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    @if ($view == "update")
    @livewire('usuarios.edit')
    @endif

    

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-blue-700 rounded-sm  dark:bg-gray-700 dark:text-gray-400">
            <tr class="rounded-sm">
                <th scope="col" class="px-6 py-3">
                    Nombre
                </th>
                <th scope="col" class="px-6 py-3">
                    Perfil
                </th>
                <th scope="col" class="px-6 py-3">
                    taquilla
                </th>
                <th scope="col" class="px-6 py-3">

                </th>
                <th scope="col" class="px-6 py-3">
                    editar
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr
                class="odd:bg-black odd:dark:bg-gray-900 even:bg-blue-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $user->nombre_completo }}
                </th>
                <td class="px-6 py-4">
                    {{ $user->descripcion_perfil }}
                </td>
                <td class="px-6 py-4">
                    {{ $user->nombre_taquilla }}
                </td>
                <td class="px-6 py-4">
                    <button wire:click="update()" class="btn btn-primary btn-sm">Editar</button>
                    {{-- <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>