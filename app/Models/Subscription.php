<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
