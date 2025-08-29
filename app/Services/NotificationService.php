<?php


namespace App\Services;

use App\Models\User;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client as TwilioClient;
use App\Mail\ApplicationStatusMail;
use App\Mail\PaymentConfirmationMail;

class NotificationService
{
    protected $twilioClient;
    
    public function __construct()
    {
        if (config('funding.sms.provider') === 'twilio') {
            $this->twilioClient = new TwilioClient(
                config('funding.sms.twilio.account_sid'),
                config('funding.sms.twilio.auth_token')
            );
        }
    }
    
    public function sendPaymentConfirmation(User $user): bool
    {
        try {
            // Send Email
            $emailSent = $this->sendEmail($user, [
                'type' => 'payment_confirmation',
                'subject' => 'Payment Confirmation - Academic Funding Gateway',
                'mailable' => new PaymentConfirmationMail($user)
            ]);
            
            // Send SMS
            $smsSent = $this->sendSms($user, 
                "Dear {$user->first_name}, your payment of ₦" . number_format(config('funding.acceptance_fee')) . 
                " has been confirmed. You can now proceed with your grant application."
            );
            
            return $emailSent || $smsSent;
        } catch (\Exception $e) {
            Log::error('Payment confirmation notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    public function sendApplicationStatusUpdate(User $user, string $status, string $additionalMessage = ''): bool
    {
        try {
            $statusMessages = [
                'reviewing' => 'Your grant application is now under review.',
                'accepted' => 'Congratulations! Your grant application has been accepted.',
                'rejected' => 'We regret to inform you that your grant application was not successful.'
            ];
            
            $message = $statusMessages[$status] ?? 'Your application status has been updated.';
            if ($additionalMessage) {
                $message .= ' ' . $additionalMessage;
            }
            
            // Send Email
            $emailSent = $this->sendEmail($user, [
                'type' => 'status_update',
                'subject' => 'Application Status Update - Academic Funding Gateway',
                'mailable' => new ApplicationStatusMail($user, $status, $message)
            ]);
            
            // Send SMS
            $smsMessage = "Dear {$user->first_name}, {$message}";
            $smsSent = $this->sendSms($user, $smsMessage);
            
            return $emailSent || $smsSent;
        } catch (\Exception $e) {
            Log::error('Status update notification failed', [
                'user_id' => $user->id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    protected function sendEmail(User $user, array $data): bool
    {
        try {
            if (!$user->email) {
                return false;
            }
            
            Mail::to($user->email)->send($data['mailable']);
            
            // Log notification
            NotificationModel::create([
                'user_id' => $user->id,
                'type' => 'email',
                'subject' => $data['subject'],
                'message_body' => 'Email sent successfully',
                'status' => 'sent',
                'sent_at' => now(),
                'metadata' => ['type' => $data['type']]
            ]);
            
            return true;
        } catch (\Exception $e) {
            // Log failed notification
            NotificationModel::create([
                'user_id' => $user->id,
                'type' => 'email',
                'subject' => $data['subject'] ?? 'Email notification',
                'message_body' => 'Failed to send email',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'metadata' => ['type' => $data['type'] ?? 'unknown']
            ]);
            
            Log::error('Email notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
    
    protected function sendSms(User $user, string $message): bool
    {
        try {
            if (!$user->phone_number || !$this->twilioClient) {
                return false;
            }
            
            $this->twilioClient->messages->create(
                $user->phone_number,
                [
                    'from' => config('funding.sms.twilio.from_number'),
                    'body' => $message
                ]
            );
            
            // Log notification
            NotificationModel::create([
                'user_id' => $user->id,
                'type' => 'sms',
                'message_body' => $message,
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            return true;
        } catch (\Exception $e) {
            // Log failed notification
            NotificationModel::create([
                'user_id' => $user->id,
                'type' => 'sms',
                'message_body' => $message,
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            
            Log::error('SMS notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
