<?php

namespace App\Livewire\OpenPay;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\CustomerService;
use App\Services\PaymentService;
use App\Livewire\OpenPay\service\Cliente;
use App\Livewire\OpenPay\service\ComercioService;

class PaymentLinkGenerator extends Component
{
     public $monto = '';
     public $name = '';
     public $lastname = '';
     public $email = '';
     public $phone = '';
    public $descripcion = '';
    public $generatedLink = '';
    public $showLink = false;

    private $customerService;
    private $paymentService;
    public $comercios;
    

    // comercio selecionado
    public ?string $brand = 'AEXA';
    public ?array $selectOption = [];

    protected $rules = [
        'monto' => 'required|numeric|min:0.01',
        'name' => 'required|string',
        'lastname' => 'required|string',
        'email' => 'email',
        'phone' => 'required|numeric',
        'descripcion' => 'required|string|min:3|max:255',
        'selectOption' => 'required',
    ];

    protected $messages = [
        'monto.required' => 'El monto es obligatorio',
        'monto.numeric' => 'El monto debe ser un número válido',
        'monto.min' => 'El monto debe ser mayor a 0',
        'descripcion.required' => 'La descripción es obligatoria',
        'descripcion.min' => 'La descripción debe tener al menos 3 caracteres',
        'descripcion.max' => 'La descripción no puede exceder 255 caracteres',
    ];

    public function boot(SelectPaymentGategay $paymentService, ComercioService $comercios)
    {
        // $this->customerService = $customerService;
        // $this->paymentService = $paymentService;
        $this->paymentService = $paymentService;
        $this->comercios = $comercios->GetComercio();
        // dd($this->comercios);
    }

     public function redirectToPaymenList()
    {
        return $this->redirectRoute('pay.list');
    }
      public function generateLink()
    {
        $this->validate();

        try {
            $cliente = new Cliente(
                $this->name,
                $this->lastname,
                $this->phone,
                $this->descripcion,
                $this->email,
                $this->monto);

            $payment = $this->paymentService->GeneratePayFromBrand($this->selectOption, $cliente);
            $this->showLink = true;
            $this->generatedLink = $payment['link'];
            
            session()->flash('success', '¡Link de pago de Openpay generado exitosamente!');

            session()->put('openpay_checkout', [
                    'id' => $payment['id'],
                    'order_id' => $payment['order_id'],
                    'amount' => $payment['amount'],
                    'status' => $payment['status'],
                   'expiration_date' => $payment['expiration_date']
                ]);
            $this->dispatch('scroll-to-link');
         } catch (\Exception $e) {
            $this->addError('general', 'Error al generar el link de pago: ' . $e->getMessage());
            
            // Fallback: generar un link de prueba si falla Openpay
            
            
            // session()->flash('warning', 'Se generó un link de prueba. Configura tus credenciales de Openpay.');
        }
    }

    public function copyToClipboard()
    {
        $this->dispatch('copy-to-clipboard', link: $this->generatedLink);
    }

    public function clear()
    {
        $this->reset(['name','lastname','email','phone','monto', 'descripcion', 'generatedLink', 'showLink']);
        $this->resetErrorBag();
        $this->dispatch('set-all');
    }
    public function render()
    {
        return view('livewire.open-pay.payment-link-generator');
    }
}
