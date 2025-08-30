<?php


namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use App\Mail\ApplicationStatusMail;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendApplicationStatusUpdate(User $user, string $status)
    {
        try {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => 'email',
                'subject' => $this->getStatusSubject($status),
                'message_body' => $this->getStatusMessage($user, $status),
                'status' => 'pending'
            ]);

            Mail::to($user->email)->send(new ApplicationStatusMail($user, $status));

            $notification->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            Log::info("Application status email sent to {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send application status email: " . $e->getMessage());
            
            if (isset($notification)) {
                $notification->update(['status' => 'failed']);
            }
        }
    }

    public function sendPaymentConfirmation(User $user, Payment $payment)
    {
        try {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => 'email',
                'subject' => 'Payment Confirmation',
                'message_body' => "Payment of ₦{$payment->amount} has been confirmed.",
                'status' => 'pending'
            ]);

            Mail::to($user->email)->send(new PaymentConfirmationMail($user, $payment));

            $notification->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            Log::info("Payment confirmation email sent to {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send payment confirmation email: " . $e->getMessage());
            
            if (isset($notification)) {
                $notification->update(['status' => 'failed']);
            }
        }
    }

    private function getStatusSubject(string $status): string
    {
        return match($status) {
            'accepted' => 'Grant Application Accepted',
            'rejected' => 'Grant Application Update',
            'reviewing' => 'Application Under Review',
            default => 'Application Status Update'
        };
    }

    private function getStatusMessage(User $user, string $status): string
    {
        return match($status) {
            'accepted' => "Congratulations {$user->full_name}! Your grant application has been accepted.",
            'rejected' => "Dear {$user->full_name}, after careful review, your grant application was not selected.",
            'reviewing' => "Dear {$user->full_name}, your grant application is currently under review.",
            default => "Dear {$user->full_name}, your application status has been updated."
        };
    }
}