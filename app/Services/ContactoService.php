<?php

namespace App\Services;

use App\Models\Openpay\Customer;
use App\Models\Openpay\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactoService
{
    /**
     * Obtiene o crea el contacto y le asocia un cliente con el nombre dado.
     */
    public function resolverCliente(
        string $email,
        string $phone,  
        string $name,
        string $lastname
    ): Client {
        return DB::transaction(function () use ($email, $phone, $name, $lastname) {
            // 1. Buscar contacto existente (por email OR phone)
            $contacto = Customer::byEmailOrPhone($email, $phone)->first()
                ?? Customer::create(['email' => $email, 'phone_number' => $phone]);

            // 2. Buscar si ya existe ese nombre bajo este contacto
            return $contacto->clients()
                ->firstOrCreate(
                    ['name' => $name, 'lastname' => $lastname],
                );
        });
    }
}