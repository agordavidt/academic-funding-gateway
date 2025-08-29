<?php

namespace App\Services;

use Flutterwave\v3\Flutterwave;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected $flutterwave;
    
    public function __construct()
    {
        $this->flutterwave = new Flutterwave(config('funding.flutterwave.public_key'));
    }
    
    public function initializePayment(array $data)
    {
        try {
            $payload = [
                'tx_ref' => $data['tx_ref'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'payment_options' => 'card,banktransfer,ussd',
                'customer' => $data['customer'],
                'customizations' => $data['customizations'],
                'redirect_url' => $data['redirect_url']
            ];
            
            $response = $this->flutterwave->payment->initiate($payload);
            
            if ($response['status'] !== 'success') {
                throw new \Exception('Payment initialization failed: ' . $response['message']);
            }
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Flutterwave payment initialization failed', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    
    public function verifyPayment(string $transactionId)
    {
        try {
            $response = $this->flutterwave->transaction->verify($transactionId);
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Flutterwave payment verification failed', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}