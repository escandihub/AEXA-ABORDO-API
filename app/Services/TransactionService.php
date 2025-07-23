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

     /**
     * Actualizar transacción cuando el pago es exitoso
     */
    public function updateTransactionSuccess(array $webhookData): Transaction
    {
        return DB::transaction(function () use ($webhookData) {
            $transaction = Transaction::where('transaction_id', $webhookData['transaction']['id'])->firstOrFail();

            $transaction->update([
                'status' => $webhookData['transaction']['status'],
                'metadata' => $webhookData['transaction']['metadata'] ?? null,
            ]);

            $this->updatePaymentMethodSuccess($transaction, $webhookData);

            // Disparar eventos/notificaciones
            // ** FUTURAS IMPLEMENTACIONES **
            //$this->handleSuccessfulPayment($transaction);

            // Actualizar el método de pago si es necesario
            if (isset($webhookData['payment_method'])) {
                $this->createPaymentMethod($transaction, $webhookData['payment_method']);
            }

            return $transaction;
        });
    }

    /**
     * Actualizar método de pago específico cuando es exitoso
     */
    private function updatePaymentMethodSuccess(Transaction $transaction, array $webhookData): void
    {
        $paymentMethod = $webhookData['transaction']['method'] ?? [];
        
        match ($transaction->method) {
            'card' => $this->updateCardPaymentSuccess($transaction, $webhookData),
            'bank_transfer' => $this->updateBankTransferSuccess($transaction, $webhookData),
            'store' => $this->updateStorePaymentSuccess($transaction, $webhookData),
        };
    }
    /**
     * Actualizar pago con tarjeta exitoso
     */
    private function updateCardPaymentSuccess(Transaction $transaction,  array $webhookData): void
    {
        $cardPayment = $transaction->cardPayment;
        $cardData = $webhookData['transaction']['card'];
        if ($cardPayment) {
            $cardPayment->update([
                'type' => $cardData['type'] ?? $cardPayment->type,
                'brand' => $cardData['brand'] ?? $cardPayment->brand,
                'card_number' => $cardData['card_number'] ?? $cardPayment->card_number,
                'holder_name' => $cardData['holder_name'] ?? $cardPayment->holder_name,
                'authorization' => $webhookData['transaction']['authorization'] ?? $cardPayment->authorization,
                'expiration_month' => $cardData['expiration_month'] ?? $cardPayment->expiration_month,
                'expiration_year' => $cardData['expiration_year'] ?? $cardPayment->expiration_year,
            ]);
        }
    }

    /**
     * Actualizar transferencia bancaria exitosa
     */
    private function updateBankTransferSuccess(Transaction $transaction, array $webhookData): void
    {
        $bankTransfer = $transaction->bankTransfer;
        if ($bankTransfer) {
            $bankTransfer->update([
                // 'reference' => $paymentMethod['receiving_account_number'] ?? $webhookData['reference'] ?? $bankTransfer->reference,
                // Actualizar otros campos específicos de SPEI si vienen en el webhook
            ]);
        }
    }

    /**
     * Actualizar pago en tienda exitoso
     */
    private function updateStorePaymentSuccess(Transaction $transaction, array $webhookData): void
    {
        $storePayment = $transaction->storePayment;
        if ($storePayment) {
            $storePayment->update([
                // 'store_name' => $webhookData['payment_method']['store'] ?? $paymentMethod['store'] ?? $storePayment->store_name,
                // Agregar timestamp de cuando se pagó en tienda
            ]);
        }
    }

    private function logErrorTransaction(array $webhookData): void
    {
        // Aquí puedes implementar la lógica para registrar errores de transacción
        // Por ejemplo, guardar en una tabla de logs o enviar una notificación
        return "OK";
    }
}
