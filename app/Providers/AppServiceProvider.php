<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Suppress deprecations in CLI output for cleaner terminal display on PHP 8.5
        error_reporting(E_ALL & ~E_DEPRECATED);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define Gates for authorization
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('view-monitoring', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
