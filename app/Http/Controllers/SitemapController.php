<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Proyecto;
use App\Models\Seo;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        // Obtener URL base sin www
        $baseUrl = preg_replace('/^(https?:\/\/)www\./', '$1', config('app.url'));

        // URLs estáticas principales
        $staticUrls = [
            [
                'loc' => $baseUrl,
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '1.0'
            ],
            [
                'loc' => $baseUrl . '/servicios',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => $baseUrl . '/proyectos',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => $baseUrl . '/sobre-nosotros',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'loc' => $baseUrl . '/contacto',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ]
        ];

        // URLs dinámicas de proyectos
        $proyectos = Proyecto::activos()->get();
        $projectUrls = [];

        foreach ($proyectos as $proyecto) {
            $projectUrls[] = [
                'loc' => $baseUrl . '/proyectos/' . $proyecto->slug,
                'lastmod' => $proyecto->updated_at->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ];
        }

        // Combinar todas las URLs
        $urls = array_merge($staticUrls, $projectUrls);

        // Generar XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($url['loc']) . '</loc>';
            $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
            $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $url['priority'] . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $baseUrl = preg_replace('/^(https?:\/\/)www\./', '$1', config('app.url'));

        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /dashboard\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "\n";
        $content .= "Sitemap: {$baseUrl}/sitemap.xml\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
