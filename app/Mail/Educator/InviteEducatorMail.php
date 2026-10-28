<?php

namespace App\Mail\Educator;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteEducatorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registrationUrl;

    public function __construct($registrationUrl)
    {
        $this->registrationUrl = $registrationUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to join the platform',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'admin::emails.invite_educator',
            with: ['registrationUrl' => $this->registrationUrl]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
