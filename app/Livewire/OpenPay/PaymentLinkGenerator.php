<?php

namespace App\Livewire\OpenPay;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class PaymentLinkGenerator extends Component
{
     public $monto = '';
    public $descripcion = '';
    public $generatedLink = '';
    public $showLink = false;

    protected $rules = [
        'monto' => 'required|numeric|min:0.01',
        'descripcion' => 'required|string|min:3|max:255',
    ];

    protected $messages = [
        'monto.required' => 'El monto es obligatorio',
        'monto.numeric' => 'El monto debe ser un número válido',
        'monto.min' => 'El monto debe ser mayor a 0',
        'descripcion.required' => 'La descripción es obligatoria',
        'descripcion.min' => 'La descripción debe tener al menos 3 caracteres',
        'descripcion.max' => 'La descripción no puede exceder 255 caracteres',
    ];

      public function generateLink()
    {
        $this->validate();

        try {
            // Configuración de Openpay
            $merchantId = config('openpay.merchant_id');
            $privateKey = config('openpay.private_key');
            $isSandbox = config('openpay.sandbox', true);
            
            // URL base según el ambiente
            $baseUrl = $isSandbox ? 'https://sandbox-api.openpay.mx' : 'https://api.openpay.mx';
            
            // Datos para crear el checkout
            $checkoutData = [
                'amount' => $this->monto,
                'currency' => 'MXN',
                'description' => $this->descripcion,
                'order_id' => 'ORD-' . uniqid() . '-' . time(),
                'send_email' => false,
                'customer' => [
                    'name' => 'Cliente',
                    'last_name' => 'Openpay',
                    'phone_number' => '5555555555',
                    'email' => 'cliente@ejemplo.com'
                ],
                'redirect_url' => url()->current(),
                'expiration_date' => now()->addDays(7)->format('Y-m-d H:i'),
            ];

            // Llamada a la API de Openpay
            $response = Http::withBasicAuth($privateKey, '')
                ->post("{$baseUrl}/v1/{$merchantId}/checkouts", $checkoutData);

            if ($response->successful()) {
                $data = $response->json();
                $this->generatedLink = $data['checkout_link'];
                $this->showLink = true;
                
                // Guardar información adicional del checkout
                session()->put('openpay_checkout', [
                    'id' => $data['id'],
                    'order_id' => $data['order_id'],
                    'amount' => $data['amount'],
                    'status' => $data['status'],
                    'expiration_date' => $data['expiration_date']
                ]);
                
                session()->flash('success', '¡Link de pago de Openpay generado exitosamente!');
            } else {
                $error = $response->json();
                throw new \Exception($error['description'] ?? 'Error desconocido de Openpay');
            }
            
        } catch (\Exception $e) {
            $this->addError('general', 'Error al generar el link de pago: ' . $e->getMessage());
            
            // Fallback: generar un link de prueba si falla Openpay
            $this->generatedLink = 'https://sandbox-api.openpay.mx/ck/' . uniqid();
            $this->showLink = true;
            
            session()->flash('warning', 'Se generó un link de prueba. Configura tus credenciales de Openpay.');
        }
    }

    public function copyToClipboard()
    {
        $this->dispatch('copy-to-clipboard', link: $this->generatedLink);
    }

    public function resett()
    {
        $this->reset(['monto', 'descripcion', 'generatedLink', 'showLink']);
        $this->resetErrorBag();
    }
    public function render()
    {
        return view('livewire.open-pay.payment-link-generator');
    }
}
