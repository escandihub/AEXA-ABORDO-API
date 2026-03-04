<div x-data="selectorItinerario()">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            </svg>
            Ubicaciones
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Campo Origen -->
            <div class="space-y-2">
                <label for="origen" class="block text-sm font-semibold text-gray-700">
                    Origen <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path>
                        </svg>
                    </div>
                    <select id="origen" x-model="origen"
                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300">
                        <option value="">Selecciona origen</option>
                        <option value="CDMX">Ciudad de México</option>
                        <option value="MTY">Monterrey</option>
                        <option value="GDL">Guadalajara</option>
                        <option value="CUN">Cancún</option>
                        <option value="PLY">Puebla</option>
                        <option value="TJN">Tijuana</option>
                        <option value="AGS">Aguascalientes</option>
                        <option value="LPZ">La Paz</option>
                    </select>
                </div>
                <template x-if="!origen">
                    <p class="text-red-500 text-xs mt-1">Selecciona un origen</p>
                </template>
            </div>

            <!-- Campo Destino -->
            <div class="space-y-2">
                <label for="destino" class="block text-sm font-semibold text-gray-700">
                    Destino <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path>
                        </svg>
                    </div>
                    <select id="destino" x-model="destino"
                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300">
                        <option value="">Selecciona destino</option>
                        <option value="CDMX">Ciudad de México</option>
                        <option value="MTY">Monterrey</option>
                        <option value="GDL">Guadalajara</option>
                        <option value="CUN">Cancún</option>
                        <option value="PLY">Puebla</option>
                        <option value="TJN">Tijuana</option>
                        <option value="AGS">Aguascalientes</option>
                        <option value="LPZ">La Paz</option>
                    </select>
                </div>
                <template x-if="!destino">
                    <p class="text-red-500 text-xs mt-1">Selecciona un destino</p>
                </template>
            </div>
        </div>
    </div>
    <!-- Fecha -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10m-4 12l-4-4m8 8l4-4"></path>
            </svg>
            Fecha del Viaje
        </h3>

        <div class="space-y-2">
            <label for="fecha" class="block text-sm font-semibold text-gray-700">
                Fecha <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <input type="date" id="fecha" x-model="fecha"
                    class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300">
            </div>
            <template x-if="!fecha">
                <p class="text-red-500 text-xs mt-1">Selecciona una fecha</p>
            </template>
        </div>
    </div>

     <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Hora de Salida
                    </h3>

                    <div class="space-y-2">
                        <label for="hora" class="block text-sm font-semibold text-gray-700">
                            Hora <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input type="time" 
                                id="hora"
                                x-model="hora"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300">
                        </div>
                        <template x-if="!hora">
                            <p class="text-red-500 text-xs mt-1">Selecciona una hora</p>
                        </template>
                    </div>
                </div>

                <!-- Asientos Dinámicos -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Asientos <span class="text-sm font-normal text-gray-600">(<span x-text="asientos.filter(a => a.numero && a.numero.trim()).length"></span>)</span>
                    </h3>

                    <!-- Lista de Asientos -->
                    <div class="space-y-3">
                        <template x-for="(asiento, index) in asientos" :key="index">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-end justify-between gap-3">
                                <div class="flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Asiento <span x-text="index + 1"></span> <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" 
                                            x-model="asiento.numero"
                                            @input="asiento.numero = asiento.numero.toUpperCase()"
                                            placeholder="Ej: 12A"
                                            class="w-full pl-11 pr-4 py-3 bg-white border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-0 focus:bg-white transition-all duration-300 placeholder-gray-400 uppercase"
                                            maxlength="5">
                                    </div>
                                </div>

                                <button type="button"
                                    @click="eliminarAsiento(index)"
                                    x-show="asientos.length > 1"
                                    class="p-3 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200"
                                    title="Eliminar asiento">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Botón Agregar Asiento -->
                    <button type="button"
                        @click="agregarAsiento()"
                        class="w-full mt-4 px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl text-gray-700 font-medium hover:border-purple-500 hover:bg-purple-50 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Agregar Otro Asiento
                    </button>
                </div>
   
    <button @click="generarFormato()"> mostrar formato </button>
</div>
<script>
    function selectorItinerario() {
            return {
                origen: '',
                destino: '',
                fecha: '',
                asientos: [{ numero: ''}],
                hora: '',
                formatoGenerado: null,

                agregarAsiento() {
                    this.asientos.push({ numero: ''});
                },

                eliminarAsiento(index) {
                    this.asientos.splice(index, 1);
                },

                formatearFecha(fecha) {
                    if (!fecha) return '';
                    const [year, month, day] = fecha.split('-');
                    return `${day}-${month}-${year}`;
                },

                formatearHora(hora) {
                    if (!hora) return '';
                    const [h, m] = hora.split(':');
                    return `${h}.${m}`;
                },

                generarFormato() {
                    console.log(this.origen, this.hora)
                    if (!this.origen || !this.destino || !this.fecha || !this.hora) {
                        alert('Por favor completa origen, destino y fecha');
                        return;
                    }

                    const asientosValidos = this.asientos.filter(a => a.numero && a.numero.trim());

                    if (asientosValidos.length === 0) {
                        alert('Por favor agrega al menos un asiento');
                        return;
                    }

                    const fechaFormato = this.formatearFecha(this.fecha);
                    const horaFormato = this.formatearHora(this.hora);
                    const asiento = asientosValidos.map(asi => asi.numero).join('.');


                    console.log(`${this.origen}-${this.destino}. ${fechaFormato}. ASIENTO ${asiento}. ${horaFormato} HRS`)
                    return `${this.origen}-${this.destino}. ${fechaFormato}. ASIENTO ${asiento}. ${horaFormato} HRS.`;  
                },

                copiarAlPortapapeles(texto) {
                    navigator.clipboard.writeText(texto).then(() => {
                        const btn = event.target.closest('button');
                        const icono = btn.querySelector('svg');
                        const original = iconooriginal || icono.innerHTML;
                        
                        btn.style.backgroundColor = '#d1fae5';
                        btn.style.color = '#059669';
                        btn.textContent = '✓ Copiado';
                        
                        setTimeout(() => {
                            btn.style.backgroundColor = '';
                            btn.style.color = '';
                            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>';
                        }, 1500);
                    });
                },

                copiarTodoAlPortapapeles() {
                    const texto = this.formatoGenerado.join('\n');
                    navigator.clipboard.writeText(texto).then(() => {
                        const btn = event.target.closest('button');
                        btn.textContent = '✓ Copiado';
                        btn.style.opacity = '0.8';
                        
                        setTimeout(() => {
                            btn.textContent = 'Copiar Todo';
                            btn.style.opacity = '1';
                        }, 1500);
                    });
                },
                emitValues(){
                    let  description = this.generarFormato()
                     $wire.dispatch('description-created', {description: description})
                },
                init(){
                Livewire.on('request-description', () => {
                    let  description = this.generarFormato()
                     @this.dispatch('description-created', {description: description})
                }); 

                },

                limpiar() {
                    this.origen = '';
                    this.destino = '';
                    this.fecha = '';
                    this.asientos = [{ numero: '', hora: '' }];
                    this.formatoGenerado = null;
                }
            };
        }
</script>