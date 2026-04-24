<?php

namespace App\Livewire\OpenPay\service;

readonly class PagoData 
{
    public function __construct(
        public string $name,
        public string $lastname,
        public string $phone,
        public string $email,
        public string $descripcion,
        public int    $monto,
    ) {}
}
