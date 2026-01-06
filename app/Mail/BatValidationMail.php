<?php

namespace App\Mail;

use App\Models\StandaloneBat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BatValidationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public StandaloneBat $bat
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'BAT a valider - ' . $this->bat->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bat-validation',
        );
    }
}
