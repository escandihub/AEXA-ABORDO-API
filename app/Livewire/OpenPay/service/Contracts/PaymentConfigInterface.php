<?php

namespace  App\Livewire\OpenPay\service\Contracts;

interface PaymentConfigInterface
{
    public function getMerchantId(): string;
    public function getPrivateKey(): string;
    public function isSandbox(): bool;
    public function getApiUrl(): string;
}