<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log authenticated GET requests, excluding admin and static routes
        if (Auth::check() && $request->isMethod('GET')) {
            $url = $request->getPathInfo();
            $user = Auth::user();

            // Skip admin, logout and trivial routes
            if (str_starts_with($url, '/admin') || $url === '/logout' || str_starts_with($url, '/livewire')) {
                return $response;
            }

            // Determine feature area
            $featureArea = 'Other';
            if (str_starts_with($url, '/loans'))
                $featureArea = 'Empréstimos';
            elseif (str_starts_with($url, '/orcamentos'))
                $featureArea = 'Limites (Budgets)';
            elseif (str_starts_with($url, '/transactions'))
                $featureArea = 'Transações';
            elseif (str_starts_with($url, '/dashboard'))
                $featureArea = 'Painel';
            elseif (str_starts_with($url, '/relatorios'))
                $featureArea = 'Relatórios';
            elseif (str_starts_with($url, '/planilhas'))
                $featureArea = 'Planilhas';

            \App\Models\ActivityLog::create([
                'user_id' => $user->id,
                'url' => $url,
                'method' => $request->method(),
                'feature_area' => $featureArea,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
