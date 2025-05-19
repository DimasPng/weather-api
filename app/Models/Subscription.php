<?php

namespace App\Models;

use App\Enum\FrequencyEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property string $city
 * @property FrequencyEnum $frequency
 * @property bool $confirmed
 * @property string|null $confirmation_token
 * @property string|null $unsubscribe_token
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
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'frequency' => FrequencyEnum::class,
    ];
}
