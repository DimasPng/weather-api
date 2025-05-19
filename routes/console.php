<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('hourly-update-command')->hourly();
Schedule::command('daily-update-command')->daily()->dailyAt('9:00');
