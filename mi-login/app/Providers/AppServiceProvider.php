<?php

namespace App\Providers;

use App\Support\SeguroMock;
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
        // Con SEGURO_API_MOCK=true, las llamadas a la API se responden con datos simulados.
        if (config('services.seguro.mock')) {
            SeguroMock::register();
        }
    }
}
