<?php

namespace Tests\Unit;

use App\Events\SubscriptionConfirmedEvent;
use App\Events\SubscriptionCreatedEvent;
use App\Exceptions\CityNotFoundException;
use App\Exceptions\SubscriptionNotFoundException;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use App\Services\SubscriptionService;
use App\Services\WeatherApiClient;
use App\ValueObject\Token;
use Illuminate\Support\Facades\Event;
use Mockery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    /**
     * @throws CityNotFoundException
     */
    public function test_create_successfully_calls_verify_and_repository_and_dispatches_event(): void
    {
        Event::fake();

        $city = 'TestCity';
        $email = 'user@example.com';
        $frequency = 'daily';

        $weather = Mockery::mock(WeatherApiClient::class);
        $weather->shouldReceive('verifyCityExists')
            ->once()
            ->with($city);

        $repo = Mockery::mock(SubscriptionRepository::class);
        $subscription = Mockery::mock(Subscription::class);

        $repo->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) use ($email, $city, $frequency) {
                return $data['email'] === $email
                    && $data['city'] === $city
                    && $data['frequency'] === $frequency
                    && is_string($data['confirmation_token'])
                    && is_string($data['unsubscribe_token'])
                    && $data['confirmed'] === false;
            }))
            ->andReturn($subscription);

        $service = new SubscriptionService($weather, $repo);
        $service->create([
            'email' => $email,
            'city' => $city,
            'frequency' => $frequency,
        ]);

        Event::assertDispatched(SubscriptionCreatedEvent::class, function ($e) use ($subscription) {
            return $e->subscription === $subscription;
        });
    }

    public function test_create_throws_when_city_not_found(): void
    {
        $this->expectException(CityNotFoundException::class);

        $weather = Mockery::mock(WeatherApiClient::class);
        $weather->shouldReceive('verifyCityExists')
            ->once()
            ->andThrow(new CityNotFoundException('City not found'));

        $repo = Mockery::mock(SubscriptionRepository::class);

        $service = new SubscriptionService($weather, $repo);
        $service->create([
            'email' => 'a@b.com',
            'city' => 'Nowhere',
            'frequency' => 'daily',
        ]);
    }

    /**
     * @throws SubscriptionNotFoundException
     */
    public function test_confirm_successfully_finds_updates_and_dispatches_event(): void
    {
        Event::fake();

        $tokenValue = Token::generate()->getValue();
        $token = Token::fromString($tokenValue);

        $repo = Mockery::mock(SubscriptionRepository::class);
        $subscription = Mockery::mock(Subscription::class);

        $repo->shouldReceive('findByConfirmToken')
            ->once()
            ->with(Mockery::on(fn ($t) => $t instanceof Token && $t->getValue() === $tokenValue))
            ->andReturn($subscription);

        $repo->shouldReceive('update')
            ->once()
            ->with($subscription, ['confirmed' => true, 'confirmation_token' => null])
            ->andReturnTrue();

        $service = new SubscriptionService(Mockery::mock(WeatherApiClient::class), $repo);
        $service->confirm($token);

        Event::assertDispatched(SubscriptionConfirmedEvent::class, function ($e) use ($subscription) {
            return $e->subscription === $subscription;
        });
    }

    public function test_confirm_throws_when_subscription_not_found(): void
    {
        $this->expectException(SubscriptionNotFoundException::class);

        $token = Token::generate();

        $repo = Mockery::mock(SubscriptionRepository::class);
        $repo->shouldReceive('findByConfirmToken')
            ->once()
            ->andReturnNull();

        $service = new SubscriptionService(Mockery::mock(WeatherApiClient::class), $repo);
        $service->confirm($token);
    }

    public function test_unsubscribe_successfully_updates_subscription(): void
    {
        $tokenValue = Token::generate()->getValue();
        $token = Token::fromString($tokenValue);

        $repo = Mockery::mock(SubscriptionRepository::class);
        $subscription = Mockery::mock(Subscription::class);

        $repo->shouldReceive('findByUnsubscribeToken')
            ->once()
            ->with(Mockery::on(fn ($t) => $t instanceof Token && $t->getValue() === $tokenValue))
            ->andReturn($subscription);

        $repo->shouldReceive('update')
            ->once()
            ->with($subscription, ['confirmed' => false, 'unsubscribe_token' => null])
            ->andReturnTrue();

        $service = new SubscriptionService(Mockery::mock(WeatherApiClient::class), $repo);
        $service->unsubscribe($token);

        $this->assertTrue(true);
    }

    public function test_unsubscribe_throws_when_subscription_not_found(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $token = Token::generate();

        $repo = Mockery::mock(SubscriptionRepository::class);
        $repo->shouldReceive('findByUnsubscribeToken')
            ->once()
            ->andReturnNull();

        $service = new SubscriptionService(Mockery::mock(WeatherApiClient::class), $repo);
        $service->unsubscribe($token);
    }
}
