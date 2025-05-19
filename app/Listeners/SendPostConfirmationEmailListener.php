<?php

namespace App\Listeners;

use App\Events\SubscriptionConfirmedEvent;
use App\Mail\ConfirmedSubscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPostConfirmationEmailListener implements ShouldQueue
{
    public function __construct() {}

    public function handle(SubscriptionConfirmedEvent $event): void
    {
        Mail::to($event->subscription->email)->send(new ConfirmedSubscription($event->subscription));
    }
}
