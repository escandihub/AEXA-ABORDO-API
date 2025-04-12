<div>
    <x-modal name="maletas-pasajero" :show="$showDetails">
        <x-card>
            <x-slot name="title">
                Detalles de las Maletas
            </x-slot>
            <x-slot name="content">
                <div class="flex flex-col">
                    <div class="bg-white relative drop-shadow-2xl  rounded-3xl p-4 m-4">
                        <div class="flex-none sm:flex">
                            <div class="flex-auto justify-evenly">
                                {{-- first block --}}
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center  my-1">
                                        <span class="mr-3 rounded-full bg-white w-8 h-8">
                                            <img src="https://image.winudf.com/v2/image1/Y29tLmJldHMuYWlyaW5kaWEudWlfaWNvbl8xNTU0NTM4MzcxXzA0Mw/icon.png?w=&amp;fakeurl=1"
                                                class="h-8 p-1">
                                        </span>
                                        <h2 class="font-medium">clase {{ $passenger[0]->clase }}</h2>
                                    </div>
                                    {{-- unidad --}}
                                    <div class="ml-auto text-blue-800">{{ $passenger[0]->autobus }}</div>
                                </div>
                                {{-- separador --}}
                                <div class="border-b border-dashed border-b-2 my-5"></div>
                                {{-- second block --}}
                                @foreach ($passenger as $maleta)
                                <div class="flex items-center mb-4 px-5">
                                    <div class="flex flex-col text-sm">
                                        <span class="">Documentado</span>
                                        <div class="font-semibold">{{ Carbon\Carbon::parse($maleta->created_at)->format('d-m-Y h:i') }}</div>

                                    </div>
                                    <div class="flex flex-col mx-auto text-sm">
                                        <span class="">Tipo</span>
                                        <div class="font-semibold">{{ $maleta->name }}</div>

                                    </div>
                                    <div class="flex flex-col text-sm">
                                        <span class="">entregado</span>
                                        <div class="font-semibold">{{ Carbon\Carbon::parse($maleta->delivery_at)->format('d-m-Y h:i') }}</div>

                                    </div>
                                </div>
                                {{-- separador --}}
                                <div class="border-b border-dashed border-b-2 my-5"></div>
                                @endforeach
                                {{-- pasajero  --}}
                                <div class="flex items-center justify-between mb-4 px-5">
                                    <div class="flex flex-col text-sm">
                                        <span class="">Pasajero</span>
                                        <div class="font-semibold"> {{ $passenger[0]->nombre }} </div>

                                    </div>
                                    <div class="flex flex-col text-sm">
                                        <span class="">Asiento</span>
                                        <div class="font-semibold">{{ $passenger[0]->numero_asiento }}</div>

                                    </div>
                                </div>

                                {{-- generar qr  --}}
                                <div class="flex flex-col py-5  justify-center text-sm ">
                                   <h6 class="font-bold text-center">ticket de pasajero</h6>
                                   <div x-data>
                                    {{-- https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=  --}}
                                    {{-- <img src="" alt=""> --}}
                                    <livewire:qr-generator :code="$passenger[0]->autobus">
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot>
            <x-slot name="actions">
                <x-danger-button wire:click="showMore()">
                    cerrar
                </x-danger-button>
            </x-slot>
        </x-card>
    </x-modal>
</div>