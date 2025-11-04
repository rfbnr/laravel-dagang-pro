<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ForcePasswordChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $tempPassword;

    public function __construct($user, $tempPassword)
    {
        $this->user = $user;
        $this->tempPassword = $tempPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Temporary Password - Please Change It',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.force-password-change',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
