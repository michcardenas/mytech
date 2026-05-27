<?php

namespace Database\Seeders;

use App\Models\Seo;
use Illuminate\Database\Seeder;

class SeoOptimizeMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $updates = [
            2 => [
                'meta_title' => 'Servicios de Software a Medida en Colombia | MY Tech',
                'meta_description' => 'Desarrollo web, apps móviles, SaaS, automatización con IA y SEO técnico. Soluciones a medida para empresas en Colombia y LATAM. Consultoría gratis.',
            ],
            3 => [
                'meta_title' => 'Portafolio de Proyectos | MY Tech Solutions',
                'meta_description' => 'Proyectos reales de software a medida: SaaS, e-commerce, automatización WhatsApp, sistemas empresariales. 37 plataformas en Colombia y LATAM.',
            ],
            4 => [
                'meta_title' => 'Sobre Nosotros — Agencia de Software LATAM | MY Tech',
                'meta_description' => 'Somos un estudio de software a medida en LATAM. 37+ proyectos en producción en 11 países. Conoce nuestra filosofía y forma de trabajar.',
            ],
            5 => [
                'meta_title' => 'Cotiza tu Software en Colombia — Gratis | MY Tech',
                'meta_description' => 'Cotiza tu proyecto de software a medida en Colombia. Respuesta en 24h con propuesta clara. WhatsApp directo o formulario. Consultoría sin costo.',
            ],
            7 => [
                'meta_title' => 'Integrar DIAN en Software: Facturación Electrónica 2026',
                'meta_description' => 'Guía completa para integrar Facturación Electrónica DIAN en software a medida. Desde habilitación hasta XML UBL 2.1 en producción. Casos reales 2026.',
            ],
            8 => [
                'meta_title' => 'Precio Desarrollo de Software en Bogotá [Real 2026]',
                'meta_description' => 'Precios reales para desarrollar software a medida en Bogotá 2026: landing pages, SaaS, ERPs. Rangos por tipo de proyecto y qué incluye cada uno.',
            ],
            9 => [
                'meta_title' => 'WordPress vs Desarrollo a Medida: ¿Cuándo Migrar? 2026',
                'meta_description' => 'WordPress funciona al inicio pero frena el crecimiento. Señales claras de cuándo migrar a un sistema a medida y cómo hacerlo sin afectar operación.',
            ],
            10 => [
                'meta_title' => 'Cómo Crear un SaaS a Medida para tu Nicho — Guía 2026',
                'meta_description' => 'Por qué el Micro-SaaS es la tendencia 2026. Aprende paso a paso cómo crear software a medida para tu nicho y generar líneas de ingresos recurrentes.',
            ],
            11 => [
                'meta_title' => 'Precio Panel Admin Web en Colombia [Real 2026]',
                'meta_description' => 'Desglose real de costos para desarrollar un panel administrativo web en Colombia 2026. Módulos básicos, intermedios y avanzados. Proyectos en Laravel.',
            ],
            12 => [
                'meta_title' => '¿Cuánto cuesta software en Colombia 2026? Guía Real',
                'meta_description' => 'Guía 2026 de precios reales para desarrollar software a medida en Colombia. Compara costos freelancer vs agencia y qué incluye cada opción.',
            ],
            13 => [
                'meta_title' => 'Cómo Elegir Agencia de Software en Colombia [2026]',
                'meta_description' => 'Qué evaluar antes de contratar una agencia de software en Colombia: preguntas clave, red flags y qué incluir en el contrato. Ejemplos reales 2026.',
            ],
            14 => [
                'meta_title' => 'Desarrollo Web para Empresas en Chile [Precios 2026]',
                'meta_description' => '¿Página web o plataforma a medida? Guía 2026 de precios reales para empresas en Chile: sitios corporativos, SaaS y ERPs. Proyectos en producción.',
            ],
            15 => [
                'meta_title' => 'Agencia de Software para Argentina — Guía 2026',
                'meta_description' => 'Guía para empresas argentinas: rangos de precio reales, qué exigir antes de contratar y proyectos entregados en el mercado argentino en producción.',
            ],
            16 => [
                'meta_title' => 'Blog — Desarrollo de Software, SaaS y IA | MY Tech',
                'meta_description' => 'Guías técnicas, casos reales y análisis sobre desarrollo a medida, SaaS, automatización con IA y SEO para empresas en Colombia y LATAM.',
            ],
        ];

        foreach ($updates as $id => $data) {
            $seo = Seo::find($id);
            if (! $seo) {
                $this->command->warn("Seo #{$id} not found");
                continue;
            }

            $seo->meta_title = $data['meta_title'];
            $seo->meta_description = $data['meta_description'];

            if (empty($seo->og_title) || strlen($seo->og_title) > 60) {
                $seo->og_title = $data['meta_title'];
            }
            if (empty($seo->og_description) || strlen($seo->og_description) > 160) {
                $seo->og_description = $data['meta_description'];
            }
            if (empty($seo->twitter_title) || strlen($seo->twitter_title) > 60) {
                $seo->twitter_title = $data['meta_title'];
            }
            if (empty($seo->twitter_description) || strlen($seo->twitter_description) > 160) {
                $seo->twitter_description = $data['meta_description'];
            }
            $seo->save();
            $this->command->info("OK [{$id}] " . strlen($data['meta_title']) . " / " . strlen($data['meta_description']));
        }
    }
}
