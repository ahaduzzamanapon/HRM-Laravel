<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        // Fix invalid CA file path in Windows/XAMPP environment
        $caFile = ini_get('openssl.cafile');
        if ($caFile && !file_exists($caFile)) {
            $realCaFile = 'G:\\xampp\\apache\\bin\\curl-ca-bundle.crt';
            if (file_exists($realCaFile)) {
                ini_set('openssl.cafile', $realCaFile);
                ini_set('curl.cainfo', $realCaFile);
            } else {
                ini_set('openssl.cafile', '');
            }
        }
    }
}
