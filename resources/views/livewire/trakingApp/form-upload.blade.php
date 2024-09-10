<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 py-12">
    <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Agregar nueva version') }}
                </h2>
    
                {{-- <p class="mt-1 text-sm text-gray-600">
                    {{ __("Update your account's profile information and email address.") }}
                </p> --}}
            </header>
    
            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('patch')
    
                <div>
                    <x-input-label for="name" :value="__('Nombre')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"  required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="versionName" :value="__('nombre de version')" />
                    <x-text-input id="versionName" name="versionName" type="text" class="mt-1 block w-full"  required autofocus autocomplete="versionName" />
                    <x-input-error class="mt-2" :messages="$errors->get('versionName')" />
                </div>
                <div>
                    <x-input-label for="uploadAPK" :value="__('Subir version')" />
                    <x-file-attachment wire:model="files" :file="$files" mode="attachment" fileName="csv o exel" accept="*" />
                </div>
            </form>
        </div>
    </section>
</div>