<?php

namespace Tests\Feature;

use App\Services\SubscriptionService;
use App\ValueObject\Token;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class UnsubscribeSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unsubscribe_successful(): void
    {
        $validToken = Token::generate()->getValue();

        $this->mock(SubscriptionService::class, function ($mock) use ($validToken) {
            $mock->shouldReceive('unsubscribe')
                ->once()
                ->with(Mockery::on(fn ($t) => $t instanceof Token && $t->getValue() === $validToken))
                ->andReturnNull();
        });

        $response = $this->getJson("/api/unsubscribe/{$validToken}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Unsubscribed successfully.',
            ]);
    }

    public function test_unsubscribe_invalid_token_format(): void
    {
        $badToken = 'not-a-valid-token';

        $response = $this->getJson("/api/unsubscribe/{$badToken}");

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'message' => 'Invalid token.',
            ]);
    }

    public function test_unsubscribe_not_found(): void
    {
        $validToken = Token::generate()->getValue();

        $this->mock(SubscriptionService::class, function ($mock) {
            $mock->shouldReceive('unsubscribe')
                ->once()
                ->andThrow(new NotFoundHttpException('Token not found.'));
        });

        $response = $this->getJson("/api/unsubscribe/{$validToken}");

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'Token not found.',
            ]);
    }
}
