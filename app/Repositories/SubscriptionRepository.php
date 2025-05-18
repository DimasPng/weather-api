<?php

namespace App\Repositories;

use App\Enum\FrequencyEnum;
use App\Models\Subscription;
use App\ValueObject\Token;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class SubscriptionRepository
{
    public function create(array $data): Subscription
    {
        return Subscription::query()->create($data);
    }

    public function update(Subscription $sub, array $data): bool
    {
        return $sub->update($data);
    }

    public function findByConfirmToken(Token $token): ?Subscription
    {
        return Subscription::query()->where('confirmation_token', $token->getValue())->first();
    }

    public function findByUnsubscribeToken(Token $token): ?Subscription
    {
        return Subscription::query()->where('unsubscribe_token', $token->getValue())->first();
    }

    public function findAllConfirmedByCity(string $city, FrequencyEnum $frequency): LazyCollection
    {
        return Subscription::query()
            ->select(['id', 'email', 'unsubscribe_token'])
            ->where('confirmed', true)
            ->where('frequency', $frequency)
            ->where('city', $city)
            ->cursor();
    }

    public function getUniqueCities(): Collection
    {
        return Subscription::query()->select('city')->distinct()->get()->pluck('city');
    }
}
