<div>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 p-4">
        <div class="w-full max-w-6xl">
            {{-- Encabezado --}}
            <div class="text-center mb-8">
                <h1
                    class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-2">
                    Consulta de Pagos
                </h1>
                <p class="text-gray-600 text-lg">Gestiona y consulta el estado de los pagos</p>
            </div>
            <div class="flex justify-end mb-6">
                {{-- Botón para generar link de pago --}}
                <div>
                    <button wire:loading.class="animate-pulse"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                        wire:click='redirectToPaymentLinkGenerator'>

                        Link de pago
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
            </div>
            
            {{-- Contenedor de la tabla con Material Expressive --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                {{-- Superficie elevada con efecto glassmorphism --}}
                <div class="bg-gradient-to-r from-purple-500/10 to-blue-500/10 p-6 border-b border-white/20">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Registros de Pagos</h2>
                        </div>

                        {{-- Botón limpiar filtros --}}
                        <button wire:click="clearFilters"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Limpiar Filtros
                        </button>
                        @can('isGerente')
                        <livewire:open-pay.report-modal />
                        @endcan
                    </div>

                    {{-- Panel de filtros --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Filtro por nombre --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar por Cliente</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input wire:model.live="searchName" type="text" placeholder="Nombre del cliente..."
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white/70 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150 text-sm">
                            </div>
                        </div>

                        {{-- Filtro por estado --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Pago</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <select wire:model.live="statusFilter"
                                    class="block w-full pl-10 pr-8 py-2.5 border border-gray-300 rounded-lg bg-white/70 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150 text-sm appearance-none">
                                    <option value="">Todos los estados</option>
                                    <option value="completed">Pagado</option>
                                    <option value="charge_pending">Pendiente</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Filtro por fecha --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input wire:model.live="dateFilter" type="date"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white/70 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150 text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Contador de resultados --}}
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                        <span>Mostrando {{ count($filteredPayments) }} de {{ count($payments) }} registros</span>
                        @if(count($filteredPayments) !== count($payments))
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Filtros activos
                        </span>
                        @endif
                    </div>
                </div>
                @livewire('components.pay.modal-logs')
                {{-- Tabla responsiva --}}
                <div class="overflow-x-auto relative">
                    {{-- <x-payments.loading-table /> --}}
                    <table class="w-full">
                        {{-- Encabezado de tabla con estilo Material Expressive --}}
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                    ID</th>
                                    <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                    Hecho por</th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                    Cliente</th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                    Fecha</th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                    Monto</th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                    Descripción</th>
                                <th
                                    class="px-6 py-4 text-center text-sm font-semibold text-gray-700 tracking-wider border-b border-gray-200">
                                    Acciones</th>
                            </tr>
                        </thead>

                        {{-- Cuerpo de la tabla --}}
                        <tbody class="divide-y divide-gray-100">
                            @forelse($payments as $payment)
                            <tr
                                class="hover:bg-gradient-to-r hover:from-purple-50/50 hover:to-blue-50/50 transition-all duration-300 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-100 to-blue-100 flex items-center justify-center group-hover:from-purple-200 group-hover:to-blue-200 transition-colors">
                                            <span class="text-sm font-medium text-gray-700">#{{ $payment['id'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <span
                                        class="text-white bg-blue-600 rounded-md px-2 text-center"
                                        >{{ $payment['user'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $payment['cliente'] }}</div>
                                    <div>
                                        <span>Link pago</span>
                                        <x-open-pay.button-copia :link="$payment['checkout_link']" />
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{
                                        \Carbon\Carbon::parse($payment['fecha'])->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-green-600">${{
                                        number_format($payment['amount'], 2) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 max-w-xs truncate"
                                        title="{{ $payment['description'] }}">
                                        {{ $payment['description'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div wire:key="consultarPago{{ $payment['order_id'] }}"
                                        wire:target="consultarPago('{{ $payment['order_id'] }}')">
                                        {{-- wire:loading.attr="disabled" wire:loading.class="animate-pulse" --}}
                                        <button wire:click="consultarPago('{{ $payment['order_id'] }}')"
                                            
                                            class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 disabled:bg-blue-400 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 border-0 relative overflow-hidden group backdrop-blur-sm">

                                            <!-- Icon and text (default state) -->
                                            <svg wire:loading.remove
                                                wire:target="consultarPago('{{ $payment['order_id'] }}')"
                                                class="w-4 h-4 mr-2 transition-all duration-300 group-hover:rotate-12"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                                </path>
                                            </svg>

                                            <!-- Loading dots -->
                                            <div wire:loading wire:target="consultarPago('{{ $payment['order_id'] }}')"
                                                class="flex items-center space-x-1 mr-2">
                                                <div class="w-2 h-2 bg-white rounded-full animate-bounce"></div>
                                                <div class="w-2 h-2 bg-white rounded-full animate-bounce"
                                                    style="animation-delay: 0.1s"></div>
                                                <div class="w-2 h-2 bg-white rounded-full animate-bounce"
                                                    style="animation-delay: 0.2s"></div>
                                            </div>
                                            {{-- {{ $payment['order_id'] }} - --}}
                                            <span wire:loading.remove
                                                wire:target="consultarPago('{{ $payment['order_id'] }}')">
                                                Consultar</span>
                                            <span wire:loading
                                                wire:target="consultarPago('{{ $payment['order_id'] }}')">Consultando
                                                pago...</span>
                                        </button>
                                    </div>
                                    <div>
                                        {{-- @click="$dispatch('openPaymentLogsModal')" --}}
                                        <button wire:click="consultaLogs('{{ $payment['order_id'] }}')">logs</button>
                                        {{-- <button @click="$dispatch('openPaymentLogsModal')">logs</button> --}}
                                    </div>
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No se encontraron registros</p>
                                        <p class="text-gray-400 text-sm mt-1">Intenta ajustar los filtros de búsqueda
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                            {{ $payments->links() }}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal de estado de pago --}}
        @if($showPaymentStatus && $selectedPayment)
        <x-payments.card-info :selectedPayment="$selectedPayment" />
        @endif
    </div>
</div>