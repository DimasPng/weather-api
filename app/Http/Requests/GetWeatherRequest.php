<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class GetWeatherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'city.required' => 'City is required.',
            'city.string' => 'City must be a string.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Invalid request.',
            'errors' => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST));
    }
}
