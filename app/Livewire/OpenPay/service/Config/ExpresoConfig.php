<?php

namespace  App\Livewire\OpenPay\service\Config;

use App\Livewire\OpenPay\service\Contracts\PaymentConfigInterface;
/**
 * Clase para la configuracion de 
 * AUTOBUSES EXPRESOS DE MEXICO
 */
final class ExpresoConfig implements PaymentConfigInterface
{
    private const API_URL = 'https://api.payment-gateway.com';
    
    public function __construct(
        public readonly string $merchantId,
        public readonly string $privateKey,
        public readonly bool $sandbox = true,
    ) {}

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }
    public function getApiUrl(): string
    {
        return $this->sandbox
            ? 'https://sandbox-api.aexa.com'
            : 'https://api.aexa.com';
    }

    public static function fromConfig(): self
    {
        return new self(
            merchantId: config('openpay.expreso_mx.merchant_id'),
            privateKey: config('openpay.expreso_mx.private_key'),
            sandbox: config('openpay.sandbox', true),
        );
    }
}
