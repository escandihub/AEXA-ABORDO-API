<?php

namespace App\Livewire\OpenPay\service;

class Cliente
{
    public string $name;
    public string $lastname;
    public string $phone;
    public string $descripcion;
    public string $email;
    public int $monto;

    public function __construct($name, $lastname, $phone, $descripcion, $email, $monto)
    {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->phone = $phone;
        $this->descripcion = $descripcion;
        $this->email = $email;
        $this->monto = $monto;
    }
}
