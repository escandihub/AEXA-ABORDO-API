<?php

namespace App\Livewire\OpenPay;

use Livewire\Component;
use App\Models\Openpay\payment;

class PaymentTableComponent extends Component
{
   // public $payments = [];
    public $filteredPayments = [];
    public $selectedPayment = null;
    public $showPaymentStatus = false;
    
    // Filtros
    public $searchName = '';
    public $statusFilter = '';
    public $dateFilter = '';

    public function mount()
    {
        // Datos de ejemplo - reemplaza con tu lógica de base de datos
        $this->payments = [
            [
                'id' => 1,
                'cliente' => 'Juan Pérez',
                'fecha' => '2024-06-15',
                'monto' => 1500.00,
                'descripcion' => 'Servicio de consultoría',
                'pagado' => true
            ],
            [
                'id' => 2,
                'cliente' => 'María González',
                'fecha' => '2024-06-20',
                'monto' => 2800.50,
                'descripcion' => 'Desarrollo web',
                'pagado' => false
            ],
            [
                'id' => 3,
                'cliente' => 'Carlos Ruiz',
                'fecha' => '2024-06-22',
                'monto' => 750.00,
                'descripcion' => 'Mantenimiento sistema',
                'pagado' => true
            ],
            [
                'id' => 4,
                'cliente' => 'Ana López',
                'fecha' => '2024-06-25',
                'monto' => 3200.00,
                'descripcion' => 'Aplicación móvil',
                'pagado' => false
            ],
            [
                'id' => 5,
                'cliente' => 'Roberto Silva',
                'fecha' => '2024-06-10',
                'monto' => 1250.75,
                'descripcion' => 'Diseño gráfico',
                'pagado' => true
            ],
            [
                'id' => 6,
                'cliente' => 'Carmen Morales',
                'fecha' => '2024-06-27',
                'monto' => 4500.00,
                'descripcion' => 'E-commerce completo',
                'pagado' => false
            ],
        ];
        
        $this->applyFilters();
    }

    public function updatedSearchName()
    {
        $this->applyFilters();
    }

    public function updatedStatusFilter()
    {
        $this->applyFilters();
    }

    public function updatedDateFilter()
    {
        $this->applyFilters();
    }

    public function applyFilters()
    {
        $this->filteredPayments = collect($this->payments)->filter(function ($payment) {
            $matchesName = empty($this->searchName) || 
                          str_contains(strtolower($payment['cliente']), strtolower($this->searchName));
            
            $matchesStatus = empty($this->statusFilter) || 
                           ($this->statusFilter === 'pagado' && $payment['pagado']) ||
                           ($this->statusFilter === 'pendiente' && !$payment['pagado']);
            
            $matchesDate = empty($this->dateFilter) || 
                          $payment['fecha'] === $this->dateFilter;
            
            return $matchesName && $matchesStatus && $matchesDate;
        })->values()->toArray();
    }

    public function clearFilters()
    {
        $this->searchName = '';
        $this->statusFilter = '';
        $this->dateFilter = '';
        $this->applyFilters();
    }

    public function consultarPago($paymentId)
    {
        $this->selectedPayment = collect($this->filteredPayments)->firstWhere('id', $paymentId);
        $this->showPaymentStatus = true;
    }

    public function cerrarModal()
    {
        $this->showPaymentStatus = false;
        $this->selectedPayment = null;
    }

    public function query(){
        return payment::query()
        ->joinCustomer();
        // ->where('payments.creation_date', '=', now());

    }


    public function render()
    {
        return view('livewire.open-pay.payment-table-component', [
            'payments' => $this->query()->get(),
        ]);
    }
}
