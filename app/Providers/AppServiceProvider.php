<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;  // ← AGREGAR ESTA LÍNEA
use App\View\Composers\NavbarComposer;
use App\Models\Category;
use App\Models\Proyecto;
use App\Observers\ProyectoObserver;

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
        // Registrar el observer de Proyecto para regenerar sitemap automáticamente
        Proyecto::observe(ProyectoObserver::class);

        //
        Paginator::useBootstrapFive();
    //      View::composer(['layouts.app', 'layouts.app_admin'], NavbarComposer::class);

    //      View::share('categories', Category::orderBy('name')->get());


    }
}
