<?php

namespace App\Services;

use App\Exceptions\CityNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

class WeatherApiClient
{
    protected Client $http;

    protected string $apiKey;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => 'https://api.weatherapi.com/v1/',
        ]);
        $this->apiKey = config('services.weather.key');
    }

    public function getCurrent(string $city): array
    {
        try {
            $data = $this->fetchWeatherData($city);

            return [
                'temperature' => $data['current']['temp_c'],
                'humidity' => $data['current']['humidity'],
                'description' => $data['current']['condition']['text'],
            ];

        } catch (\Exception $e) {
            return [
                'error' => 'Unexpected error: '.$e->getMessage(),
                'code' => Response::HTTP_SERVICE_UNAVAILABLE,
            ];
        }
    }

    /**
     * @throws CityNotFoundException
     */
    public function verifyCityExists(string $city): void
    {
        $this->fetchWeatherData($city);
    }

    /**
     * @throws CityNotFoundException
     */
    private function fetchWeatherData(string $city): array
    {
        try {
            $response = $this->http->get('current.json', [
                'query' => ['key' => $this->apiKey, 'q' => $city],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody()->getContents(), true);
            $message = $body['error']['message'] ?? 'Client error from weather service';

            throw new CityNotFoundException($message);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Weather service request failed: '.$e->getMessage(), $e->getCode(), $e);
        }
    }
}
