<?php

namespace App\Services\PaymentServices;

use App\Models\Openpay\TransactionLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Openpay\Transaction;
use App\Services\PaymentServices\Interfaces\Updated;

class UpdateTransaction implements Updated
{
    /**
     * Actualizar transacción cuando el pago es exitoso
     */
    public function HandleTransactionSuccess(array $webhookData): Transaction
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

}