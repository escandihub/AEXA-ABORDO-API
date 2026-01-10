<?php

namespace App\Livewire\OpenPay;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Openpay\payment;
use App\Models\Openpay\Transaction;
use App\Services\PaymentServices\TransactionStatusService;


class PaymentTableComponent extends Component
{
     use WithPagination;
    // public $payments = [];
    public $filteredPayments = [];
    public $selectedPayment = null;
    public $showPaymentStatus = false;
    
    // Filtros
    public $searchName = '';
    public $statusFilter = '';
    public $dateFilter = '';

    private $transactionStatusService;

    public function mount(TransactionStatusService $transactionStatusService)
    {
       $this->transactionStatusService = $transactionStatusService;
        // Datos de ejemplo - reemplaza con tu lógica de base de datos
       
        // $this->applyFilters();

    }
    public function redirectToPaymentLinkGenerator()
    {
        return $this->redirectRoute('pay.make');
    }

    public function updatedSearchName()
    {
        // $this->applyFiltersDB();
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
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
    public function applyFiltersDB(){
        \Log::info('0.si esta buscando');
       $query = $this->query();

        $query->when($this->searchName , function($query, $search) {
            \Log::info("filtrando {$search}");
            $query->where('customers.email', 'LIKE', '%' . $search . '%');
        });

        $query->when($this->statusFilter, function($query, $search){
            $query->where('transactions.status', $search);
        });
        $query->when($this->dateFilter, function($query, $search){
            $query->whereDate('payments_buttons.created_at', $search);
        });
        /* ->when($this->statusFilter != '', function($query, $status){
             $query->where('transactions.status', "%{$status}%");
        })->when($this->dateFilter != '', function($query, $date){
            $query->whereDate('payments_buttons.created_at', $date);
        }); */

        return $query;
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
        \Log::info("Consultando pago con ID: {$paymentId}");
        // $this->selectedPayment = collect($this->filteredPayments)->firstWhere('id', $paymentId);
        $this->selectedPayment = Transaction::where('order_id',  $paymentId)->first();
        \Log::info("Pago consultado: ", ['payment' => $this->selectedPayment]);
        $this->showPaymentStatus = true;
    }
    public function consultaLogs($orderId){
        try {
            $logs = Transaction::where('order_id', $orderId)->get();
            $logs = $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'transaction_id' => $log->transaction_id,
                    'status' => $log->status,
                    'amount' => $log->amount,
                    'currency' => $log->currency,
                    'method' => $log->method,
                    'error_message' => $log->error_message,
                    'error_details' => $log->error_details,
                    'gateway_response_code' => $log->gateway_response_code,
                    'attempted_amount' => $log->attempted_amount,
                    'created_at' => $log->created_at,
                    'updated_at' => $log->updated_at,
                    'logs' => $log->logs()->get()
                ];
            })->toArray(); 

            \Log::info($logs);
            $this->dispatch('open-logs-modal', $logs);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function cerrarModal()
    {
        $this->showPaymentStatus = false;
        $this->selectedPayment = null;
    }

    public function query(){
        return payment::query()
        ->joinCustomer()
        ->orderBy('payments_buttons.id', 'DESC');
        // ->where('payments.creation_date', '=', now());

    }


    public function render()
    {
        \Log::info($this->applyFiltersDB()->get());
        $pagos = $this->applyFiltersDB()->paginate(10)->through(fn ($payment) => [
                'id' => $payment->id,
                'cliente' => $payment->cliente,
                'amount' => $payment->amount,
                'fecha' => $payment->created_at->format('Y-m-d'),
                'description' => $payment->description,
                'status' => $payment->status,
                'order_id' => $payment->order_id,
                'checkout_link' => $payment->checkout_link
            ]);

        return view('livewire.open-pay.payment-table-component', [
            'payments' => $pagos,
        ]);
    }
}
