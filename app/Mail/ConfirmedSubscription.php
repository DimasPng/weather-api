<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmedSubscription extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Confirmed',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscription_confirmed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
