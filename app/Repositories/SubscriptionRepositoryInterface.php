<?php

namespace App\Repositories;

use App\Models\Subscription;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function create(array $data): Subscription;

    public function update(Subscription $sub, array $data): bool;

    public function findByConfirmToken(string $token): ?Subscription;

    public function findByUnsubscribeToken(string $token): ?Subscription;

    public function findAllConfirmed(): Collection;

    public function findConfirmedByEmailAndCity(string $email, string $city): ?Subscription;
}
