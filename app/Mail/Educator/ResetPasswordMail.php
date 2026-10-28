<?php

namespace App\Mail\Educator;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password via OTP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'admin::emails.reset_password',
            with: ['lang' => 'en', 'code' => $this->code]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
