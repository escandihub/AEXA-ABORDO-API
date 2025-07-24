<?php

namespace App\Services\PaymentServices\Interfaces;
use App\Models\Openpay\Transaction;

interface Updated
{
    public function HandleTransactionSuccess(array $data);
    private function updatePaymentMethodSuccess( Transaction $transaction, array $webhookData);
    private function updateCardPaymentSuccess(Transaction $transaction, array $webhookData);
    private function updateBankTransferSuccess(Transaction $transaction, array $webhookData);
    private function updateStorePaymentSuccess(Transaction $transaction, array $webhookData);
}