<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $email
 * @property string $city
 * @property string $frequency
 * @property bool $confirmed
 * @property string $confirm_token
 * @property string $unsubscribe_token
 * @property Carbon|null $last_sent_at
 */
class Subscription extends Model
{
    protected $fillable = [
        'email',
        'city',
        'frequency',
        'confirmed',
        'confirm_token',
        'unsubscribe_token',
        'last_sent_at',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'last_sent_at' => 'datetime',
    ];
}
