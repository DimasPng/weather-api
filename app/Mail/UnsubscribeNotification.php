<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnsubscribeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $sub) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Unsubscribe from Weather Alerts',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.unsubscribe',
            with: [
                'unsubscribeUrl' => url("/api/unsubscribe/{$this->sub->unsubscribe_token}"),
                'city' => $this->sub->city,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
