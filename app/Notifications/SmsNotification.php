<?php
// app/Notifications/SmsNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\AfricasTalking\AfricasTalkingChannel;
use NotificationChannels\AfricasTalking\AfricasTalkingMessage;

class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $from;

    public function __construct($message, $from = null)
    {
        $this->message = $message;
        $this->from = $from;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return [AfricasTalkingChannel::class];
    }

    /**
     * Get the Africa's Talking representation of the notification.
     */
    public function toAfricasTalking($notifiable)
    {
        $africasTalkingMessage = (new AfricasTalkingMessage())
            ->content($this->message);

        if ($this->from) {
            $africasTalkingMessage->from($this->from);
        }

        return $africasTalkingMessage;
    }
}

// Specialized notification classes for different types of messages
class PaymentApprovedSmsNotification extends SmsNotification
{
    protected $payment;

    public function __construct($user, $payment)
    {
        $this->payment = $payment;
        $message = "Dear {$user->first_name}, your payment of ₦{$payment->amount} has been approved. " .
                  "Your application is now being reviewed. You will be notified of the outcome soon.";
        
        parent::__construct($message);
    }
}

class PaymentRejectedSmsNotification extends SmsNotification
{
    protected $payment;
    protected $reason;

    public function __construct($user, $payment, $reason)
    {
        $this->payment = $payment;
        $this->reason = $reason;
        $message = "Dear {$user->first_name}, your payment evidence has been rejected. " .
                  "Reason: {$reason}. Please upload a clear payment receipt and try again.";
        
        parent::__construct($message);
    }
}

class ApplicationStatusSmsNotification extends SmsNotification
{
    protected $status;

    public function __construct($user, $status)
    {
        $this->status = $status;
        
        $statusMessages = [
            'pending' => 'Your application is pending review.',
            'reviewing' => 'Your application is currently under review.',
            'accepted' => 'Congratulations! Your application has been ACCEPTED. You will receive further instructions soon.',
            'rejected' => 'We regret to inform you that your application has not been successful this time.'
        ];

        $message = "Dear {$user->first_name}, " . ($statusMessages[$status] ?? 'Your application status has been updated.');
        parent::__construct($message);
    }
}

class TrainingAssignmentSmsNotification extends SmsNotification
{
    protected $trainingInstitution;

    public function __construct($user, $trainingInstitution)
    {
        $this->trainingInstitution = $trainingInstitution;
        $message = "Dear {$user->first_name}, you have been assigned to {$trainingInstitution->name} " .
                  "for your training program. Please report on {$trainingInstitution->start_date}. " .
                  "Contact: {$trainingInstitution->contact_phone}";
        
        parent::__construct($message);
    }
}