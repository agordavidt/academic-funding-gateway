<?php


namespace App\Mail;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $payment;

    public function __construct(User $user, Payment $payment)
    {
        $this->user = $user;
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->subject('Payment Confirmation - Academic Funding Gateway')
                    ->view('emails.payment-confirmation')
                    ->with([
                        'user' => $this->user,
                        'payment' => $this->payment,
                    ]);
    }
}
