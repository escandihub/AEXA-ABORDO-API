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
    
            {{-- {{ route('profile.update') }} --}}
            {{-- <form method="post" action="saveNewApp" class="mt-6 space-y-6"> --}}
            <form method="post" wire:submit="saveNewApp" class="mt-6 space-y-6">
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
                    <x-file-attachment wire:model="file" :file="$file" mode="attachment" fileName="android app" accept=".apk" />
                    <x-input-error class="mt-2" :messages="$errors->get('file')" />
                        @error('file') <span class="error">{{ $message }}</span> @enderror 
                </div>
                <x-primary-button>{{ __('guardar') }}</x-primary-button>
                @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Se ha guardado exitosamente la nueva app</p>
            @endif
            </form>
        </div>
    </section>
</div>