<?php

namespace  App\Livewire\OpenPay\Dtos;
use JsonSerializable;

readonly class SuccessCardPay implements JsonSerializable
{

    public function __construct(
        public string $nombre,
        public string $apellido,
        public float $monto
    )
    {
        // throw new \Exception('Not implemented');
    }
    public function fullName()
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
     public function jsonSerialize(): array
    {
        return [
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nombre_completo' => $this->fullName(),
            'monto' => $this->monto,
        ];
    }
}
