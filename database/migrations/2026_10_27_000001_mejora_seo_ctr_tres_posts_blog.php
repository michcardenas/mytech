<?php

use App\Models\Page;
use App\Models\Seo;
use Illuminate\Database\Migrations\Migration;

/**
 * Mejora <title> y meta description de los 3 posts del blog con más
 * impresiones en Search Console, para subir su CTR (que está muy bajo porque
 * aparecen en posiciones 6-11):
 *
 *   - facturacion-electronica-dian-integrar-software  (1.011 impr, pos 10,7)
 *   - cuanto-cuesta-chatbot-ia-whatsapp               (638 impr, pos 7,3, CTR 0,3%)
 *   - cuanto-cuesta-una-agencia-de-software-...-2026   (~4k impr, pos 6; solo acorta
 *                                                        el title para que no se trunque)
 *
 * Ajustes: títulos < 65 car. (para que no los corte Google), descripciones
 * < 160 car. con un gancho de beneficio, y se quita la marca "| MY Tech" y el
 * Title Case del post de chatbot.
 *
 * Solo toca meta_title y meta_description (lo que se ve en el SERP); OG/Twitter
 * se dejan como están. Corrección de datos de una sola vez, reversible e
 * idempotente: si un post no existe en el entorno, se omite.
 */
return new class extends Migration
{
    /** @var array<string, array{new_title:string,new_description:string,old_title:string,old_description:string}> */
    private array $posts = [
        'facturacion-electronica-dian-integrar-software' => [
            'new_title' => 'Integrar facturación electrónica DIAN a tu ERP o software (2026)',
            'new_description' => 'Integra la facturación electrónica DIAN a tu ERP, CRM o e-commerce y automatiza venta, validación y PDF. Opciones, costos y un plan por fases.',
            'old_title' => 'Integrar facturación electrónica DIAN a tu ERP o software propio (2026)',
            'old_description' => 'Cómo integrar la facturación electrónica DIAN a tu ERP, CRM o e-commerce: opciones (DIAN gratis vs. proveedor), costos y un plan por fases.',
        ],
        'cuanto-cuesta-chatbot-ia-whatsapp' => [
            'new_title' => 'Cuánto cuesta un chatbot con IA para WhatsApp (precios 2026)',
            'new_description' => '¿Cuánto cuesta un chatbot con IA para WhatsApp en 2026? Rangos reales y qué define el precio: bot de botones vs. asistente que entiende, cobra y agenda 24/7.',
            'old_title' => 'Cuánto Cuesta un Chatbot con IA para WhatsApp (2026) | MY Tech',
            'old_description' => '¿Cuánto cuesta un chatbot con IA para WhatsApp en 2026? Rangos de precio reales, qué determina el costo y bot genérico vs asistente a la medida que cobra y agenda.',
        ],
        'cuanto-cuesta-una-agencia-de-software-en-colombia-2026' => [
            'new_title' => 'Cuánto cuesta una agencia de software en Colombia (precios 2026)',
            'new_description' => 'Precios reales 2026 de una agencia de software en Colombia: los 3 niveles de proyecto, qué incluye cada uno y agencia vs. freelancer, para no pagar de más.',
            'old_title' => 'Cuánto cuesta una agencia de software en Colombia: precios reales 2026',
            'old_description' => 'Precios reales 2026 de una agencia de software en Colombia: los 3 niveles de proyecto, qué incluye cada uno y agencia vs. freelancer, para no pagar de más.',
        ],
    ];

    public function up(): void
    {
        foreach ($this->posts as $slug => $v) {
            $this->applySeo($slug, $v['new_title'], $v['new_description']);
        }
    }

    public function down(): void
    {
        foreach ($this->posts as $slug => $v) {
            $this->applySeo($slug, $v['old_title'], $v['old_description']);
        }
    }

    private function applySeo(string $slug, string $title, string $description): void
    {
        $page = Page::where('slug', $slug)->first();

        if ($page === null) {
            return;
        }

        Seo::where('page_id', $page->id)->update([
            'meta_title' => $title,
            'meta_description' => $description,
        ]);
    }
};
