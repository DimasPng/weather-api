<?php

namespace App\Listeners;

use App\Events\SubscriptionCreatedEvent;
use App\Mail\ConfirmSubscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMail implements ShouldQueue
{
    public function __construct() {}

    public function handle(SubscriptionCreatedEvent $event): void
    {
        Mail::to($event->subscription->email)->send(new ConfirmSubscription($event->subscription));
    }
}
