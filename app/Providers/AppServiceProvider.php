<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            app()->environment('production') ||
            env('APP_ENV') === 'production' ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'vercel.app'))
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Auth::provider('cached-eloquent', function ($app, array $config) {
            return new \App\Auth\CachedEloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
