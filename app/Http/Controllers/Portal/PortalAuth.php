<?php

namespace App\Http\Controllers\Portal;

trait PortalAuth
{
    /**
     * Normaliza un número telefónico: solo dígitos, sin prefijo país +57.
     */
    protected function normalizePhone(?string $phone): string
    {
        if (!$phone) return '';
        $digits = preg_replace('/\D+/', '', $phone);
        if (strlen($digits) === 12 && str_starts_with($digits, '57')) {
            $digits = substr($digits, 2);
        }
        if (strlen($digits) === 13 && str_starts_with($digits, '057')) {
            $digits = substr($digits, 3);
        }
        return $digits;
    }

    /**
     * Anti-fuerza bruta: 5 intentos por sesión en 10 minutos.
     */
    protected function tooManyAttempts(\Illuminate\Http\Request $request, string $key): bool
    {
        $now = time();
        $attempts = $request->session()->get('portal_attempts.' . $key, []);
        $attempts = array_filter($attempts, fn ($t) => $t > $now - 600);
        $request->session()->put('portal_attempts.' . $key, $attempts);
        return count($attempts) >= 5;
    }

    protected function recordAttempt(\Illuminate\Http\Request $request, string $key): void
    {
        $attempts = $request->session()->get('portal_attempts.' . $key, []);
        $attempts[] = time();
        $request->session()->put('portal_attempts.' . $key, $attempts);
    }

    protected function clearAttempts(\Illuminate\Http\Request $request, string $key): void
    {
        $request->session()->forget('portal_attempts.' . $key);
    }
}
