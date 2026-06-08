<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If X-Forwarded-Proto header says https, make sure the request knows it's HTTPS
        if ($request->header('X-Forwarded-Proto') === 'https') {
            $_SERVER['HTTPS'] = 'on';
            $_SERVER['REQUEST_SCHEME'] = 'https';
        }

        return $next($request);
    }
}
