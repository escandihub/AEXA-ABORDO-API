<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;
use App\Models\Transaction;
use App\Models\PaymentCard;
use App\Models\Openpay\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OpenPayWebhookService
{
    /**
     * Process incoming OpenPay webhook
     */
    public function processWebhook(array $payload): bool
    {
        try {
            DB::beginTransaction();

            $webhookType = $payload['type'];
            $transactionData = $payload['transaction'];

            // Process based on webhook type
            switch ($webhookType) {
                case 'charge.succeeded':
                    $this->handleChargeSucceeded($payload);
                    break;
                
                case 'charge.failed':
                    $this->handleChargeFailed($payload);
                    break;
                
                case 'charge.cancelled':
                    $this->handleChargeCancelled($payload);
                    break;
                
                case 'charge.created':
                    $this->handleChargeCreated($payload);
                    break;
                
                case 'payout.created':
                    $this->handlePayoutCreated($payload);
                    break;
                
                case 'payout.succeeded':
                    $this->handlePayoutSucceeded($payload);
                    break;
                
                case 'payout.failed':
                    $this->handlePayoutFailed($payload);
                    break;
                
                case 'chargeback.created':
                    $this->handleChargebackCreated($payload);
                    break;
                case 'verification':
                    $this->endPointVerification($payload);
                    break;
                
                default:
                    Log::warning('Unknown OpenPay webhook type', ['type' => $webhookType]);
                    break;
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing OpenPay webhook', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            return false;
        }
    }

    /**
     * Handle charge succeeded webhook
     */
    protected function handleChargeSucceeded(array $payload): void
    {
        $transactionData = $payload['transaction'];
        \Log::info('OpenPay Webhook charge succeeded', ['transaction' => $transactionData]);
        // Update or create transaction record
        $transaction = payment::updateOrCreate(
            ['order_id' => $transactionData['order_id']],
            [
                'amount' => $transactionData['amount'],
                'authorization' => $transactionData['authorization'] ?? null,
                'method' => $transactionData['method'],
                'operation_type' => $transactionData['operation_type'],
                'transaction_type' => $transactionData['transaction_type'],
                'status' => $transactionData['status'],
                'description' => $transactionData['description'] ?? null,
                'order_id' => $transactionData['order_id'] ?? null,
                'error_message' => $transactionData['error_message'] ?? null,
                'processed_at' => Carbon::parse($payload['event_date']),
                'openpay_created_at' => Carbon::parse($transactionData['creation_date']),
                // 'webhook_type' => $payload['type']
            ]
        );

        // Handle card information if present
        // if (isset($transactionData['card'])) {
        //     $this->processCardData($transactionData['card'], $transaction);
        // }

        // Trigger any business logic for successful charge
        $this->onChargeSucceeded($transaction, $payload);
    }

    /**
     * Handle charge failed webhook
     */
    protected function handleChargeFailed(array $payload): void
    {
        $transactionData = $payload['transaction'];
        
        $transaction = payment::updateOrCreate(
            ['openpay_id' => $transactionData['id']],
            [
                'amount' => $transactionData['amount'],
                'method' => $transactionData['method'],
                'operation_type' => $transactionData['operation_type'],
                'transaction_type' => $transactionData['transaction_type'],
                'status' => $transactionData['status'],
                'description' => $transactionData['description'] ?? null,
                'order_id' => $transactionData['order_id'] ?? null,
                'error_message' => $transactionData['error_message'] ?? null,
                'processed_at' => Carbon::parse($payload['event_date']),
                'openpay_created_at' => Carbon::parse($transactionData['creation_date']),
                // 'webhook_type' => $payload['type']
            ]
        );

        $this->onChargeFailed($transaction, $payload);
    }

    /**
     * Handle charge cancelled webhook
     */
    protected function handleChargeCancelled(array $payload): void
    {
        $transactionData = $payload['transaction'];
        
        $transaction = payment::updateOrCreate(
            ['openpay_id' => $transactionData['id']],
            [
                'status' => 'cancelled',
                'processed_at' => Carbon::parse($payload['event_date']),
                // 'webhook_type' => $payload['type']
            ]
        );

        $this->onChargeCancelled($transaction, $payload);
    }

    /**
     * Handle charge created webhook
     */
    protected function handleChargeCreated(array $payload): void
    {
        $transactionData = $payload['transaction'];
        
        payment::updateOrCreate(
            ['openpay_id' => $transactionData['id']],
            [
                'amount' => $transactionData['amount'],
                'method' => $transactionData['method'],
                'operation_type' => $transactionData['operation_type'],
                'transaction_type' => $transactionData['transaction_type'],
                'status' => $transactionData['status'],
                'description' => $transactionData['description'] ?? null,
                'order_id' => $transactionData['order_id'] ?? null,
                'processed_at' => Carbon::parse($payload['event_date']),
                'openpay_created_at' => Carbon::parse($transactionData['creation_date']),
                // 'webhook_type' => $payload['type']
            ]
        );
    }

    /**
     * Handle payout webhooks
     */
    protected function handlePayoutCreated(array $payload): void
    {
        // Implementation for payout created
        Log::info('Payout created webhook received', $payload);
    }

    protected function handlePayoutSucceeded(array $payload): void
    {
        // Implementation for payout succeeded
        Log::info('Payout succeeded webhook received', $payload);
    }

    protected function handlePayoutFailed(array $payload): void
    {
        // Implementation for payout failed
        Log::info('Payout failed webhook received', $payload);
    }

    /**
     * Handle chargeback created webhook
     */
    protected function handleChargebackCreated(array $payload): void
    {
        // Implementation for chargeback
        Log::info('Chargeback created webhook received', $payload);
    }

    /**
     * Process card data from transaction
     */
    protected function processCardData(array $cardData, payment $transaction): void
    {
        if (!isset($cardData['card_number'])) {
            return;
        }

        payment::updateOrCreate(
            ['card_number' => $cardData['card_number']],
            [
                'transaction_id' => $transaction->id,
                'type' => $cardData['type'] ?? null,
                'brand' => $cardData['brand'] ?? null,
                'holder_name' => $cardData['holder_name'] ?? null,
                'expiration_month' => $cardData['expiration_month'] ?? null,
                'expiration_year' => $cardData['expiration_year'] ?? null,
                'allows_charges' => $cardData['allows_charges'] ?? false,
                'allows_payouts' => $cardData['allows_payouts'] ?? false,
                'bank_name' => $cardData['bank_name'] ?? null,
                'bank_code' => $cardData['bank_code'] ?? null,
                'address_line1' => $cardData['address']['line1'] ?? null,
                'address_line2' => $cardData['address']['line2'] ?? null,
                'address_line3' => $cardData['address']['line3'] ?? null,
                'address_state' => $cardData['address']['state'] ?? null,
                'address_city' => $cardData['address']['city'] ?? null,
                'address_postal_code' => $cardData['address']['postal_code'] ?? null,
                'address_country_code' => $cardData['address']['country_code'] ?? null,
                'openpay_created_at' => isset($cardData['creation_date']) ? Carbon::parse($cardData['creation_date']) : null,
            ]
        );
    }

    /**
     * Validate webhook signature (implement based on your OpenPay configuration)
     */
    public function validateWebhookSignature(Request $request): bool
    {
        // If you have webhook signature validation configured in OpenPay,
        // implement the validation here
        
        // For now, we'll return true - implement signature validation based on your setup
        return true;
        
        // Example implementation:
        // $signature = $request->header('X-OpenPay-Signature');
        // $payload = $request->getContent();
        // $secret = config('openpay.webhook_secret');
        // $expectedSignature = hash_hmac('sha256', $payload, $secret);
        // return hash_equals($signature, $expectedSignature);
    }

    /**
     * Business logic hooks - customize these methods for your application
     */
    protected function onChargeSucceeded(payment $transaction, array $payload): void
    {
        // Add your business logic here
        // For example: send confirmation email, update order status, etc.
        Log::info('Charge succeeded', ['transaction_id' => $transaction->id]);
    }

    protected function onChargeFailed(payment $transaction, array $payload): void
    {
        // Add your business logic here
        // For example: notify customer, update order status, etc.
        Log::info('Charge failed', ['transaction_id' => $transaction->id]);
    }

    protected function onChargeCancelled(payment $transaction, array $payload): void
    {
        // Add your business logic here
        Log::info('Charge cancelled', ['transaction_id' => $transaction->id]);
    }
    protected function endPointVerification(array $payload): void
    {
        // Handle verification endpoint logic
        Log::info('Verification endpoint hit', ['payload' => $payload]);
        
        // Example: Log the verification code or perform any necessary actions
        if (isset($payload['verification_code'])) {
            Log::info('Verification code received', ['code' => $payload['verification_code']]);
        }
    }
}