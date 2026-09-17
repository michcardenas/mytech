<?php

use App\Models\Page;
use App\Models\Seo;
use Illuminate\Database\Migrations\Migration;

/**
 * Mejora el <title> y meta description del blog "¿Cuánto cuesta una agencia
 * de software en Colombia?" (creado a mano en el admin, no en un seeder) para
 * subir su CTR: 3.490 impresiones, 22 clics (~0,7% CTR) en posición ~6.
 *
 * Corrección de datos de una sola vez. Guardada por si el blog no existe en
 * el entorno (p. ej. una BD de pruebas recién migrada).
 */
return new class extends Migration
{
    private string $slug = 'cuanto-cuesta-una-agencia-de-software-en-colombia-2026';

    private string $newTitle = 'Cuánto cuesta una agencia de software en Colombia: precios reales 2026';

    private string $newDescription = 'Precios reales 2026 de una agencia de software en Colombia: los 3 niveles de proyecto, qué incluye cada uno y agencia vs. freelancer, para no pagar de más.';

    private string $oldTitle = '¿Cuánto cuesta una agencia de software en Colombia? Precios 2026';

    private string $oldDescription = 'Precios reales 2026 para contratar una agencia de software en Colombia: rangos por proyecto, agencia vs. freelancer y cómo no pagar de más.';

    public function up(): void
    {
        $this->applySeo($this->newTitle, $this->newDescription);
    }

    public function down(): void
    {
        $this->applySeo($this->oldTitle, $this->oldDescription);
    }

    private function applySeo(string $title, string $description): void
    {
        $page = Page::where('slug', $this->slug)->first();

        if ($page === null) {
            return;
        }

        Seo::where('page_id', $page->id)->update([
            'meta_title' => $title,
            'meta_description' => $description,
        ]);
    }
};
