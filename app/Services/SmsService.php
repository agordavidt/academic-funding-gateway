<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class SmsService
{
    protected $username;
    protected $apiKey;
    protected $baseUrl;
    protected $sender;

    public function __construct()
    {
        // Change config to EbulkSMS credentials
        $this->username = config('services.ebulksms.username');
        $this->apiKey = config('services.ebulksms.apikey');
        $this->sender = config('services.ebulksms.sender', 'YourSenderID');
        $this->baseUrl = 'https://api.ebulksms.com/sendsms';
    }

    /**
     * Send SMS using EbulkSMS HTTP GET API
     * @param string $phoneNumber Can be a single number or a comma-separated string for bulk
     * @param string $message The SMS message content
     * @param string|null $sender The sender ID to use (optional)
     * @return array
     */
    public function sendSms($phoneNumber, $message, $sender = null)
    {
        try {
            // Remove any non-numeric characters from the phone number(s)
            $cleanedNumbers = collect(explode(',', $phoneNumber))
                ->map(fn ($num) => $this->cleanPhoneNumber($num))
                ->filter()
                ->implode(',');

            if (empty($this->apiKey) || empty($this->username)) {
                Log::warning('EbulkSMS API credentials not configured');
                return ['success' => false, 'message' => 'SMS service not properly configured'];
            }

            // For development/testing, log instead of sending
            if (app()->environment('local') && !config('services.ebulksms.send_in_local', false)) {
                Log::info('EbulkSMS would be sent in production', [
                    'phone' => $cleanedNumbers,
                    'message' => $message,
                    'sender' => $sender ?? $this->sender
                ]);
                return ['success' => true, 'message' => 'SMS logged (development mode)', 'message_id' => 'dev_' . time()];
            }

            // Make the HTTP GET request with query parameters
            $response = Http::get($this->baseUrl, [
                'username' => $this->username,
                'apikey' => $this->apiKey,
                'sender' => $sender ?? $this->sender,
                'messagetext' => $message,
                'recipients' => $cleanedNumbers,
            ]);

            $body = $response->body();
            Log::info('EbulkSMS API raw response', ['response' => $body]);

            // Check the response status
            if (str_contains($body, 'SUCCESS')) {
                // EbulkSMS returns a string like: SUCCESS|totalsent:1|cost:0.1
                $parts = explode('|', $body);
                $result = [];
                foreach ($parts as $part) {
                    if (str_contains($part, ':')) {
                        list($key, $value) = explode(':', $part);
                        $result[$key] = $value;
                    }
                }
                Log::info('SMS sent successfully via EbulkSMS', ['recipients' => $cleanedNumbers, 'result' => $result]);
                return ['success' => true, 'message' => 'SMS sent successfully', 'result' => $result];
            } else {
                // EbulkSMS returns a string like: FAILED|reason
                Log::error('EbulkSMS API request failed', ['recipients' => $cleanedNumbers, 'error' => $body]);
                return ['success' => false, 'message' => 'EbulkSMS API failed: ' . $body];
            }

        } catch (\Exception $e) {
            Log::error('EbulkSMS service exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'SMS service error: ' . $e->getMessage()];
        }
    }

    /**
     * Clean and format phone number(s)
     */
    protected function cleanPhoneNumber($phoneNumber)
    {
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (substr($phone, 0, 1) === '0') {
            $phone = '234' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) === '234' && strlen($phone) === 13) {
            
        } else {
          
            $phone = '234' . $phone;
        }
        return $phone;
    }
}