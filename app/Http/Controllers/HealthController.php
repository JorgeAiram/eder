<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    /**
     * Check application and database connectivity.
     */
    public function check(): JsonResponse
    {
        $dbStatus = 'unknown';
        $dbLatencyMs = null;
        $healthy = true;
        $error = null;

        $startTime = microtime(true);
        try {
            DB::connection()->getPdo();
            DB::select('SELECT 1');
            $dbLatencyMs = round((microtime(true) - $startTime) * 1000, 2);
            $dbStatus = 'connected';
        } catch (\Throwable $e) {
            $healthy = false;
            $dbStatus = 'disconnected';
            $error = $e->getMessage();
        }

        $payload = [
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'version' => env('APP_VERSION', '1.0.0'),
            'timestamp' => now()->toIso8601String(),
            'services' => [
                'api' => [
                    'status' => 'operational',
                    'php_version' => PHP_VERSION,
                ],
                'database' => [
                    'status' => $dbStatus,
                    'connection' => config('database.default'),
                    'database' => config('database.connections.mysql.database'),
                    'latency_ms' => $dbLatencyMs,
                    'error' => $error,
                ],
            ],
        ];

        return response()->json($payload, $healthy ? 200 : 503);
    }
}
