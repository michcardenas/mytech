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
     * Generar el archivo robots.txt dinámicamente optimizado para SEO
     */
    public function robots()
    {
        $baseUrl = preg_replace('/^(https?:\/\/)www\./', '$1', config('app.url'));

        // Usar cache para evitar generar el robots.txt en cada request
        $content = Cache::remember('robots_txt', now()->addDay(), function () use ($baseUrl) {
            $robotsTxt = "# robots.txt para " . config('app.name') . "\n";
            $robotsTxt .= "# Generado automáticamente el " . now()->format('Y-m-d') . "\n";
            $robotsTxt .= "# Sitio web: {$baseUrl}\n\n";

            // ========================================
            // GOOGLE BOT - Configuración específica
            // ========================================
            $robotsTxt .= "# Googlebot\n";
            $robotsTxt .= "User-agent: Googlebot\n";
            $robotsTxt .= "Allow: /\n";
            $robotsTxt .= "Allow: /proyectos/\n";
            $robotsTxt .= "Allow: /servicios\n";
            $robotsTxt .= "Allow: /sobre-nosotros\n";
            $robotsTxt .= "Allow: /contacto\n";
            $robotsTxt .= "Disallow: /admin/\n";
            $robotsTxt .= "Disallow: /dashboard/\n";
            $robotsTxt .= "Disallow: /login\n";
            $robotsTxt .= "Disallow: /register\n";
            $robotsTxt .= "Disallow: /password/\n";
            $robotsTxt .= "Crawl-delay: 0\n\n";

            // ========================================
            // GOOGLEBOT IMAGES - Para indexar imágenes
            // ========================================
            $robotsTxt .= "# Googlebot Images\n";
            $robotsTxt .= "User-agent: Googlebot-Image\n";
            $robotsTxt .= "Allow: /storage/images/\n";
            $robotsTxt .= "Allow: /images/\n";
            $robotsTxt .= "Allow: /*.jpg$\n";
            $robotsTxt .= "Allow: /*.jpeg$\n";
            $robotsTxt .= "Allow: /*.png$\n";
            $robotsTxt .= "Allow: /*.webp$\n";
            $robotsTxt .= "Allow: /*.svg$\n\n";

            // ========================================
            // BINGBOT - Microsoft Bing
            // ========================================
            $robotsTxt .= "# Bingbot\n";
            $robotsTxt .= "User-agent: Bingbot\n";
            $robotsTxt .= "Allow: /\n";
            $robotsTxt .= "Crawl-delay: 1\n\n";

            // ========================================
            // OTROS BOTS IMPORTANTES
            // ========================================
            $robotsTxt .= "# Otros motores de búsqueda importantes\n";
            $robotsTxt .= "User-agent: Slurp\n"; // Yahoo
            $robotsTxt .= "User-agent: DuckDuckBot\n"; // DuckDuckGo
            $robotsTxt .= "User-agent: Baiduspider\n"; // Baidu
            $robotsTxt .= "User-agent: YandexBot\n"; // Yandex
            $robotsTxt .= "Allow: /\n";
            $robotsTxt .= "Crawl-delay: 2\n\n";

            // ========================================
            // BOTS SOCIALES - Para previews en redes
            // ========================================
            $robotsTxt .= "# Bots de redes sociales (para previews/cards)\n";
            $robotsTxt .= "User-agent: facebookexternalhit\n"; // Facebook
            $robotsTxt .= "User-agent: Twitterbot\n"; // Twitter/X
            $robotsTxt .= "User-agent: LinkedInBot\n"; // LinkedIn
            $robotsTxt .= "User-agent: WhatsApp\n"; // WhatsApp
            $robotsTxt .= "User-agent: TelegramBot\n"; // Telegram
            $robotsTxt .= "Allow: /\n\n";

            // ========================================
            // BLOQUEAR BOTS MALICIOSOS/SCRAPERS
            // ========================================
            $robotsTxt .= "# Bloquear bots maliciosos y scrapers conocidos\n";
            $robotsTxt .= "User-agent: AhrefsBot\n";
            $robotsTxt .= "User-agent: SemrushBot\n";
            $robotsTxt .= "User-agent: MJ12bot\n";
            $robotsTxt .= "User-agent: DotBot\n";
            $robotsTxt .= "User-agent: Serpstatbot\n";
            $robotsTxt .= "User-agent: Screaming Frog SEO Spider\n";
            $robotsTxt .= "User-agent: DataForSeoBot\n";
            $robotsTxt .= "Disallow: /\n\n";

            // ========================================
            // REGLAS GENERALES PARA TODOS LOS BOTS
            // ========================================
            $robotsTxt .= "# Reglas generales para todos los demás bots\n";
            $robotsTxt .= "User-agent: *\n";
            $robotsTxt .= "Allow: /\n\n";

            // ========================================
            // BLOQUEAR ÁREAS PRIVADAS
            // ========================================
            $robotsTxt .= "# Áreas administrativas (TODOS los bots)\n";
            $robotsTxt .= "Disallow: /admin/\n";
            $robotsTxt .= "Disallow: /admin\n";
            $robotsTxt .= "Disallow: /dashboard/\n";
            $robotsTxt .= "Disallow: /dashboard\n\n";

            $robotsTxt .= "# Autenticación y seguridad\n";
            $robotsTxt .= "Disallow: /login\n";
            $robotsTxt .= "Disallow: /register\n";
            $robotsTxt .= "Disallow: /logout\n";
            $robotsTxt .= "Disallow: /password/\n";
            $robotsTxt .= "Disallow: /password-reset/\n";
            $robotsTxt .= "Disallow: /forgot-password\n";
            $robotsTxt .= "Disallow: /reset-password/\n";
            $robotsTxt .= "Disallow: /email/verify/\n\n";

            $robotsTxt .= "# APIs y endpoints internos\n";
            $robotsTxt .= "Disallow: /api/\n";
            $robotsTxt .= "Disallow: /_debugbar/\n";
            $robotsTxt .= "Disallow: /telescope/\n";
            $robotsTxt .= "Disallow: /horizon/\n\n";

            $robotsTxt .= "# Archivos y directorios del sistema\n";
            $robotsTxt .= "Disallow: /vendor/\n";
            $robotsTxt .= "Disallow: /storage/framework/\n";
            $robotsTxt .= "Disallow: /storage/logs/\n";
            $robotsTxt .= "Disallow: /.env\n";
            $robotsTxt .= "Disallow: /.git/\n";
            $robotsTxt .= "Disallow: /node_modules/\n\n";

            $robotsTxt .= "# Archivos de configuración\n";
            $robotsTxt .= "Disallow: /*.json$\n";
            $robotsTxt .= "Disallow: /*.yml$\n";
            $robotsTxt .= "Disallow: /*.yaml$\n";
            $robotsTxt .= "Disallow: /*.log$\n";
            $robotsTxt .= "Disallow: /*.sql$\n";
            $robotsTxt .= "Disallow: /*.md$\n";
            $robotsTxt .= "Allow: /sitemap.xml$\n\n";

            // ========================================
            // PERMITIR RECURSOS ESTÁTICOS
            // ========================================
            $robotsTxt .= "# Permitir acceso a recursos estáticos (CSS, JS, imágenes)\n";
            $robotsTxt .= "Allow: /css/\n";
            $robotsTxt .= "Allow: /js/\n";
            $robotsTxt .= "Allow: /build/\n";
            $robotsTxt .= "Allow: /images/\n";
            $robotsTxt .= "Allow: /storage/images/\n";
            $robotsTxt .= "Allow: /favicon.ico\n";
            $robotsTxt .= "Allow: /robots.txt\n\n";

            // ========================================
            // SITEMAPS
            // ========================================
            $robotsTxt .= "# Sitemaps XML\n";
            $robotsTxt .= "Sitemap: {$baseUrl}/sitemap.xml\n\n";

            // ========================================
            // INFORMACIÓN ADICIONAL
            // ========================================
            $robotsTxt .= "# Información de contacto\n";
            $robotsTxt .= "# Para reportar problemas de crawling: {$baseUrl}/contacto\n";

            return $robotsTxt;
        });

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400') // Cache de 24 horas
            ->header('X-Robots-Tag', 'noindex'); // El robots.txt mismo no debe indexarse
    }
}
