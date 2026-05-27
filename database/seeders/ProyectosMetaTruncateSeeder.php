<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Acorta meta_title (>60) y meta_description (>160) en proyectos.
 */
class ProyectosMetaTruncateSeeder extends Seeder
{
    public function run(): void
    {
        $manual = [
            'voyconvos' => [
                'meta_title' => 'VoyConVos — Carpooling en Argentina con Laravel',
            ],
            'tumesa' => [
                'meta_title' => 'TuMesa — Comensales y Chefs en Argentina',
                'meta_description' => 'Plataforma TuMesa conecta comensales con chefs profesionales en Argentina. Reservas de experiencias gastronómicas únicas. Caso MY Tech Solutions.',
            ],
            'academia-gva' => [
                'meta_title' => 'Academia GVA — Capacitación Corporativa en México',
            ],
            'guillen-cleaning' => [
                'meta_title' => 'Guillen Cleaning — Servicios de Limpieza en Minnesota',
            ],
            'innovatech' => [
                'meta_title' => 'Innovatech — Inventario y Ventas en Colombia',
            ],
            'anabelle' => [
                'meta_title' => 'Anabelle — Planes Fitness y Recetas en Colombia',
            ],
            'citas-mallorca' => [
                'meta_title' => 'Citas Mallorca — Plataforma de Citas con Premium',
            ],
            'sur-alpine-bot-de-whatsapp-con-ia-para-automatizacion-de-ventas' => [
                'meta_title' => 'Sur Alpine — Bot WhatsApp con IA para Ventas',
                'meta_description' => 'Bot de WhatsApp con IA para automatización de ventas. Calificación de leads, agendamiento y respuestas 24/7. Caso real Sur Alpine — MY Tech.',
            ],
            'alberto-asenssion-sitio-personal-rd' => [
                'meta_title' => 'Sitio Personal Profesional RD — Alberto Asenssion',
                'meta_description' => 'Sitio web personal profesional para abogado en República Dominicana. Diseño editorial, SEO técnico y formulario de consulta. Caso MY Tech.',
            ],
            'formula-high-ticket-crm-ventas-telefonicas' => [
                'meta_title' => 'Formula High Ticket — CRM para Ventas Telefónicas',
                'meta_description' => 'CRM a medida para ventas telefónicas high ticket: dashboard de leads, scoring, llamadas y closers. Caso Formula High Ticket en Laravel.',
            ],
            'miracle-gestion-b2b-siigo-dian' => [
                'meta_title' => 'Miracle — ERP B2B con SIIGO/DIAN en Laravel',
                'meta_description' => 'ERP B2B con facturación electrónica SIIGO/DIAN en Laravel. Cotizaciones, órdenes, inventario y reportes. Caso real MY Tech Solutions.',
            ],
            'bos-metrics-saas-fleet-management' => [
                'meta_title' => 'Bos Metrics — SaaS Multi-Tenant para Flotas USA',
                'meta_description' => 'SaaS multi-tenant para gestión de flotas y fuerza laboral en USA. Dashboard, métricas y operación en tiempo real. Caso Bos Metrics — MY Tech.',
            ],
            'bos-metrics-app-conductores-amazon-dsp' => [
                'meta_title' => 'Bos Metrics App — Conductores Amazon DSP',
                'meta_description' => 'App móvil iOS y Android para conductores de flotas Amazon DSP. Rutas, métricas, registro de jornada y reportes. Caso real Bos Metrics.',
            ],
            'manzer-agroforestal-web-seo-google-ads' => [
                'meta_title' => 'Manzer Agroforestal — Web Laravel + SEO + Ads',
                'meta_description' => 'Web Laravel con SEO técnico avanzado y Google Ads gestionado para empresa agroforestal en Lleida, España. Caso Manzer — MY Tech.',
            ],
            'manzer-erp-gestion-integral-agroforestal' => [
                'meta_title' => 'MANZER ERP — Gestión Agroforestal en Laravel',
                'meta_description' => 'Sistema ERP integral para empresa agroforestal en Laravel. Operaciones, recursos, facturación y reportes. Caso real Manzer en Lleida.',
            ],
            'keriva-app-farmacias-turno-precios' => [
                'meta_title' => 'Keriva — App de Farmacias de Turno y Precios',
                'meta_description' => 'App móvil para encontrar farmacias de turno y comparar precios de medicamentos en República Dominicana. Caso Keriva — MY Tech.',
            ],
            'tennis-challenge-pronosticos-tenis' => [
                'meta_title' => 'Tennis Challenge — Fantasy Tenis con API ESPN',
                'meta_description' => 'Plataforma fantasy de tenis con pronósticos en tiempo real, API ESPN, ranking y ligas privadas. Caso Tennis Challenge — MY Tech.',
            ],
            'dicomsa-automatizacion-comercial-con-whatsapp-business-api' => [
                'meta_title' => 'Dicomsa — Automatización Comercial WhatsApp API',
                'meta_description' => 'Integración de WhatsApp Business API con CRM para automatización comercial. Respond.io y Meta Ads. Caso Dicomsa — MY Tech Solutions.',
            ],
        ];

        // Solo truncar description (mantener title bueno)
        $autoTruncateDesc = ['flexfood', 'electralhome', 'offiesco-latam', 'onlyescorts', 'betogether', 'elitecloser'];

        $countTitle = 0;
        $countDesc = 0;

        foreach ($manual as $slug => $data) {
            $p = Proyecto::where('slug', $slug)->first();
            if (! $p) {
                $this->command->warn("Proyecto not found: $slug");
                continue;
            }

            $changed = false;
            if (! empty($data['meta_title'])) {
                $p->meta_title = $data['meta_title'];
                $countTitle++;
                $changed = true;
            }
            if (! empty($data['meta_description'])) {
                $p->meta_description = $data['meta_description'];
                $countDesc++;
                $changed = true;
            }
            if ($changed) {
                $p->save();
            }
        }

        foreach ($autoTruncateDesc as $slug) {
            $p = Proyecto::where('slug', $slug)->first();
            if (! $p) {
                continue;
            }
            if (strlen($p->meta_description ?? '') > 160) {
                $p->meta_description = Str::limit($p->meta_description, 155, '.');
                $p->save();
                $countDesc++;
            }
        }

        $this->command->info("Titles updated: $countTitle | Descriptions updated: $countDesc");
    }
}
