<div x-data="paymentGenerator()"
@scroll-to-link.window="showAll()"
@set-all.window="setAll()"
>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center p-4"
        
        >
        <div class="w-full max-w-3xl mx-auto ">
            <div class="flex justify-start mb-6">
                <button wire:click="redirectToPaymenList" wire:loading.class="animate-pulse"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600  text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Regresar
                </button>
            </div>
            <!-- Card Principal -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 space-y-6">
                <!-- Header -->
                <div class="text-center space-y-2">
                    <div
                        class="w-16 h-16 mx-auto bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                            </path>
                        </svg>
                    </div>
                    <h1
                        class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        Generar Link Openpay
                    </h1>
                    <p class="text-gray-600 text-sm">Crea enlaces de pago seguros con Openpay</p>
                </div>

                <!-- Formulario -->
                <form wire:submit="generateLink" class="max-w-2xl mx-auto space-y-6">
                    @error('general')
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4" x-ref="generalError">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    </div>
                    @enderror

                    {{-- choosing a brand --}}
                    <x-list-ofert wire-model="{{ $brand }}" :comercios="$comercios" />
                    <!-- Información del Cliente -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Información del Cliente
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Campo Nombre -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" id="name" wire:model="name"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300 placeholder-gray-400"
                                        placeholder="Ingrese el nombre">
                                </div>
                                @error('name')
                                <p class="text-red-500 text-xs mt-1 animate-pulse flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Campo Apellido -->
                            <div class="space-y-2">
                                <label for="lastname" class="block text-sm font-semibold text-gray-700">
                                    Apellido <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" id="lastname" wire:model="lastname"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300 placeholder-gray-400"
                                        placeholder="Ingrese el apellido">
                                </div>
                                @error('lastname')
                                <p class="text-red-500 text-xs mt-1 animate-pulse flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Campo Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-semibold text-gray-700">
                                    Correo Electrónico <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="email" id="email" wire:model="email"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300 placeholder-gray-400"
                                        placeholder="correo@dominio.com">
                                </div>
                                @error('email')
                                <p class="text-red-500 text-xs mt-1 animate-pulse flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Campo Teléfono -->
                            <div class="space-y-2">
                                <label for="phone" class="block text-sm font-semibold text-gray-700">
                                    Teléfono <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="tel" id="phone" wire:model="phone"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300 placeholder-gray-400"
                                        placeholder="(123) 456-7890">
                                </div>
                                @error('phone')
                                <p class="text-red-500 text-xs mt-1 animate-pulse flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del Pago -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                            Detalles del Pago
                        </h3>

                        <!-- Campo Monto -->
                        <div class="space-y-2 mb-4">
                            <label for="monto" class="block text-sm font-semibold text-gray-700">
                                Monto <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-medium text-lg">$</span>
                                </div>
                                <input type="number" step="0.01" id="monto" wire:model="monto"
                                    class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300 placeholder-gray-400 text-lg font-semibold"
                                    placeholder="0.00">
                            </div>
                            @error('monto')
                            <p class="text-red-500 text-xs mt-1 animate-pulse flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Campo Descripción -->
                        <div class="space-y-2">
                            <label for="descripcion" class="block text-sm font-semibold text-gray-700">
                                Descripción del Pago
                            </label>
                            <div class="relative">
                                <textarea id="descripcion" wire:model="descripcion" rows="3"
                                    class="w-full px-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300 placeholder-gray-400 resize-none"
                                    placeholder="Describe el concepto del pago..." maxlength="255"></textarea>
                                <div
                                    class="absolute bottom-2 right-2 text-xs text-gray-400 bg-white/80 px-2 py-1 rounded">
                                    {{ strlen($descripcion) }}/255
                                </div>
                            </div>
                            @error('descripcion')
                            <p class="text-red-500 text-xs mt-1 animate-pulse flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botón Generar -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            wire:loading.attr="disabled">
                            <div wire:loading.remove class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                                Generar Link de Pago
                            </div>
                            <div wire:loading class="flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Generando Link...
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Link Generado -->
                
                <div class="mt-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200 space-y-4 animate-fadeIn"
                    
                    x-show="showSuccess"
                    >
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-green-800">Link generado exitosamente</h3>
                    </div>

                    <div class="bg-white/70 p-4 rounded-xl border border-green-200">
                        <p class="text-xs text-gray-600 mb-2">Tu link de pago Openpay:</p>
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-lg">
                                <p class="text-sm text-gray-800 truncate">{{ $generatedLink }}</p>
                            </div>
                            <button @click="copyToClipboard('{{ $generatedLink }}')" {{-- wire:click="copyToClipboard"
                                --}}
                                class="p-2 bg-purple-100 hover:bg-purple-200 text-purple-600 rounded-lg transition-colors duration-200"
                                title="Copiar al portapapeles">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        @if(session()->has('openpay_checkout'))
                        @php $checkout = session('openpay_checkout'); @endphp
                        <div class="mt-3 text-xs text-gray-600 space-y-1">
                            <p><strong>ID:</strong> {{ $checkout['id'] }}</p>
                            <p><strong>Orden:</strong> {{ $checkout['order_id'] }}</p>
                            <p><strong>Estado:</strong> <span class="text-blue-600 font-medium">{{
                                    ucfirst($checkout['status']) }}</span></p>
                            <p><strong>Expira:</strong> {{ date('d/m/Y H:i', strtotime($checkout['expiration_date'])) }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <div class="flex space-x-3" x-ref="linkContainer">
                        <button wire:click="clear"
                            class="flex-1 bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors duration-200 text-sm font-medium">
                            Generar Otro
                        </button>
                        <a href="{{ $generatedLink }}" target="_blank"
                            class="flex-1 bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-xl transition-colors duration-200 text-sm font-medium text-center">
                            Abrir Link
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Powered by Openpay & Laravel Livewire</p>
                    <p class="text-xs text-gray-400 mt-1">
                        @if(config('openpay.sandbox'))
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                            🧪 Modo Sandbox
                        </span>
                        @else
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                            🚀 Modo Producción
                        </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("alpine:init", () => {
        Alpine.data("paymentGenerator", () => ({
            copyToClipboard(link) {
                navigator.clipboard.writeText(link).then(() => {
                    this.showToast('¡Link copiado al portapapeles!', 'success');
                }).catch(() => {
                    this.showToast('Error al copiar el link', 'error');
                });
            },
            
            showToast(message, type = 'success') {
                // Crear notificación toast
                const toast = document.createElement('div');
                const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                
                toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fadeIn transform transition-all duration-300`;
                toast.textContent = message;
                
                // Agregar al DOM
                document.body.appendChild(toast);
                
                // Animar entrada
                setTimeout(() => {
                    toast.classList.add('translate-x-0');
                }, 10);
                
                // Remover después de 3 segundos
                setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 300);
                }, 3000);
            },
            showSuccess: false,
            showAll(){
                this.showSuccess = true,
                this.moveToInfo()
            },
            moveToInfo(){
                console.log('eventooooo'); 
                this.$nextTick(() => this.$refs.linkContainer.scrollIntoView({ behavior: 'smooth' }))
            },
            MoveToError(){
                this.$nextTick(() => this.$refs.generalError.scrollIntoView({ behavior: 'smooth'}))
            },
            setAll(){
                 this.showSuccess = false 
            }
     }));
    });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</div>