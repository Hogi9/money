<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $recipientName) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mailgun Test — Laravel Money',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.test',
        );
    }
}
