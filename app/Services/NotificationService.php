<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use App\Notifications\SmsNotification;
use App\Notifications\PaymentApprovedSmsNotification;
use App\Notifications\PaymentRejectedSmsNotification;
use App\Notifications\ApplicationStatusSmsNotification;
use App\Notifications\TrainingAssignmentSmsNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class NotificationService
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send payment submission confirmation
     */
    public function sendPaymentSubmittedConfirmation(User $user, Payment $payment)
    {
        $subject = 'Payment Evidence Submitted - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, your payment evidence has been submitted successfully. " .
                   "Transaction ID: {$payment->transaction_id}. We will verify your payment within 24 hours.";

        $this->sendNotification($user, 'payment_submitted', $subject, $message);
    }

    /**
     * Send payment approved notification
     */
    public function sendPaymentApproved(User $user, Payment $payment)
    {
        // Email and database logic (removed for brevity)

        if ($user->phone_number) {
            try {
                $user->notify(new PaymentApprovedSmsNotification($user, $payment));
                Log::info('Payment approved SMS sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send payment approved SMS', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        $this->storeNotification($user, 'payment_approved', $subject, $message);
    }

    // Similarly, fix other methods to use the specialized classes
    public function sendPaymentRejected(User $user, Payment $payment, $reason)
    {
        // ... (removed for brevity)
        if ($user->phone_number) {
            try {
                $user->notify(new PaymentRejectedSmsNotification($user, $payment, $reason));
                Log::info('Payment rejected SMS sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                // ...
            }
        }
        $this->storeNotification($user, 'payment_rejected', $subject, $message);
    }

    public function sendApplicationStatusUpdate(User $user, $status)
    {
        // ... (removed for brevity)
        if ($user->phone_number) {
            try {
                $user->notify(new ApplicationStatusSmsNotification($user, $status));
                Log::info('Application status SMS sent', ['user_id' => $user->id, 'status' => $status]);
            } catch (\Exception $e) {
                // ...
            }
        }
        $this->storeNotification($user, 'application_status', $subject, $message);
    }
    
    public function sendTrainingAssignment(User $user, $trainingInstitution)
    {
        // ... (removed for brevity)
        if ($user->phone_number) {
            try {
                $user->notify(new TrainingAssignmentSmsNotification($user, $trainingInstitution));
                Log::info('Training assignment SMS sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                // ...
            }
        }
        $this->storeNotification($user, 'training_assignment', $subject, $message);
    }

    /**
     * Send custom SMS to user (for admin use)
     */
    public function sendCustomSms(User $user, $message, $from = null)
    {
        if (!$user->phone_number) {
            $this->storeNotification($user, 'custom_sms', 'Custom SMS Failed', 'User does not have a phone number');
            return ['success' => false, 'message' => 'User does not have a phone number.'];
        }

        try {
            // Use the generic SmsNotification class
            $user->notify(new SmsNotification($message, $from));
            
            $this->storeNotification($user, 'custom_sms', 'Custom SMS', $message);

            return ['success' => true, 'message' => 'SMS sent successfully.'];
        } catch (\Exception $e) {
            Log::error('Failed to send custom SMS notification: ' . $e->getMessage(), ['user_id' => $user->id]);
            $this->storeNotification($user, 'custom_sms_failed', 'Custom SMS Failed', $e->getMessage());

            // The catch block now returns a consistent error array.
            return ['success' => false, 'message' => 'Failed to send SMS notification.'];
        }
    }

    /**
     * Send bulk SMS to multiple users
     */
    public function sendBulkSms(array $users, $message, $from = null)
    {
        // Filter users with phone numbers
        $usersWithPhone = collect($users)->filter(fn($user) => !empty($user->phone_number));

        if ($usersWithPhone->isEmpty()) {
            return ['success' => false, 'message' => 'No users with valid phone numbers found', 'total' => 0];
        }

        try {
            FacadesNotification::send($usersWithPhone, new SmsNotification($message, $from));

            $this->storeNotification(null, 'bulk_sms', 'Bulk SMS sent', "Sent a bulk SMS to {$usersWithPhone->count()} users.");

            return ['success' => true, 'message' => "Bulk SMS sent to {$usersWithPhone->count()} users.", 'total' => $usersWithPhone->count()];
        } catch (\Exception $e) {
            Log::error('Failed to send bulk SMS notification: ' . $e->getMessage());
            $this->storeNotification(null, 'bulk_sms_failed', 'Bulk SMS failed', $e->getMessage());

            return ['success' => false, 'message' => 'Failed to send bulk SMS notification.', 'total' => 0];
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmail(User $user, $subject, $message)
    {
        if (!$user->email) {
            return;
        }

        try {
            Mail::raw($message, function ($mail) use ($user, $subject) {
                $mail->to($user->email)
                     ->subject($subject);
            });

            Log::info('Email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'subject' => $subject
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store notification in database for record keeping
     */
    protected function storeNotification(?User $user, $type, $subject, $message)
    {
        try {
            Notification::create([
                'user_id' => $user->id ?? null,
                'type' => $type,
                'subject' => $subject,
                'message_body' => $message,
                'status' => 'sent',
                'sent_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store notification', [
                'user_id' => $user->id ?? null,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Legacy method for backward compatibility
     * @deprecated Use specific methods instead
     */
    protected function sendNotification(User $user, $type, $subject, $message)
    {
        // Send email
        $this->sendEmail($user, $subject, $message);
        
        // Send SMS using generic notification
        if ($user->phone_number) {
            $user->notify(new SmsNotification($message));
        }

        // Store notification record
        $this->storeNotification($user, $type, $subject, $message);
    }
}