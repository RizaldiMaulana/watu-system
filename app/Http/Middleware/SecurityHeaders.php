<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent invalid MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent the page from being framed (clickjacking protection)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Enable XSS filtering in browsers (usually default, but good to be explicit)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Enforce HTTPS (HSTS) - Enable this only if you have SSL
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Content Security Policy (Basic starting point)
        // Be careful with this, as it can break styles/scripts if strict. 
        // We'll set a permissive one for now to block mixed content at least.
        $response->headers->set('Content-Security-Policy', "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data:;");

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy (Limit features)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
