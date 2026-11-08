<?php

use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app' => 'Laravel CI/CD Docker API',
        'status' => 'online',
        'version' => env('APP_VERSION', '1.0.0'),
    ]);
});

Route::get('/health', [HealthController::class, 'check']);
