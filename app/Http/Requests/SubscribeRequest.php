<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class SubscribeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email:rfc,dns|unique:subscriptions,email',
            'city' => 'required|string|max:255',
            'frequency' => 'required|string|in:hourly,daily',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email already subscribed.',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();

        if ($this->wantsJson()) {
            if ($errors->has('email') && in_array('Email already subscribed.', $errors->get('email'))) {
                throw new HttpResponseException(response()->json([
                    'message' => 'Email already subscribed.',
                    'errors' => $errors->toArray(),
                ], Response::HTTP_CONFLICT));
            }

            throw new HttpResponseException(response()->json([
                'message' => 'Invalid input.',
                'errors' => $errors->toArray(),
            ], Response::HTTP_BAD_REQUEST));
        }
    }
}
