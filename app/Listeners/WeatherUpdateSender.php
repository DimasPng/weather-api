<?php

namespace App\Listeners;

use App\Events\WeatherUpdatedEvent;
use App\Mail\WeatherUpdateMail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class WeatherUpdateSender implements ShouldQueue
{
    public function __construct() {}

    public function handle(WeatherUpdatedEvent $event): void
    {
        Mail::to($event->subscription->email)->send(new WeatherUpdateMail($event->subscription, $event->weather));

        $event->subscription->last_sent_at = Carbon::now();
        $event->subscription->save();
    }
}
