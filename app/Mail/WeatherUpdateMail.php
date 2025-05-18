<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeatherUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public array $weatherData
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Weather Update for {$this->subscription->city}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weather-update',
            with: [
                'city' => $this->subscription->city,
                'weatherData' => $this->weatherData,
                'unsubscribeUrl' => url("/unsubscribe/{$this->subscription->unsubscribe_token}"),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
