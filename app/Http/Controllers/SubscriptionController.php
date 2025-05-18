<?php

namespace App\Http\Controllers;

use App\Exceptions\CityNotFoundException;
use App\Exceptions\SubscriptionNotFoundException;
use App\Http\Requests\SubscribeRequest;
use App\Services\SubscriptionService;
use App\ValueObject\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class SubscriptionController
{
    public function __construct(
        private SubscriptionService $subscriptionService,
    ) {}

    public function subscribe(SubscribeRequest $request): JsonResponse
    {
        try {
            $this->subscriptionService->create($request->validated());

            return response()->json([
                'message' => 'Subscription successful. Confirmation email sent.',
            ], Response::HTTP_OK);
        } catch (CityNotFoundException $e) {
            return response()->json([
                'message' => 'Invalid input.',
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            Log::error('Subscription failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An unexpected error occurred.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function confirm(string $token): JsonResponse
    {
        try {
            $tkn = Token::fromString($token);
            $this->subscriptionService->confirm($tkn);

            return response()->json(['message' => 'Subscription confirmed successfully.'], Response::HTTP_OK);
        } catch (InvalidArgumentException) {
            return response()->json(['message' => 'Invalid token.'], Response::HTTP_BAD_REQUEST);
        } catch (SubscriptionNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function unsubscribe(string $token): JsonResponse
    {
        try {
            $tkn = Token::fromString($token);
            $this->subscriptionService->unsubscribe($tkn);

            return response()->json(['message' => 'Unsubscribed successfully.'], Response::HTTP_OK);
        } catch (InvalidArgumentException) {
            return response()->json(['message' => 'Invalid token.'], Response::HTTP_BAD_REQUEST);
        } catch (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
