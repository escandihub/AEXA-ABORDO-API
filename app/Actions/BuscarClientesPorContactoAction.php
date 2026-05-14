<?php

namespace App\Actions;

use App\Models\Openpay\Customer;
use App\Models\Openpay\Client;
use Illuminate\Support\Collection;

class BuscarClientesPorContactoAction
{
    /**
     * Retorna colección de clientes vinculados al contacto
     * encontrado por email o teléfono.
     *
     * @return Collection<int, array{id: int, name: string, lastname: string|null, full_name: string}>
     */
    public function execute(?string $email, ?string $phone): Collection
    {
        if (! $email && ! $phone) {
            return collect();
        }

        return Customer::byEmailOrPhone($email, $phone)
            ->with(['clients' => fn($q) => $q->select('id', 'customer_id', 'name', 'lastname')])
            ->get()
            ->flatMap(function (Customer $c) {
                return $c->clients->map(function (Client $cl) use ($c) {
                    return [
                        'id'        => $cl->id,
                        'name'      => $cl->name,
                        'lastname'  => $cl->lastname,
                        'full_name' => $cl->full_name,
                        'email'     => $c->email,
                    ];
                });
            })
            ->unique('id')
            ->values();
    }
}
