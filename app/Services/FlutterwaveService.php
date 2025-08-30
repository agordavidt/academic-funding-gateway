<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    private $publicKey;
    private $secretKey;
    private $baseUrl;

    public function __construct()
    {
        $this->publicKey = config('flutterwave.publicKey');
        $this->secretKey = config('flutterwave.secretKey');
        $this->baseUrl = config('flutterwave.paymentUrl');
    }

    public function initiatePayment(Payment $payment, $redirectUrl)
    {
        $data = [
            'tx_ref' => $payment->transaction_id,
            'amount' => $payment->amount,
            'currency' => 'NGN',
            'redirect_url' => $redirectUrl,
            'customer' => [
                'email' => $payment->user->email,
                'phonenumber' => $payment->user->phone_number,
                'name' => $payment->user->full_name,
            ],
            'customizations' => [
                'title' => 'Academic Funding Gateway',
                'description' => 'Grant Registration Fee',
                'logo' => config('app.url') . '/images/logo.png',
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, $data);

            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['status'] === 'success') {
                    return $responseData['data']['link'];
                }
            }

            Log::error('Flutterwave payment initiation failed', [
                'response' => $response->json(),
                'payment_id' => $payment->id,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Flutterwave payment error: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyPayment($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . "/transactions/{$transactionId}/verify");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Flutterwave verification error: ' . $e->getMessage());
            return null;
        }
    }
}