<?php

namespace Tests\Feature;

use App\Exceptions\CityNotFoundException;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use RefreshDatabase;

    private string $validEmail = 'test@gmail.com';

    public function test_successful_subscription(): void
    {
        $this->mock(SubscriptionService::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->with([
                    'email' => $this->validEmail,
                    'city' => 'London',
                    'frequency' => 'daily',
                ]);
        });

        $response = $this->postJson('/api/subscribe', [
            'email' => $this->validEmail,
            'city' => 'London',
            'frequency' => 'daily',
        ]);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Subscription successful. Confirmation email sent.',
            ]);
    }

    public function test_subscription_with_invalid_city(): void
    {
        $this->mock(SubscriptionService::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->andThrow(new CityNotFoundException('City not found'));
        });

        $response = $this->postJson('/api/subscribe', [
            'email' => $this->validEmail,
            'city' => 'InvalidCity',
            'frequency' => 'daily',
        ]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'message' => 'Invalid input.',
                'errors' => 'City not found',
            ]);
    }

    public function test_subscription_unexpected_error(): void
    {
        Log::shouldReceive('error')->once();

        $this->mock(SubscriptionService::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->andThrow(new \Exception('Database down'));
        });

        $response = $this->postJson('/api/subscribe', [
            'email' => $this->validEmail,
            'city' => 'Paris',
            'frequency' => 'daily',
        ]);

        $response
            ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson([
                'message' => 'An unexpected error occurred.',
            ]);
    }
}
