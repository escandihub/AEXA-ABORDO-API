<?php

namespace App\Livewire\OpenPay;

use Livewire\Component;
use App\Models\Openpay\payment;
use App\Models\Openpay\Transaction;
use App\Services\PaymentServices\TransactionStatusService;


class PaymentTableComponent extends Component
{
    public $payments = [];
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
        \Log::info("Consultando pago con ID: {$paymentId}");
        // $this->selectedPayment = collect($this->filteredPayments)->firstWhere('id', $paymentId);
        $this->selectedPayment = Transaction::where('order_id', $paymentId)->first();;
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
        ->joinCustomer();
        // ->where('payments.creation_date', '=', now());

    }


    public function render()
    {
        $this->payments = $this->query()->get()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'cliente' => $payment->cliente,
                'amount' => $payment->amount,
                'fecha' => $payment->created_at->format('Y-m-d'),
                'description' => $payment->description,
                'status' => $payment->status,
                'order_id' => $payment->order_id,
            ];
        })->toArray();

        return view('livewire.open-pay.payment-table-component', [
            'payments' => $this->query()->get(),
        ]);
    }
}
