<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SmsNotification;

class SmsService
{
    protected $username;
    protected $apiKey;
    protected $from;
    protected $baseUrl;

    public function __construct()
    {
        $this->username = config('services.africastalking.username');
        $this->apiKey = config('services.africastalking.key');
        $this->from = config('services.africastalking.from', '');
        $this->baseUrl = 'https://api.africastalking.com/version1/messaging';
    }

    /**
     * Send SMS using Africa's Talking API
     */
    public function sendSms($phoneNumber, $message, $from = null)
    {
        try {
            $phoneNumber = $this->cleanPhoneNumber($phoneNumber);

            if (empty($this->apiKey) || empty($this->username)) {
                Log::warning('SMS API credentials not configured');
                return [
                    'success' => false,
                    'message' => 'SMS service not properly configured'
                ];
            }

            // For development/testing, log instead of sending
            if (app()->environment('local') && !config('services.africastalking.send_in_local', false)) {
                Log::info('SMS would be sent in production', [
                    'phone' => $phoneNumber,
                    'message' => $message,
                    'from' => $from ?? $this->from
                ]);
                return [
                    'success' => true,
                    'message' => 'SMS logged (development mode)',
                    'message_id' => 'dev_' . time()
                ];
            }

            $headers = [
                'apiKey' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ];

            $data = [
                'username' => $this->username,
                'to' => $phoneNumber,
                'message' => $message,
            ];

            // Add sender ID if provided
            if ($from ?? $this->from) {
                $data['from'] = $from ?? $this->from;
            }

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->asForm()
                ->post($this->baseUrl, $data);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Africa's Talking returns SMSMessageData
                if (isset($responseData['SMSMessageData']['Recipients'])) {
                    $recipients = $responseData['SMSMessageData']['Recipients'];
                    $recipient = $recipients[0] ?? null;
                    
                    if ($recipient && isset($recipient['statusCode']) && $recipient['statusCode'] == 101) {
                        Log::info('SMS sent successfully via Africa\'s Talking', [
                            'phone' => $phoneNumber,
                            'messageId' => $recipient['messageId'] ?? null,
                            'cost' => $recipient['cost'] ?? null
                        ]);

                        return [
                            'success' => true,
                            'message' => 'SMS sent successfully',
                            'message_id' => $recipient['messageId'] ?? null,
                            'cost' => $recipient['cost'] ?? null
                        ];
                    } else {
                        $error = $recipient['status'] ?? 'Failed to send SMS';
                        Log::error('SMS sending failed', [
                            'phone' => $phoneNumber,
                            'error' => $error,
                            'status_code' => $recipient['statusCode'] ?? null
                        ]);

                        return [
                            'success' => false,
                            'message' => $error
                        ];
                    }
                } else {
                    Log::error('Unexpected Africa\'s Talking API response format', [
                        'response' => $responseData
                    ]);
                    return [
                        'success' => false,
                        'message' => 'Unexpected API response format'
                    ];
                }
            } else {
                $error = $response->json()['message'] ?? 'Failed to send SMS';
                Log::error('SMS API request failed', [
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

    /**
     * Send SMS using Laravel Notifications (recommended approach)
     */
    public function sendSmsNotification($user, $message, $from = null)
    {
        try {
            $notification = new SmsNotification($message, $from);
            $user->notify($notification);
            
            Log::info('SMS notification queued successfully', [
                'user_id' => $user->id,
                'phone' => $user->phone_number
            ]);

            return [
                'success' => true,
                'message' => 'SMS notification sent successfully'
            ];
        } catch (\Exception $e) {
            Log::error('SMS notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send SMS notification: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Clean and format phone number for Nigerian numbers
     */
    protected function cleanPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Convert Nigerian numbers to international format
        if (substr($phone, 0, 1) === '0') {
            $phone = '+234' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) === '234') {
            $phone = '+' . $phone;
        } elseif (substr($phone, 0, 4) !== '+234') {
            // Assume it's a Nigerian number without country code
            $phone = '+234' . $phone;
        }
        
        return $phone;
    }

    /**
     * Send bulk SMS using notifications (efficient for large volumes)
     */
    public function sendBulkSmsNotifications(array $users, $message, $from = null)
    {
        try {
            $notification = new SmsNotification($message, $from);
            
            // Use Laravel's notification system for bulk sending
            Notification::send($users, $notification);

            Log::info('Bulk SMS notifications queued', [
                'user_count' => count($users),
                'message_length' => strlen($message)
            ]);

            return [
                'success' => true,
                'message' => 'Bulk SMS notifications queued successfully',
                'total' => count($users)
            ];
        } catch (\Exception $e) {
            Log::error('Bulk SMS notification failed', [
                'user_count' => count($users),
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send bulk SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get Africa's Talking account balance
     */
    public function getBalance()
    {
        try {
            if (empty($this->apiKey) || empty($this->username)) {
                return ['balance' => 'N/A - API credentials not configured'];
            }

            // Use appropriate balance endpoint based on environment
            $isSandbox = config('services.africastalking.sandbox', true);
            $balanceUrl = $isSandbox 
                ? 'https://api.sandbox.africastalking.com/version1/user'
                : 'https://api.africastalking.com/version1/user';

            $response = Http::withHeaders([
                'apiKey' => $this->apiKey,
                'Accept' => 'application/json'
            ])->get($balanceUrl, [
                'username' => $this->username
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'balance' => $data['UserData']['balance'] ?? 'Unknown',
                    'currency' => 'Credits'
                ];
            }

            return ['balance' => 'Unable to fetch'];
        } catch (\Exception $e) {
            return ['balance' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Validate phone number format
     */
    public function isValidPhoneNumber($phoneNumber)
    {
        $cleaned = $this->cleanPhoneNumber($phoneNumber);
        // Nigerian mobile numbers should be 14 characters (+234xxxxxxxxx)
        return preg_match('/^\+234[7-9][0-9]{9}$/', $cleaned);
    }
}