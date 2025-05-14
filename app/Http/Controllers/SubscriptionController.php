<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Mail\ConfirmSubscription;
use App\Mail\UnsubscribeNotification;
use App\Repositories\SubscriptionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController
{
    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptionRepository
    ) {}

    public function subscribe(SubscribeRequest $request): JsonResponse
    {
        $existing = $this->subscriptionRepository->findConfirmedByEmailAndCity($request['email'], $request['city']);

        if ($existing) {
            return response()->json([
                'message' => 'You are already subscribed to this city.',
            ], Response::HTTP_CONFLICT);
        }

        $confirmToken = Str::uuid()->toString();
        $unsubscribeToken = Str::uuid()->toString();

        $subscription = $this->subscriptionRepository->create([
            'email' => $request['email'],
            'city' => $request['city'],
            'frequency' => $request['frequency'],
            'confirm_token' => $confirmToken,
            'unsubscribe_token' => $unsubscribeToken,
            'confirmed' => false,
        ]);

        try {
            Mail::to($subscription->email)->queue(new ConfirmSubscription($subscription));
        } catch (\Throwable $e) {
            Log::error('Mail send failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Failed to send confirmation email.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Subscription pending confirmation.',
        ], Response::HTTP_OK);
    }

    public function confirm(string $token): JsonResponse
    {
        $subscription = $this->subscriptionRepository->findByConfirmToken($token);

        if (! $subscription) {
            return response()->json([
                'message' => 'Invalid or expired token.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->subscriptionRepository->update($subscription, [
            'confirmed' => true,
            'confirm_token' => null,
        ]);

        return response()->json([
            'message' => 'Subscription confirmed.',
        ], Response::HTTP_OK);
    }

    public function unsubscribe(string $token): JsonResponse
    {
        $subscription = $this->subscriptionRepository->findByUnsubscribeToken($token);

        if (! $subscription) {
            return response()->json([
                'message' => 'Invalid or expired token.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->subscriptionRepository->update($subscription, ['confirmed' => false]);

        try {
            Mail::to($subscription->email)->queue(new UnsubscribeNotification($subscription));
        } catch (\Throwable $e) {
            Log::error('Unsubscribe notification failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Unsubscribed successfully.',
        ], Response::HTTP_OK);
    }
}
