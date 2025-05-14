<?php

namespace App\Repositories;

use App\Models\Subscription;
use Illuminate\Support\Collection;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function create(array $data): Subscription
    {
        return Subscription::query()->create($data);
    }

    public function update(Subscription $sub, array $data): bool
    {
        return $sub->update($data);
    }

    public function findByConfirmToken(string $token): ?Subscription
    {
        return Subscription::query()->where('confirm_token', $token)->first();
    }

    public function findByUnsubscribeToken(string $token): ?Subscription
    {
        return Subscription::query()->where('unsubscribe_token', $token)->first();
    }

    public function findAllConfirmed(): Collection
    {
        return Subscription::query()->where('confirmed', true)->get();
    }

    public function findConfirmedByEmailAndCity(string $email, string $city): ?Subscription
    {
        return Subscription::query()
            ->where('email', $email)
            ->where('city', $city)
            ->where('confirmed', true)
            ->first();
    }
}
