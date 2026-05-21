<?php

namespace App\Livewire\OpenPay;
use App\Services\CustomerService;
use App\Services\PaymentService;

use App\Livewire\OpenPay\service\Brands;
use App\Livewire\OpenPay\service\Cliente;
use Illuminate\Support\Facades\Http;

use App\Livewire\OpenPay\service\Config\AexaConfig;
use App\Livewire\OpenPay\service\Config\TitaniumConfig;
use App\Livewire\OpenPay\service\Config\ExpresoConfig;
use App\Livewire\OpenPay\service\Config\GTESConfig;
use App\Livewire\OpenPay\service\PagoData;
use Illuminate\Support\Facades\DB;

class SelectPaymentGategay
{
    private  $brand;

    private $customerService;
    private $paymentService;

    public $generatedLink = '';

    
    public function __construct(CustomerService $customerService, PaymentService $paymentService) {
        $this->customerService = $customerService;
        $this->paymentService = $paymentService;
        
    }


    private function selectBrand()
    {
        return match ($this->brand) {
            'AEXA' => AexaConfig::fromConfig(),
            'TITANIUM' => TitaniumConfig::fromConfig(),
            'Expreso' => ExpresoConfig::fromConfig(),
            'GTES' => GTESConfig::fromConfig(),
            default => throw new \InvalidArgumentException("Marca no soportada: {$this->brand}"),
        };
    }

    public function GeneratePayFromBrand($brand, PagoData $cliente_pay)
    {
          try {
            DB::beginTransaction();
            
            $this->brand = $brand["name"];
            $brandConfig = $this->selectBrand();
            // Configuración de Openpay
            $merchantId = $brandConfig->merchantId;
            $privateKey = $brandConfig->privateKey;
            $isSandbox = $brandConfig->sandbox;
            
            // URL base según el ambiente
            $baseUrl = $isSandbox ? 'https://sandbox-api.openpay.mx' : 'https://api.openpay.mx';
            
            // Datos para crear el checkout
            $checkoutData = [
                'amount' => $cliente_pay->monto,
                'currency' => 'MXN',
                'description' => $cliente_pay->descripcion,
                'order_id' => 'ORD-' . uniqid() . '-' . time(),
                'send_email' => false,
                'customer' => [
                    'name' => $cliente_pay->name,
                    'last_name' => $cliente_pay->lastname,
                    'phone_number' => $cliente_pay->phone,
                    'email' => $cliente_pay->email,
                ],
                'redirect_url' => env('OPENPAY_REDIRECT_URL', ''),
                'expiration_date' => now()->addHours(2)->format('Y-m-d H:i'),
            ];
            // verificar crear client para asociar a customer (email, phone)
            $cliente = $this->customerService->getOrCreateCustomer($cliente_pay);

            // Llamada a la API de Openpay
            $response = Http::withBasicAuth($privateKey, '')
                ->post("{$baseUrl}/v1/{$merchantId}/checkouts", $checkoutData);
            \Log::info('Respuesta de Openpay: ' . $response->body());
            if ($response->successful()) {
                $data = $response->json();
                $this->generatedLink = $data['checkout_link'];
                // $this->showLink = true;

                $this->paymentService->createPayment([
                    'openpay_id' => $data['id'],
                    'customer_id' => $cliente->id,
                    'amount' => $cliente_pay->monto,
                    'description' => $cliente_pay->descripcion,
                    'order_id' => $data['order_id'],
                    'brand' => $this->brand,
                    'currency' => 'MXN',
                    'iva' => 0.00, // Asumiendo que no se aplica IVA
                    'status' => $data['status'],
                    'checkout_link' => $data['checkout_link'],
                    'creation_date' => now(),
                    'expiration_date' => now()->addHour(3)
                ]);

                // Guardar información adicional del checkout
                // session()->put('openpay_checkout', [
                //     'id' => $data['id'],
                //     'order_id' => $data['order_id'],
                //     'amount' => $data['amount'],
                //     'status' => $data['status'],
                //     'expiration_date' => $data['expiration_date']
                // ]);
                
                
                // $this->generatedLink = 'https://sandbox-api.openpay.mx/ck/' . uniqid();

                // session()->flash('success', '¡Link de pago de Openpay generado exitosamente!');
                DB::commit();
                return [
                'id' => $data['id'],
                'link' => $data['checkout_link'],
                'order_id' => $data['order_id'],
                'amount' => $data['amount'],
                'status' => $data['status'],
                'expiration_date' => $data['expiration_date']
            ];
            } else {
                $error = $response->json();
                DB::rollBack();
                throw new \Exception($error['description'] ?? 'Error desconocido de Openpay');
            }
            
        } catch (\Exception $e) {
            // $this->addError('general', 'Error al generar el link de pago: ' . $e->getMessage());
            
            // Fallback: generar un link de prueba si falla Openpay
            $this->generatedLink = 'https://sandbox-api.openpay.mx/ck/' . uniqid();
            // $this->showLink = true;
             DB::rollBack();
            session()->flash('warning', 'Se generó un link de prueba. Configura tus credenciales de Openpay.');
        }
    }
}