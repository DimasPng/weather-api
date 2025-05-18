<?php

namespace App\Jobs;

use App\Mail\WeatherUpdateMail;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendWeatherUpdateEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Subscription $subscription,
        public array $data,
    ) {}

    public function handle(): void
    {
        Mail::to($this->subscription->email)->send(new WeatherUpdateMail($this->subscription, $this->data));
    }
}
