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
        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Auto-create storage folders for "Zero Config"
        if (!file_exists(storage_path('app/public/signatures'))) {
            @mkdir(storage_path('app/public/signatures'), 0755, true);
        }
        if (!file_exists(storage_path('app/public/delivery_proofs'))) {
            @mkdir(storage_path('app/public/delivery_proofs'), 0755, true);
        }
    }
}
