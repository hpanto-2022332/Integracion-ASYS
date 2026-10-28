<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // ⬅️ 1. IMPORTAR ESTA LÍNEA

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
        // 🛡️ INFRAESTRUCTURA AVANTA: Forzar renderizado HTML limpio de paginación
        Paginator::useBootstrapFive();
    }
}
