<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule daily automated rotation and cleanup of old log files
Schedule::command('monitoring:clean-logs')->dailyAt('00:00')->name('clean-old-logs');
