<?php

namespace App\Http\Controllers;

use App\Exceptions\CityNotFoundException;
use App\Http\Requests\GetWeatherRequest;
use App\Services\WeatherApiClient;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends Controller
{
    public function __construct(private readonly WeatherApiClient $weatherClient) {}

    public function getWeather(GetWeatherRequest $request): JsonResponse
    {
        try {
            $data = $this->weatherClient->getCurrent($request['city']);

            return response()->json($data);
        } catch (CityNotFoundException $e) {
            return response()->json(['message' => 'City not found.'], Response::HTTP_NOT_FOUND);
        }
    }
}
