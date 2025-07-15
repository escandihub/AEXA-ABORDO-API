<?php

namespace App\Services;

use App\Models\Openpay\payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;
use Throwable;

class PaymentService
{
     /**
     * Create a new customer in Openpay and save to the database.
     *
     * @param array $data
     * @return payment|null
     */
    public function createPayment(array $data)
    {
        try {
            // Create payment in Openpay
            $payment = new payment();
            $payment->openpay_id = $data['openpay_id'];
            $payment->customer_id = $data['customer_id'];
            $payment->amount = $data['amount'];
            $payment->description = $data['description'];
            $payment->order_id = $data['order_id'];
            $payment->currency = $data['currency'];
            $payment->iva = $data['iva'];
            $payment->status = $data['status'];
            $payment->checkout_link = $data['checkout_link'];
            $payment->creation_date = now();
            $payment->expiration_date = now()->addDays(7);

            // Save payment to the database
            if ($payment->save()) {
                return $payment;
            }
        } catch (Throwable $e) {
            Log::error('Error al crear un pago: ' . $e->getMessage());
        }

        return null;

    }
}