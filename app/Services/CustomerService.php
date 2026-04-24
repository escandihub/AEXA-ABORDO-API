<?php

namespace App\Services;

use App\Models\Openpay\Customer;
use App\Livewire\OpenPay\service\PagoData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class CustomerService
{
    /**
     * Create a new customer in Openpay and save to the database.
     *
     * @param array $data
     * @return Customer|null
     */
    public function createCustomer(array $data): ?Customer
    {
        try {
            // Create customer in Openpay
            $customer = new Customer();
            $customer->name = $data['name'];
            $customer->last_name = $data['lastname'];
            $customer->phone_number = $data['phone'];
            $customer->email = $data['email'];
            $customer->external_id = Hash::make($data['email']); // Use email as external ID

            // Save customer to the database
            if ($customer->save()) {
                return $customer;
            }
        } catch (Throwable $e) {
            Log::error('Error al crear un cliente: ' . $e->getMessage());
        }

        return null;
    }

    public function getOrCreateCustomer(PagoData $data): ?Customer
    {
        try {
            return Customer::firstOrCreate(
                ['email' => $data->email],
                [
                    'name' => $data->name,
                    'last_name' => $data->lastname,
                    'phone_number' => $data->phone,
                    'external_id'  => Hash::make($data->email),
                ]
            );
        } catch (\Throwable $th) {
            Log::error('CustomerService: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }
}