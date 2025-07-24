<?php

namespace App\Services;

use App\Models\Openpay\Transaction;
use App\Models\Openpay\CardPayment;
use App\Models\Openpay\StorePayment;
use App\Models\Openpay\BankTransfer;
use Illuminate\Support\Facades\DB;

/**
 * Service to handle transaction-related operations
 * This service is responsible for creating transactions and their associated payment methods
 * It only used for openpay transactions
 */

class TransactionService
{
    /**
     * Handle charge created webhook init
     * a transaction clould be card, bank transfer and store payment
     */
    public function handleChargeCreated(array $payload): Transaction
    {
        $transactionData = $payload['transaction'];
        return DB::transaction(function () use ($payload, $transactionData) {
            // Crear la transacción principal
            $transaction = Transaction::create([
                'transaction_id' => $transactionData['id'],
                'customer_id' => $transactionData['customer_id'],
                'order_id' => $transactionData['order_id'],
                'method' => $transactionData['method'],
                'status' => $transactionData['status'],
                'amount' => $transactionData['amount'],
                'currency' => $transactionData['currency'] ?? 'MXN',
                'description' => $transactionData['description'] ?? null,
                'metadata' => $transactionData['metadata'] ?? null,
                'created_at_openpay' => $transactionData['creation_date'],
            ]);

            // Crear el método de pago específico
            $this->createPaymentMethod($transaction, $transactionData['payment_method']);

            return $transaction;
        });
    }

     /**
     * Crear el método de pago específico
     */
    private function createPaymentMethod(Transaction $transaction, array $paymentMethod): void
    {
        match ($paymentMethod['type']) {
            'redirect' => $this->createCardPayment($transaction, $paymentMethod),
            'bank_transfer' => $this->createBankTransfer($transaction, $paymentMethod),
            'store' => $this->createStorePayment($transaction, $paymentMethod),
            default => throw new \InvalidArgumentException("Tipo de pago no soportado: {$paymentMethod['type']}"),
        };
    }

    
    /**
     * Crear pago con tarjeta
     */
    private function createCardPayment(Transaction $transaction, array $paymentMethod): void
    {
        CardPayment::create([
            'transaction_id' => $transaction->id,
            'type' => $paymentMethod['type'],
            'url' => $paymentMethod['url'],
            'brand' => $paymentMethod['brand'] ?? null,
            'card_number' => $paymentMethod['card_number'] ?? null,
            'holder_name' => $paymentMethod['holder_name'] ?? null,
            'expiration_month' => $paymentMethod['expiration_month'] ?? null,
            'expiration_year' => $paymentMethod['expiration_year'] ?? null,
            'authorization' => $paymentMethod['authorization'] ?? null,
        ]);
    }

    /**
     * Crear transferencia bancaria
     */
    private function createBankTransfer(Transaction $transaction, array $paymentMethod): void
    {
        BankTransfer::create([
            'transaction_id' => $transaction->id,
            'type' => $paymentMethod['type'],
            'bank' => $paymentMethod['bank'],
            'clabe' => $paymentMethod['clabe'],
            'agreement' => $paymentMethod['agreement'],
            'name' => $paymentMethod['name'],
            'url_spei' => $paymentMethod['url_spei'],
            'reference' => $paymentMethod['reference'] ?? null,
            'expires_at' => $paymentMethod['expires_at'] ?? null,
        ]);
    }

    /**
     * Crear pago en tienda
     */
    private function createStorePayment(Transaction $transaction, array $paymentMethod): void
    {
        StorePayment::create([
            'transaction_id' => $transaction->id,
            'type' => $paymentMethod['type'],
            'reference' => $paymentMethod['reference'],
            'barcode_url' => $paymentMethod['barcode_url'],
            'url_store' => $paymentMethod['url_store'],
            'store_name' => $paymentMethod['store_name'] ?? null,
            'expires_at' => $paymentMethod['expires_at'] ?? null,
        ]);
    }
}
