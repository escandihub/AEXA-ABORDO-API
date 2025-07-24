<?php

namespace App\Http\Controllers\openPay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

use App\Services\OpenPayWebhookService;

class OpenPayWebhookController extends Controller
{
    protected OpenPayWebhookService $webhookService;

    public function __construct(OpenPayWebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    /**
     * Handle the incoming webhook request from OpenPay.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request)
    {

        try {

            // Get the webhook payload
            $payload = $request->all();
            \Log::info('OpenPay Webhook received', ['payload' => $payload]);
            // Validate required fields
            if (!isset($payload['type']) || !isset($payload['transaction'])) {
                Log::error('OpenPay Webhook missing required fields', ['payload' => $payload]);
                return response('Bad Request', HttpResponse::HTTP_BAD_REQUEST);
            }

            // Process the webhook
            $result = $this->webhookService->processWebhook($payload);

            if ($result) {
                Log::info('OpenPay Webhook processed successfully', [
                    'type' => $payload['type'],
                    'transaction_id' => $payload['transaction']['id'] ?? null
                ]);

                return response('OK', HttpResponse::HTTP_OK);
            } else {
                Log::error('OpenPay Webhook processing failed', ['payload' => $payload]);
                return response()->json(['status' => 'success'], 200); // only json response
            }
        } catch (\Exception $e) {
            Log::error('OpenPay Webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            return response('Internal Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Process the webhook data

        // Log or handle the data as needed
        // For example, you might want to save it to the database or trigger some business logic

        return response()->json(['status' => 'success'], 200);
    }
}
