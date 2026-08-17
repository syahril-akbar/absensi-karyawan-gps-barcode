<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * When the application is behind a Cloudflare tunnel, cloudflared forwards
     * the origin connection over plain HTTP and sets X-Forwarded-Proto: http,
     * even though the client reached Cloudflare over HTTPS (CF-Visitor header).
     * This makes Laravel treat the request as insecure, so signed URLs are
     * generated with http:// and signature validation fails after the browser
     * is redirected to https://.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cfVisitor = $request->headers->get('CF-Visitor');

        if ($cfVisitor && str_contains($cfVisitor, '"https"')) {
            $request->headers->set('X-Forwarded-Proto', 'https');
        }

        return $next($request);
    }
}
