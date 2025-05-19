<?php

namespace App\Console\Commands;

use App\Enum\FrequencyEnum;
use App\Events\WeatherUpdatedEvent;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use App\Services\WeatherApiClient;
use Illuminate\Console\Command;

class DailyUpdateCommand extends Command
{
    protected $signature = 'daily-update-command';

    protected $description = 'Sends daily weather updates to subscribed users';

    public function handle(SubscriptionRepository $subscriptionRepository, WeatherApiClient $weatherApi): void
    {
        $cities = $subscriptionRepository->getUniqueCities();

        $cities->each(function ($city) use ($subscriptionRepository, $weatherApi) {
            $weather = $weatherApi->getCurrent($city);

            $usersByCity = $subscriptionRepository->findAllConfirmedByCity($city, FrequencyEnum::DAILY);

            $usersByCity->each(function (Subscription $user) use ($weather) {
                WeatherUpdatedEvent::dispatch($user, $weather);
            });
        });
    }
}
