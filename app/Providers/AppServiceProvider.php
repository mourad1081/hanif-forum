<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Blade;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // On créé un template custom -- un if pour les admin et vip
        Blade::if('restrictedArea', function ($nameZone) {
            switch ($nameZone) {
                case "admin": return  Auth::check() and Auth::user()->isAdmin();
                case "vip":   return  Auth::check() and Auth::user()->isAtLeastVIP();
                case "guest": return !Auth::check();
                default: return false;
            }
        });

        // on met l'heure en FR avec carbon
        setlocale (LC_TIME, 'fr_FR.utf8');
        Carbon::setLocale('fr');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
