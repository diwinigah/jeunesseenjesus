<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // X-Content-Type-Options: prevent MIME type sniffing
        $response->header('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options: prevent clickjacking
        $response->header('X-Frame-Options', 'DENY');

        // X-XSS-Protection: enable XSS filtering
        $response->header('X-XSS-Protection', '1; mode=block');

        // Strict-Transport-Security: force HTTPS
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Referrer-Policy: control referrer information
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy: control feature access
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
