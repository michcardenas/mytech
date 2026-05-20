<?php

namespace App\Support;

/**
 * Helpers para renderizar tarjetas de proyectos en el home.
 * Extraído del Blade `partials/home/casos-produccion.blade.php` para que sea
 * reusable, testeable, y no contamine la vista.
 */
class ProjectCardHelper
{
    /**
     * Saca el dominio de una URL, sin "www." al inicio.
     * Devuelve null si la URL es vacía/inválida.
     */
    public static function domain(?string $url): ?string
    {
        if (! $url) {
            return null;
        }
        $host = parse_url($url, PHP_URL_HOST);
        return $host ? preg_replace('/^www\./i', '', $host) : null;
    }

    /**
     * Recorta una descripción a `$limit` caracteres, agregando "…" si excede.
     * Strip-tags y trim para asegurar texto plano.
     */
    public static function shortDesc(?string $txt, int $limit = 130): string
    {
        $t = trim(strip_tags($txt ?? ''));
        if (mb_strlen($t) <= $limit) {
            return $t;
        }
        return rtrim(mb_substr($t, 0, $limit)) . '…';
    }

    /**
     * Resuelve la URL final de un logo aceptando:
     *  - URL absoluta (http/https)
     *  - path desde la raíz pública ("/img/foo.png")
     *  - path relativo en disco "public" (Laravel Storage)
     */
    public static function logoUrl(?string $logo): ?string
    {
        if (! $logo) {
            return null;
        }
        if (preg_match('/^https?:\/\//i', $logo)) {
            return $logo;
        }
        if (str_starts_with($logo, '/')) {
            return asset(ltrim($logo, '/'));
        }
        return asset('storage/' . $logo);
    }
}
