<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangedNewMail extends Mailable
{
    use Queueable, SerializesModels;



    /**
     * Create a new message instance.
     */
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to your new email')
            ->view('emails.email_changed_new')
            ->with([
                'user' => $this->user,
            ]);
    }
}
