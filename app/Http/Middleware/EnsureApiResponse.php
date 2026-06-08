<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureApiResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Mark this as an API request expecting JSON
        $request->headers->set('Accept', 'application/json');

        try {
            $response = $next($request);

            // Ensure response is JSON for API routes
            if (!$response->headers->has('Content-Type')) {
                $response->headers->set('Content-Type', 'application/json');
            }

            return $response;
        } catch (\Throwable $e) {
            // Log the error
            Log::error('API Error: ' . $e->getMessage(), [
                'path' => $request->path(),
                'method' => $request->method(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return JSON error response instead of HTML
            return response()->json([
                'success' => false,
                'message' => app()->environment('production')
                    ? 'Internal Server Error'
                    : $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
