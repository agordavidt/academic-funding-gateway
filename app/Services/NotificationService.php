<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendPaymentSubmittedConfirmation(User $user, Payment $payment)
    {
        $subject = 'Payment Evidence Submitted - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, your payment evidence has been submitted successfully. " .
                  "Transaction ID: {$payment->transaction_id}. We will verify your payment within 24 hours.";

        $this->sendNotification($user, $subject, $message);
    }

    public function sendPaymentApproved(User $user, Payment $payment)
    {
        $subject = 'Payment Approved - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, your payment of ₦{$payment->amount} has been approved. " .
                  "Your application is now being reviewed. You will be notified of the outcome soon.";

        $this->sendNotification($user, $subject, $message);
    }

    public function sendPaymentRejected(User $user, Payment $payment, $reason)
    {
        $subject = 'Payment Evidence Rejected - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, your payment evidence has been rejected. " .
                  "Reason: {$reason}. Please upload a clear payment receipt and try again.";

        $this->sendNotification($user, $subject, $message);
    }

    public function sendApplicationStatusUpdate(User $user, $status)
    {
        $statusMessages = [
            'pending' => 'Your application is pending review.',
            'reviewing' => 'Your application is currently under review.',
            'accepted' => 'Congratulations! Your application has been ACCEPTED. You will receive further instructions soon.',
            'rejected' => 'We regret to inform you that your application has not been successful this time.'
        ];

        $subject = 'Application Status Update - Academic Funding Gateway';
        $message = "Dear {$user->first_name}, " . $statusMessages[$status];

        $this->sendNotification($user, $subject, $message);
    }

    protected function sendNotification(User $user, $subject, $message)
    {
        // Send Email if available
        if ($user->email) {
            try {
                // You should create proper email templates for this
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

        // Send SMS if phone number is available
        if ($user->phone_number) {
            $smsResult = $this->smsService->sendSms($user->phone_number, $message);
            
            if (!$smsResult['success']) {
                Log::warning('Failed to send SMS notification', [
                    'user_id' => $user->id,
                    'phone' => $user->phone_number,
                    'error' => $smsResult['message']
                ]);
            }
        }
    }
}