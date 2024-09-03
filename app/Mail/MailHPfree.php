<?php

namespace App\Mail;

use App\Models\Hpemail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailHPfree extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $Hpemail;

    /**
     * Create a new message instance.
     */
    public function __construct(Hpemail $Hpemail)
    {
        $this->Hpemail = $Hpemail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Crear usuario en CANAWIL',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mailuser',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
