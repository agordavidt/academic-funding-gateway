<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiKey;
    protected $senderId;
    protected $baseUrl;

    public function __construct()
    {
        // You can use any SMS service - Termii, Twilio, etc.
        // This example uses Termii SMS service
        $this->apiKey = config('services.sms.api_key', env('TERMII_API_KEY'));
        $this->senderId = config('services.sms.sender_id', env('TERMII_SENDER_ID', 'AFG'));
        $this->baseUrl = 'https://api.ng.termii.com/api/sms/send';
    }

    public function sendSms($phoneNumber, $message)
    {
        try {
            // Clean phone number - ensure it starts with 234
            $phoneNumber = $this->cleanPhoneNumber($phoneNumber);

            if (empty($this->apiKey)) {
                Log::warning('SMS API key not configured');
                return [
                    'success' => false,
                    'message' => 'SMS service not properly configured'
                ];
            }

            // For development/testing, you might want to skip actual sending
            if (app()->environment('local') && !config('services.sms.send_in_local', false)) {
                Log::info('SMS would be sent in production', [
                    'phone' => $phoneNumber,
                    'message' => $message
                ]);
                return [
                    'success' => true,
                    'message' => 'SMS logged (development mode)',
                    'message_id' => 'dev_' . time()
                ];
            }

            $response = Http::timeout(30)->post($this->baseUrl, [
                'api_key' => $this->apiKey,
                'to' => $phoneNumber,
                'from' => $this->senderId,
                'sms' => $message,
                'type' => 'plain',
                'channel' => 'generic'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SMS sent successfully', [
                    'phone' => $phoneNumber,
                    'message_id' => $data['message_id'] ?? null,
                    'balance' => $data['balance'] ?? null
                ]);

                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'message_id' => $data['message_id'] ?? null,
                    'balance' => $data['balance'] ?? null
                ];
            } else {
                $error = $response->json()['message'] ?? 'Failed to send SMS';
                Log::error('SMS sending failed', [
                    'phone' => $phoneNumber,
                    'error' => $error,
                    'status' => $response->status()
                ]);

                return [
                    'success' => false,
                    'message' => $error
                ];
            }

        } catch (\Exception $e) {
            Log::error('SMS service exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'SMS service error: ' . $e->getMessage()
            ];
        }
    }

    protected function cleanPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Convert Nigerian numbers to international format
        if (substr($phone, 0, 1) === '0') {
            $phone = '234' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) !== '234') {
            $phone = '234' . $phone;
        }
        
        return $phone;
    }

    public function sendBulkSms(array $phoneNumbers, $message)
    {
        $results = [];
        $successCount = 0;
        $failCount = 0;

        foreach ($phoneNumbers as $phoneNumber) {
            $result = $this->sendSms($phoneNumber, $message);
            $results[] = [
                'phone' => $phoneNumber,
                'result' => $result
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failCount++;
            }

            // Add small delay between messages to avoid rate limiting
            if (count($phoneNumbers) > 10) {
                usleep(100000); // 0.1 second delay
            }
        }

        return [
            'total' => count($phoneNumbers),
            'success' => $successCount,
            'failed' => $failCount,
            'results' => $results
        ];
    }

    public function getBalance()
    {
        try {
            if (empty($this->apiKey)) {
                return ['balance' => 'N/A - API key not configured'];
            }

            $response = Http::get('https://api.ng.termii.com/api/get-balance', [
                'api_key' => $this->apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return ['balance' => $data['balance'] ?? 'Unknown'];
            }

            return ['balance' => 'Unable to fetch'];
        } catch (\Exception $e) {
            return ['balance' => 'Error: ' . $e->getMessage()];
        }
    }
}