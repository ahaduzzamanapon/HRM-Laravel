<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
