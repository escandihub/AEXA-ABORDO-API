<?php

namespace App\Livewire\OpenPay;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\CustomerService;
use App\Services\PaymentService;
use App\Livewire\OpenPay\service\Cliente;
use App\Livewire\OpenPay\service\ComercioService;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePayLink;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;

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

    private $isProcessing = false;
    private $lastGenerationTime = 0;
    private $requestHash = null;

    protected $rules = [
        'monto' => 'required|numeric|min:0.01',
        'name' => 'required|string',
        'lastname' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|numeric',
        'descripcion' => 'required|string|min:10|max:100|regex:/^[a-zA-Z]{3}-[a-zA-Z]{3}\. \d{2}-\d{2}-\d{4}\. ASIENTO \d{1,2}(?:\.\d{1,2})*\. \d{2}\.\d{2} HRS.$/',
        'selectOption' => 'required',
    ];

    protected $messages = [
        'monto.required' => 'El monto es obligatorio',
        'monto.numeric' => 'El monto debe ser un número válido',
        'monto.min' => 'El monto debe ser mayor a 0',
        'descripcion.required' => 'La descripción es obligatoria',
        'descripcion.min' => 'La descripción debe tener al menos 3 caracteres',
        'descripcion.max' => 'La descripción no puede exceder 255 caracteres',
        'descripcion.regex' => 'La descripción debe seguir el formato: origen-destino. DD-MM-YYYY. ASIENTO N. HH.MM HRS.',
        'selectOption.required' => 'Debes seleccionar una marca para continuar',
        'name.required' => 'Nombre es obligatorio',
        'lastname.required' => 'apellido es obligatorio',
        'phone.required' => 'telefono es obligatorio',
        'email.required' => 'correo es obligatorio',
        'email.email' => 'Formato de correo inválido',
    ];

    public function boot(SelectPaymentGategay $paymentService, ComercioService $comercios)
    {
        // , ''
        if(Gate::any(['isGerente', 'isPayment', 'isAdmin'])){
            // allow access
        }else{
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
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
        if ($this->isProcessing) {
            $this->addError('general', 'Por favor espera a que se complete la solicitud anterior.');
            return;
        }
        $this->dispatch('request-description');
       
    }

    private function procesar(){
         $this->validate();

         $this->requestHash = hash('sha256', json_encode([
            $this->name,
            $this->lastname,
            $this->email,
            $this->phone,
            $this->monto,
            $this->brand
        ]));

        $cacheKey = 'payment_request_' . $this->requestHash;
        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            $this->generatedLink = $cached['link'];
            $this->showLink = true;
            
            session()->put('openpay_checkout', $cached['checkout']);
            $this->dispatch('scroll-to-link');
            
            return;
        }
         // 5. Marcar como procesando
        $this->isProcessing = true;
        $this->dispatch('submission-start');
        
        
        try {
            $cliente = new Cliente(
                $this->name,
                $this->lastname,
                $this->phone,
                $this->descripcion,
                $this->email,
                $this->monto);

            $payment = $this->paymentService->GeneratePayFromBrand($this->selectOption, $cliente);
            // 6. Guardar en caché para evitar duplicados en 5 minutos
            $checkoutData = [
                'id' => $payment['id'],
                'order_id' => $payment['order_id'],
                'amount' => $payment['amount'],
                'status' => $payment['status'],
                'expiration_date' => $payment['expiration_date']
            ];

            Cache::put($cacheKey, [
                'link' => $payment['link'],
                'checkout' => $checkoutData
            ], now()->addMinutes(5));

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
        } finally {
            $this->isProcessing = false;
            $this->dispatch('submission-complete');
            // $this->clear();
            // $this->dispatch('clear-form-child');
        }
    }

    #[On('description-created')] 
    public function createDescription($description = null){
        \Log::info('description: ' .  $description);
        $this->descripcion = $description;
        $this->procesar();
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
    public function setError($field, $message)
{
    $this->addError($field, $message);
}
    public function render()
    {
        return view('livewire.open-pay.payment-link-generator');
    }
}
