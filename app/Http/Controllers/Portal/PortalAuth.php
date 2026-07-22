<?php

namespace App\Http\Controllers\Portal;

trait PortalAuth
{
    /**
     * Normaliza un número telefónico a solo dígitos. NO recorta código de país
     * para soportar clientes internacionales (LATAM, España, Australia, US, etc.).
     * El matching se hace después con phonesMatch().
     */
    protected function normalizePhone(?string $phone): string
    {
        if (! $phone) {
            return '';
        }

        return preg_replace('/\D+/', '', $phone);
    }

    /**
     * Compara dos teléfonos siendo tolerante con códigos de país.
     * Coinciden si:
     *  - son idénticos tras normalizar; o
     *  - uno es sufijo del otro (ej: "3102334308" ⊂ "573102334308"); o
     *  - los últimos 8 dígitos coinciden (número local sin código de país).
     */
    protected function phonesMatch(string $a, string $b): bool
    {
        $a = $this->normalizePhone($a);
        $b = $this->normalizePhone($b);
        if ($a === '' || $b === '') {
            return false;
        }
        if ($a === $b) {
            return true;
        }
        if (str_ends_with($a, $b) || str_ends_with($b, $a)) {
            return true;
        }

        return strlen($a) >= 8 && strlen($b) >= 8 && substr($a, -8) === substr($b, -8);
    }

    /**
     * Anti-fuerza bruta: 5 intentos por sesión en 10 minutos.
     */
    protected function tooManyAttempts(\Illuminate\Http\Request $request, string $key): bool
    {
        $now = time();
        $attempts = $request->session()->get('portal_attempts.'.$key, []);
        $attempts = array_filter($attempts, fn ($t) => $t > $now - 600);
        $request->session()->put('portal_attempts.'.$key, $attempts);

        return count($attempts) >= 5;
    }

    protected function recordAttempt(\Illuminate\Http\Request $request, string $key): void
    {
        $attempts = $request->session()->get('portal_attempts.'.$key, []);
        $attempts[] = time();
        $request->session()->put('portal_attempts.'.$key, $attempts);
    }

    protected function clearAttempts(\Illuminate\Http\Request $request, string $key): void
    {
        $request->session()->forget('portal_attempts.'.$key);
    }
}
