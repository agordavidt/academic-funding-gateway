<?php
// app/Notifications/SmsNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Channels\EbulkSmsChannel;
use App\Models\EbulkSmsMessage;

class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $content;
    protected $from;

    public function __construct($content, $from = null)
    {
        $this->content = $content;
        $this->from = $from;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return [EbulkSmsChannel::class];
    }

    /**
     * Get the EbulkSMS representation of the notification.
     */
    public function toEbulkSms($notifiable)
    {
        $message = (new EbulkSmsMessage())
            ->content($this->content);

        if ($this->from) {
            $message->from($this->from);
        }

        return $message;
    }
}