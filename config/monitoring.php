<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Slow Request Threshold (milliseconds)
    |--------------------------------------------------------------------------
    | Any request exceeding this duration triggers a slow request log / alert.
    */
    'slow_request_threshold_ms' => (int) env('MONITORING_SLOW_THRESHOLD_MS', 500),

    /*
    |--------------------------------------------------------------------------
    | Recurring Error Alert Threshold
    |--------------------------------------------------------------------------
    | Number of identical error occurrences within the window to trigger alert.
    */
    'recurring_error_threshold' => (int) env('MONITORING_ERROR_ALERT_THRESHOLD', 3),

    /*
    |--------------------------------------------------------------------------
    | Recurring Error Time Window (minutes)
    |--------------------------------------------------------------------------
    */
    'recurring_error_window_minutes' => (int) env('MONITORING_ERROR_WINDOW_MINUTES', 5),

    /*
    |--------------------------------------------------------------------------
    | Log Retention (days)
    |--------------------------------------------------------------------------
    | Older logs are automatically rotated / deleted.
    */
    'retention_days' => (int) env('MONITORING_LOG_RETENTION_DAYS', 14),

    /*
    |--------------------------------------------------------------------------
    | Sensitive Fields To Mask In Logs
    |--------------------------------------------------------------------------
    */
    'sensitive_fields' => [
        'password',
        'password_confirmation',
        'token',
        'authorization',
        'secret',
        'access_token',
        'refresh_token',
        'api_key',
        'credit_card',
        'cvv',
    ],
];
