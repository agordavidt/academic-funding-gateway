<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use App\Notifications\SmsNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        $subject = 'Payment Approved - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, your payment of ₦{$payment->amount} has been approved. " .
                  "Your application is now being reviewed. You will be notified of the outcome soon.";

        // Send email
        $this->sendEmail($user, $subject, $message);
        
        // Send SMS using specialized notification
        if ($user->phone_number) {
            try {
                $user->notify(new SmsNotification($smsMessage));
                Log::info('Payment approved SMS sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send payment approved SMS', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Fallback to generic SMS
                $user->notify(new SmsNotification($message));
            }
        }

        // Store notification record
        $this->storeNotification($user, 'payment_approved', $subject, $message);
    }

    /**
     * Send payment rejected notification
     */
    public function sendPaymentRejected(User $user, Payment $payment, $reason)
    {
        $subject = 'Payment Evidence Rejected - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, your payment evidence has been rejected. " .
                  "Reason: {$reason}. Please upload a clear payment receipt and try again.";

        // Send email
        $this->sendEmail($user, $subject, $message);
        
        // Send SMS using specialized notification
        if ($user->phone_number) {
            try {
                $user->notify(new SmsNotification($smsMessage));
                Log::info('Payment rejected SMS sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send payment rejected SMS', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Fallback to generic SMS
                $user->notify(new SmsNotification($message));
            }
        }

        // Store notification record
        $this->storeNotification($user, 'payment_rejected', $subject, $message);
    }

    /**
     * Send application status update
     */
    public function sendApplicationStatusUpdate(User $user, $status)
    {
        $statusMessages = [
            'pending' => 'Your application is pending review.',
            'reviewing' => 'Your application is currently under review.',
            'accepted' => 'Congratulations! Your application has been ACCEPTED. You will receive further instructions soon.',
            'rejected' => 'We regret to inform you that your application has not been successful this time.'
        ];

        $subject = 'Application Status Update - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, " . ($statusMessages[$status] ?? 'Your application status has been updated.');

        // Send email
        $this->sendEmail($user, $subject, $message);
        
        // Send SMS using specialized notification
        if ($user->phone_number) {
            try {
                $user->notify(new ApplicationStatusSmsNotification($user, $status));
                Log::info('Application status SMS sent', ['user_id' => $user->id, 'status' => $status]);
            } catch (\Exception $e) {
                Log::error('Failed to send application status SMS', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Fallback to generic SMS
                $user->notify(new SmsNotification($message));
            }
        }

        // Store notification record
        $this->storeNotification($user, 'application_status', $subject, $message);
    }

    /**
     * Send training assignment notification
     */
    public function sendTrainingAssignment(User $user, $trainingInstitution)
    {
        $subject = 'Training Institution Assignment - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, you have been assigned to {$trainingInstitution->name} " .
                  "for your training program. Please report on {$trainingInstitution->start_date}. " .
                  "Contact: {$trainingInstitution->contact_phone}";

        // Send email
        $this->sendEmail($user, $subject, $message);
        
        // Send SMS using specialized notification
        if ($user->phone_number) {
            try {
                $user->notify(new SmsNotification($smsMessage));
                Log::info('Training assignment SMS sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send training assignment SMS', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Fallback to generic SMS
                $user->notify(new SmsNotification($message));
            }
        }

        // Store notification record
        $this->storeNotification($user, 'training_assignment', $subject, $message);
    }

    /**
     * Send custom SMS to user (for admin use)
     */
    public function sendCustomSms(User $user, $message, $from = null)
    {
        if (!$user->phone_number) {
            return [
                'success' => false,
                'message' => 'User does not have a phone number'
            ];
        }

        // Use SMS notification for consistency
        try {
            $user->notify(new SmsNotification($message, $from));
            
            // Store notification record
            $this->storeNotification($user, 'custom_sms', 'Custom SMS', $message);
            
            return [
                'success' => true,
                'message' => 'SMS sent successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Custom SMS failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk SMS to multiple users
     */
    public function sendBulkSms(array $users, $message, $from = null)
    {
        // Filter users with phone numbers
        $usersWithPhone = collect($users)->filter(function($user) {
            return !empty($user->phone_number);
        });

        if ($usersWithPhone->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No users with valid phone numbers found',
                'total' => 0,
                'success_count' => 0,
                'failed_count' => 0
            ];
        }

        try {
            // Use the bulk SMS notification method
            $result = $this->smsService->sendBulkSmsNotifications($usersWithPhone->toArray(), $message, $from);
            
            // Store notification records for all users
            foreach ($usersWithPhone as $user) {
                $this->storeNotification($user, 'bulk_sms', 'Bulk SMS', $message);
            }

            return [
                'success' => true,
                'message' => 'Bulk SMS sent successfully',
                'total' => $usersWithPhone->count(),
                'success_count' => $usersWithPhone->count(),
                'failed_count' => 0
            ];
        } catch (\Exception $e) {
            Log::error('Bulk SMS failed', [
                'user_count' => $usersWithPhone->count(),
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send bulk SMS: ' . $e->getMessage(),
                'total' => $usersWithPhone->count(),
                'success_count' => 0,
                'failed_count' => $usersWithPhone->count()
            ];
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
    protected function storeNotification(User $user, $type, $subject, $message)
    {
        try {
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'subject' => $subject,
                'message_body' => $message,
                'status' => 'sent',
                'sent_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store notification', [
                'user_id' => $user->id,
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