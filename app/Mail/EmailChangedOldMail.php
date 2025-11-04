<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangedOldMail extends Mailable
{
    use Queueable, SerializesModels;



    /**
     * Create a new message instance.
     */
    public $oldEmail;
    public $newEmail;

    public function __construct($oldEmail, $newEmail)
    {
        $this->oldEmail = $oldEmail;
        $this->newEmail = $newEmail;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Email Address Changed')
            ->view('emails.email_changed_old')
            ->with([
                'oldEmail' => $this->oldEmail,
                'newEmail' => $this->newEmail,
            ]);
    }
}
