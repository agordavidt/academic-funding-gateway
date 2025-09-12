<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\SmsService;

class EbulkSmsChannel
{
    /**
     * @var SmsService
     */
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Get the phone number from the notifiable model (e.g., User)
        $phoneNumber = $notifiable->phone_number;

        if (!$phoneNumber) {
            return;
        }

        // Get the message from the notification class
        $message = $notification->toEbulkSms($notifiable);

        // Send the message using our new SmsService
        $this->smsService->sendSms($phoneNumber, $message->content, $message->from);
    }
}