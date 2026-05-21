<?php

namespace App\Services;

use App\Models\Openpay\Customer;
use App\Models\Openpay\Client;
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
            $client = new Client();

            $client->name = $data['name'];
            $client->last_name = $data['lastname'];

            $customer->phone_number = $data['phone'];
            $customer->email = $data['email'];
            $customer->external_id = Hash::make($data['email']); // Use email as external ID

            // Save customer to the database
            if ($customer->save()) {
                $client->customer_id = $customer->id;
                $client->save();
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
            $customer = Customer::firstOrCreate(
                [
                    'phone_number' => $data->phone,
                    'email' => $data->email,
                ]
            );

            $customer->client()->updateOrCreate(
                [
                    'customer_id' => $customer->id,
                ],
                [
                    'name' => $data->name,
                    'lastname' => $data->lastname,
                ]
            );
            return $customer;
        } catch (\Throwable $th) {
            Log::error('CustomerService: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }
}
