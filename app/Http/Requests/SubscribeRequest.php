<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('subscriptions')->where(function ($query) {
                    return $query->where('city', $this->input('city'));
                }),
            ],
            'city' => 'required|string|max:255',
            'frequency' => 'required|string|in:hourly,daily',
        ];
    }
}
