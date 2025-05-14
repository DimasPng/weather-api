<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmSubscription extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $sub) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirm your subscription',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirm',
            with: [
                'confirmUrl' => url("/api/confirm/{$this->sub->confirm_token}"),
                'city' => $this->sub->city,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
