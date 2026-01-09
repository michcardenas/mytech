<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Servir el sitemap.xml
     *
     * Este método intenta servir el sitemap existente.
     * Si no existe o está desactualizado, lo regenera automáticamente.
     */
    public function index()
    {
        $sitemapPath = public_path('sitemap.xml');

        // Si el sitemap no existe o tiene más de 1 día, regenerarlo
        if (!File::exists($sitemapPath) || File::lastModified($sitemapPath) < now()->subDay()->timestamp) {
            Artisan::call('sitemap:generate');
        }

        // Verificar si el sitemap existe después de intentar generarlo
        if (!File::exists($sitemapPath)) {
            abort(404, 'Sitemap no disponible');
        }

        // Servir el archivo XML
        $content = File::get($sitemapPath);

        return response($content, 200)
            ->header('Content-Type', 'text/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache de 1 hora
    }

    /**
     * Generar el archivo robots.txt dinámicamente
     */
    public function robots()
    {
        $baseUrl = preg_replace('/^(https?:\/\/)www\./', '$1', config('app.url'));

        // Usar cache para evitar generar el robots.txt en cada request
        $content = Cache::remember('robots_txt', now()->addDay(), function () use ($baseUrl) {
            $robotsTxt = "# Robots.txt generado automáticamente\n";
            $robotsTxt .= "# Fecha: " . now()->toDateString() . "\n\n";

            $robotsTxt .= "User-agent: *\n";
            $robotsTxt .= "Allow: /\n\n";

            // Bloquear rutas administrativas y de autenticación
            $robotsTxt .= "# Áreas administrativas\n";
            $robotsTxt .= "Disallow: /admin/\n";
            $robotsTxt .= "Disallow: /admin\n";
            $robotsTxt .= "Disallow: /dashboard/\n";
            $robotsTxt .= "Disallow: /dashboard\n\n";

            // Bloquear rutas de autenticación
            $robotsTxt .= "# Autenticación\n";
            $robotsTxt .= "Disallow: /login\n";
            $robotsTxt .= "Disallow: /register\n";
            $robotsTxt .= "Disallow: /password/\n";
            $robotsTxt .= "Disallow: /logout\n\n";

            // Bloquear archivos y carpetas comunes
            $robotsTxt .= "# Archivos del sistema\n";
            $robotsTxt .= "Disallow: /storage/\n";
            $robotsTxt .= "Disallow: /.env\n";
            $robotsTxt .= "Disallow: /vendor/\n\n";

            // Agregar sitemap
            $robotsTxt .= "# Sitemap\n";
            $robotsTxt .= "Sitemap: {$baseUrl}/sitemap.xml\n";

            return $robotsTxt;
        });

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400'); // Cache de 24 horas
    }
}
