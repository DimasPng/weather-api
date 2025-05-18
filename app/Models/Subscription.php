<?php

namespace App\Models;

use App\Enum\FrequencyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $email
 * @property string $city
 * @property FrequencyEnum $frequency
 * @property bool $confirmed
 * @property string|null $confirmation_token
 * @property string|null $unsubscribe_token
 * @property Carbon|null $last_sent_at
 */
class Subscription extends Model
{
    protected $fillable = [
        'email',
        'city',
        'frequency',
        'confirmed',
        'confirmation_token',
        'unsubscribe_token',
        'last_sent_at',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'frequency' => FrequencyEnum::class,
        'last_sent_at' => 'datetime',
    ];
}
