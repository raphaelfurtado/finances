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
        // Fix for OpenSSL on Windows/Laragon for WebPush
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $opensslPaths = [
                'C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\extras\ssl\openssl.cnf',
                'C:\laragon\bin\apache\httpd-2.4.54-win64-VS16\conf\openssl.cnf',
                'C:\laragon\bin\php\extras\ssl\openssl.cnf',
            ];
            foreach ($opensslPaths as $path) {
                if (file_exists($path)) {
                    $path = str_replace('\\', '/', $path);
                    putenv("OPENSSL_CONF=$path");
                    $_ENV['OPENSSL_CONF'] = $path;
                    $_SERVER['OPENSSL_CONF'] = $path;
                    break;
                }
            }
        }
    }
}
