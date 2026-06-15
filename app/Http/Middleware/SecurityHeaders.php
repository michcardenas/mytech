<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inyecta headers de seguridad en todas las respuestas HTTP.
 *
 * Cubre:
 *  - HSTS: fuerza HTTPS por 1 año en navegadores que ya visitaron el sitio
 *  - X-Frame-Options: previene clickjacking (no permite iframe externo)
 *  - X-Content-Type-Options: bloquea MIME sniffing
 *  - Referrer-Policy: limita info de referrer a sitios externos
 *  - Permissions-Policy: desactiva APIs sensibles del navegador no usadas
 *  - Cross-Origin-Opener-Policy: aislamiento del origen
 *
 * NO incluye CSP por ahora porque rompería GTM + Facebook Pixel + Google Fonts.
 * Si querés CSP estricto agregalo en una fase 2 con report-only primero.
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // HSTS solo en HTTPS — incluye subdominios + preload candidate
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Anti clickjacking — permite iframes del mismo origen (formularios admin)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Bloquea MIME sniffing (forzá Content-Type declarado)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer info: env completo internamente, sólo origen para externos HTTPS, nada para HTTP downgrade
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Apaga APIs sensibles que no usamos (mejor: lista explícita de lo permitido)
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(self), payment=(), usb=(), interest-cohort=()'
        );

        // X-XSS-Protection: deprecado en navegadores modernos pero algunos auditores aún lo chequean
        $response->headers->set('X-XSS-Protection', '0');

        // Aislamiento básico — same-origin permite que la página acceda a su propio context
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // Remover headers que exponen tecnología (footprint reduction)
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Cache-Control para HTML público (GET 200): permite cache pública de 5 min en CDN/proxies.
        // Páginas autenticadas (admin, dashboard) o non-GET mantienen no-cache.
        if (
            $request->isMethod('GET')
            && $response->getStatusCode() === 200
            && ! $request->is('admin/*', 'dashboard*', 'portal/*', 'pages/*')
            && ! $request->user()
            && ! $response->headers->has('Cache-Control-Locked')
        ) {
            $ct = $response->headers->get('Content-Type', '');
            if (str_contains($ct, 'text/html')) {
                $response->headers->set('Cache-Control', 'public, max-age=300, s-maxage=600, stale-while-revalidate=86400');
            }
        }

        return $response;
    }
}
