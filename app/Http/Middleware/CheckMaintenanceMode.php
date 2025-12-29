<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AppSetting;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow admins to bypass maintenance mode
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Check if maintenance mode is active
        if (AppSetting::isMaintenanceMode()) {
            return response()->view('maintenance', [
                'message' => AppSetting::get('maintenance_message', 'Estamos em manutenção. Voltaremos em breve!')
            ], 503);
        }

        return $next($request);
    }
}
