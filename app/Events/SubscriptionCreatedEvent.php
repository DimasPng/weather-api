<?php

namespace App\Events;

use App\Models\Subscription;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Subscription $subscription) {}
}
