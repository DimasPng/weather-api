<?php

namespace Tests\Feature;

use App\Exceptions\SubscriptionNotFoundException;
use App\Services\SubscriptionService;
use App\ValueObject\Token;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ConfirmSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function confirm_successful(): void
    {
        $validToken = Token::generate()->getValue();

        $this->mock(SubscriptionService::class, function ($mock) use ($validToken) {
            $mock->shouldReceive('confirm')
                ->once()
                ->with(Mockery::on(fn ($t) => $t instanceof Token && $t->getValue() === $validToken))
                ->andReturnNull();
        });

        $response = $this->getJson("/api/confirm/{$validToken}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Subscription confirmed successfully.']);
    }

    public function confirm_invalid_token_format(): void
    {
        $badToken = 'not-a-valid-token';

        $response = $this->getJson("/api/confirm/{$badToken}");

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson(['message' => 'Invalid token.']);
    }

    public function confirm_not_found(): void
    {
        $validToken = Token::generate()->getValue();

        $this->mock(SubscriptionService::class, function ($mock) {
            $mock->shouldReceive('confirm')
                ->once()
                ->andThrow(new SubscriptionNotFoundException('Token not found.'));
        });

        $response = $this->getJson("/api/confirm/{$validToken}");

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Token not found.']);
    }
}
