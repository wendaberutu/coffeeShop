<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Menangani preflight request (OPTIONS)
        if ($request->getMethod() == "OPTIONS") {
            return response()->json([], Response::HTTP_NO_CONTENT);
        }

        // Mengatur header CORS
        $response->headers->set('Access-Control-Allow-Origin', 'http://frontend-domain.com'); // Ganti dengan domain frontend Anda
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Untuk mendukung cookies atau token

        return $response;
    }
}
