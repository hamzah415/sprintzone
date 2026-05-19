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
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Google2FA', \PragmaRXGoogle2FALaravel\Facade::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('FORCE_HTTPS', false)) {       //ini harusnya true tapi di local harus false
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
