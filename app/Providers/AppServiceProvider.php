<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Residence;
use App\Observers\ResidenceObserver;

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
        // Enregistrement de l'Observer pour la traduction automatique
        Residence::observe(ResidenceObserver::class);
    }
}
