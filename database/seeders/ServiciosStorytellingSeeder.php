<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeder one-off — agrega los 31 campos nuevos del storytelling de /servicios
 * al JSON content de la página slug='servicios' (id=3).
 *
 * PRESERVA todos los valores existentes — solo agrega keys que no existen.
 *
 * Run: php artisan db:seed --class=ServiciosStorytellingSeeder
 */
class ServiciosStorytellingSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('slug', 'servicios')->first();
        if (! $page) {
            $this->command->error('Page slug=servicios no encontrada. Abortando.');
            return;
        }

        $content = json_decode($page->content ?? '{}', true) ?: [];

        $defaults = [
            // ───── HEADER de página /servicios ─────
            'serv_hero_badge'        => 'Servicios',
            'serv_hero_title'        => 'Construimos el software que tu empresa necesita.',
            'serv_hero_description'  => 'Desde plataformas web a medida hasta automatización con IA. Diseñamos cada solución alrededor de tu negocio, no al revés.',
            'serv_hero_button_text'  => 'Cotiza tu proyecto',

            'serv_stack_eyebrow'     => 'Tecnologías',
            'serv_stack_title'       => 'El stack con el que trabajamos.',
            'serv_stack_subtitle'    => 'Herramientas maduras, probadas en producción en 11 países. No frameworks de moda.',

            // ───── SERVICIO 1 — Desarrollo Web ─────
            'servicio_1_lead'   => 'Plataformas a medida que escalan con tu negocio.',
            'servicio_1_image'  => '',
            'servicio_1_tags'   => 'Laravel, PHP, PostgreSQL, AWS',
            'servicio_1_precio' => 'Desde $8.000.000 COP',

            // ───── SERVICIO 2 — SaaS ─────
            'servicio_2_lead'   => 'De idea a producto SaaS listo para vender.',
            'servicio_2_image'  => '',
            'servicio_2_tags'   => 'Laravel, Vue.js, Inertia, Stripe',
            'servicio_2_precio' => 'Desde $12.000.000 COP',

            // ───── SERVICIO 3 — Automatización ─────
            'servicio_3_lead'   => 'Conecta tus sistemas y elimina el trabajo manual.',
            'servicio_3_image'  => '',
            'servicio_3_tags'   => 'n8n, Make, WhatsApp API, Anthropic API',
            'servicio_3_precio' => 'Desde $3.500.000 COP',

            // ───── SERVICIO 4 — Marketing / SEO ─────
            'servicio_4_lead'   => 'Aparece donde tus clientes te están buscando.',
            'servicio_4_image'  => '',
            'servicio_4_tags'   => 'SEO técnico, GEO/AEO, Schema, Analytics',
            'servicio_4_precio' => 'Desde $2.000.000 COP/mes',

            // ───── SERVICIO 5 — Mantenimiento ─────
            'servicio_5_lead'   => 'Tu plataforma operando 24/7 sin sorpresas.',
            'servicio_5_image'  => '',
            'servicio_5_tags'   => 'Monitoreo, Seguridad, Updates, SLA',
            'servicio_5_precio' => 'Desde $1.500.000 COP/mes',

            // ───── SERVICIO 6 — Bolsas de Horas ─────
            'servicio_6_lead'   => 'Horas pre-pagadas para evolucionar tu sistema.',
            'servicio_6_image'  => '',
            'servicio_6_tags'   => 'Flexible, Mensual, Sin contrato anual',
            'servicio_6_precio' => 'Desde $800.000 COP / 10h',
        ];

        // Solo agregar lo que NO existe — preserva ediciones del admin
        $added = 0;
        foreach ($defaults as $k => $v) {
            if (! array_key_exists($k, $content) || $content[$k] === null || $content[$k] === '') {
                $content[$k] = $v;
                $added++;
            }
        }

        $page->content = json_encode($content, JSON_UNESCAPED_UNICODE);
        $page->save();

        $this->command->info("✓ ServiciosStorytellingSeeder: agregadas {$added} keys nuevas (de ".count($defaults)." defaults).");
        $this->command->info("Total keys en pages.content (servicios): ".count($content));
    }
}
