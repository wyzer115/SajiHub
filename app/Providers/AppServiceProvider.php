<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;

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
        Paginator::useTailwind();

        // 1. Force HTTPS on production or when accessed via secure connection / proxy
        if ($this->shouldForceHttps()) {
            URL::forceScheme('https');
        }

        // 2. Prevent Vite 'hot' file hijacking on hosted production server
        $this->configureViteEnvironment();
    }

    protected function shouldForceHttps(): bool
    {
        return request()->isSecure()
            || (!app()->environment('local') && env('APP_ENV') !== 'local')
            || str_starts_with(config('app.url', ''), 'https://')
            || (request()->server('HTTP_X_FORWARDED_PROTO') === 'https')
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
    }

    protected function configureViteEnvironment(): void
    {
        $host = request()->getHost();
        $isLocal = in_array($host, ['localhost', '127.0.0.1', '::1'])
            || str_ends_with($host, '.test')
            || str_ends_with($host, '.local');

        // If hosted on live domain or on production environment
        if (!$isLocal || app()->isProduction() || env('APP_ENV') === 'production') {
            // Prevent Vite from trying to connect to local dev server (port 5173)
            Vite::useHotFile(storage_path('vite.hot'));

            if (file_exists(public_path('hot'))) {
                @unlink(public_path('hot'));
            }
        }
    }
}
