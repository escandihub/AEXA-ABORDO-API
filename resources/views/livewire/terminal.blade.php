<div>
    <x-notification>

    </x-notification>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Terminal') }}
        </h2>
    </x-slot>
    <div wire:loading>
        {{-- <div class="fixed top-0 left-0 flex items-center justify-center w-full h-full z-40"> --}}
        <div class="fixed inset-0  top-0 left-0 z-50 mx-auto w-screen h-screen flex items-center justify-center" style="background: rgba(0, 0, 0, 0.3);">
            <div class="flex justify-center items-center space-x-1 text-sm text-gray-700">
                <span class="loader"></span>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-blue-700 rounded-sm  dark:bg-gray-700 dark:text-gray-400">
                <tr class="rounded-sm">
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
                @foreach ($terminales as $terminal)
                <tr
                    class="odd:bg-black odd:dark:bg-gray-900 even:bg-blue-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $terminal->user }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $terminal->terminal }}
                    </td>
                    <td class="px-6 py-4">

                        {{ $terminal->abreviacion }}
                    </td>
                    <td class="hover:bg-grey-400">
                       {{-- <button x-data x-on:click="$dispatch('show-noti')" >aaas</button> --}}
                        <button wire:click="showMap({{ $terminal->id_taquillas }})" class="btn btn-primary btn-sm">Editar</button>
                        {{-- <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        --}}
                    </td>
                </tr>
                @endforeach
        </table>
      
    </div>
    {{-- @livewire('map.container') --}}
    @livewire('map.modal-form') 

    <style>
        .loader {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: inline-block;
  position: relative;
  border: 3px solid;
  border-color: #FFF #FFF transparent;
  box-sizing: border-box;
  animation: rotation 1s linear infinite;
}
.loader::after {
  content: '';  
  box-sizing: border-box;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  margin: auto;
  border: 3px solid;
  border-color: transparent #FF3D00 #FF3D00;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  animation: rotationBack 0.5s linear infinite;
  transform-origin: center center;
}

@keyframes rotation {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
} 
    
@keyframes rotationBack {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(-360deg);
  }
}
    
    </style>
</div>

