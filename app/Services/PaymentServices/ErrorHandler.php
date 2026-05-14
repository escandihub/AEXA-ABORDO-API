<?php

namespace App\Services\PaymentServices;

use App\Models\Openpay\TransactionLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Openpay\Transaction;

use Throwable;

class ErrorHandler
{
    /**
     * Handle the error and log it.
     *
     * @param Throwable $exception
     * @return void
     */
    public function handle(array $webhookData)
    {

        return DB::transaction(function () use ($webhookData) {
            // Save the error details in the transaction logs
            \Log::info('OpenPay Webhook error', [
                'error' => $webhookData['transaction']['error_message'] ?? 'Unknown error',
                'trace' => $webhookData['transaction']['trace'] ?? null,
                'payload' => $webhookData
            ]);
            $this->saveLog($webhookData);
        });
        // Optionally, you can implement additional error handling logic here
        // For example, you could notify an admin or send an email alert
    }

    public function saveLog(array $webhookData): void
    {
        $transaction = Transaction::where('transaction_id', $webhookData['transaction']['id'])->firstOrFail();
   
        $log = TransactionLog::create([
            'transaction_id' => $transaction->id,
            'status' => $webhookData['transaction']['status'] ?? 'failed',
            'error_message' => $webhookData['transaction']['error_message'] ?? null,
            'gateway_response_code' => $webhookData['transaction']['error_code'] ?? null,
            'attempted_amount' => $webhookData['transaction']['amount']?? 0.00
        ]);

         if($transaction->status === 'completed'){
            $transaction->update([
                'status' => $webhookData['transaction']['status'],
                'metadata' => $webhookData['transaction']['metadata'] ?? 'failed',
            ]);
        }
    }


}
