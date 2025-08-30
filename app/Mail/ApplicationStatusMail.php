<?php




namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $status;

    public function __construct(User $user, string $status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    public function build()
    {
        $subject = match($this->status) {
            'accepted' => 'Congratulations! Your Grant Application Has Been Accepted',
            'rejected' => 'Update on Your Grant Application',
            'reviewing' => 'Your Grant Application is Under Review',
            default => 'Grant Application Status Update'
        };

        return $this->subject($subject)
                    ->view('emails.application-status')
                    ->with([
                        'user' => $this->user,
                        'status' => $this->status,
                    ]);
    }
}