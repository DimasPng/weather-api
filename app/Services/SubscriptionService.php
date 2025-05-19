<?php

namespace App\Services;

use App\Events\SubscriptionConfirmedEvent;
use App\Events\SubscriptionCreatedEvent;
use App\Exceptions\CityNotFoundException;
use App\Exceptions\SubscriptionNotFoundException;
use App\Repositories\SubscriptionRepository;
use App\ValueObject\Token;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscriptionService
{
    public function __construct(
        private readonly WeatherApiClient $weatherClient,
        private readonly SubscriptionRepository $subscriptionRepository,
    ) {}

    /**
     * @throws CityNotFoundException
     */
    public function create(array $data): void
    {
        $this->weatherClient->verifyCityExists($data['city']);

        $subscription = $this->subscriptionRepository->create([
            'email' => $data['email'],
            'city' => $data['city'],
            'frequency' => $data['frequency'],
            'confirmation_token' => Token::generate()->getValue(),
            'unsubscribe_token' => Token::generate()->getValue(),
            'confirmed' => false,
        ]);

        SubscriptionCreatedEvent::dispatch($subscription);
    }

    /**
     * @throws SubscriptionNotFoundException
     */
    public function confirm(Token $token): void
    {
        $subscription = $this->subscriptionRepository->findByConfirmToken($token);

        if (! $subscription) {
            throw new SubscriptionNotFoundException('Token not found.');
        }

        $this->subscriptionRepository->update($subscription, ['confirmed' => true, 'confirmation_token' => null]);

        SubscriptionConfirmedEvent::dispatch($subscription);
    }

    public function unsubscribe(Token $token): void
    {
        $subscription = $this->subscriptionRepository->findByUnsubscribeToken($token);

        if (! $subscription) {
            throw new NotFoundHttpException('Token not found.');
        }

        $this->subscriptionRepository->update($subscription, ['confirmed' => false, 'unsubscribe_token' => null]);
    }
}
