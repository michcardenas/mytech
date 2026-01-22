<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Section;
use App\Models\Seo;

class LandingPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Landing 1: Software a Medida en Bogotá
        $landing1 = Page::create([
            'slug' => 'software-a-medida-bogota',
            'type' => 'landing',
            'title' => 'Desarrollo de Software a Medida en Bogotá',
            'is_active' => true,
        ]);

        // Sección Hero
        Section::create([
            'page_id' => $landing1->id,
            'name' => 'hero',
            'title' => 'Desarrollo de Software a Medida en Bogotá',
            'content' => null,
            'custom_data' => [
                'subtitle' => 'Transformamos tu idea en realidad con tecnología de punta',
                'cta_text' => 'Solicitar Cotización Gratis',
                'cta_url' => '/contacto',
                'background_image' => '/images/landings/hero-software-bogota.jpg',
                'badge' => 'Expertos en Colombia',
            ],
            'order' => 1,
            'is_active' => true,
        ]);

        // Sección Problema
        Section::create([
            'page_id' => $landing1->id,
            'name' => 'problema',
            'title' => '¿Tu empresa necesita un sistema único?',
            'content' => 'Las soluciones genéricas no se adaptan a tu negocio. Pierdes tiempo forzando procesos y tu equipo trabaja con limitaciones.',
            'custom_data' => [
                'icon' => 'fa-exclamation-triangle',
                'problemas' => [
                    'Software que no se adapta a tus procesos',
                    'Altos costos de licencias mensuales',
                    'Dependencia de proveedores externos',
                    'Falta de integración con tus sistemas',
                ],
            ],
            'order' => 2,
            'is_active' => true,
        ]);

        // Sección Solución
        Section::create([
            'page_id' => $landing1->id,
            'name' => 'solucion',
            'title' => 'Software diseñado para TU negocio',
            'content' => 'Desarrollamos soluciones 100% personalizadas que se adaptan a tus procesos, escalables y con soporte técnico dedicado.',
            'custom_data' => [
                'icon' => 'fa-check-circle',
                'beneficios' => [
                    '100% adaptado a tus procesos',
                    'Propiedad total del código',
                    'Escalable según tu crecimiento',
                    'Soporte técnico personalizado',
                    'Integración con tus herramientas actuales',
                    'ROI medible y comprobable',
                ],
            ],
            'order' => 3,
            'is_active' => true,
        ]);

        // Sección Proyectos Destacados
        Section::create([
            'page_id' => $landing1->id,
            'name' => 'proyectos_destacados',
            'title' => 'Casos de Éxito en Bogotá',
            'content' => 'Conoce empresas que ya transformaron su operación',
            'custom_data' => [
                'proyecto_ids' => [], // Se llenarán manualmente desde el admin
                'mostrar_testimonios' => true,
            ],
            'order' => 4,
            'is_active' => true,
        ]);

        // Sección FAQs
        Section::create([
            'page_id' => $landing1->id,
            'name' => 'faqs',
            'title' => 'Preguntas Frecuentes',
            'content' => null,
            'custom_data' => [
                'items' => [
                    [
                        'pregunta' => '¿Cuánto cuesta desarrollar un software a medida?',
                        'respuesta' => 'El costo depende del alcance del proyecto. Ofrecemos presupuestos detallados sin compromiso. En promedio, proyectos medianos van desde $15M - $50M COP.',
                    ],
                    [
                        'pregunta' => '¿Cuánto tiempo toma el desarrollo?',
                        'respuesta' => 'Proyectos pequeños: 2-3 meses. Proyectos medianos: 3-6 meses. Proyectos grandes: 6-12 meses. Trabajamos con metodologías ágiles para entregas incrementales.',
                    ],
                    [
                        'pregunta' => '¿Qué tecnologías utilizan?',
                        'respuesta' => 'Trabajamos con Laravel, React, Vue.js, Node.js, Python, Flutter y más. Seleccionamos la mejor tecnología según las necesidades de tu proyecto.',
                    ],
                    [
                        'pregunta' => '¿Incluyen soporte después del lanzamiento?',
                        'respuesta' => 'Sí, todos nuestros proyectos incluyen 3 meses de soporte gratuito. Después ofrecemos planes de mantenimiento mensual.',
                    ],
                    [
                        'pregunta' => '¿Trabajan solo en Bogotá?',
                        'respuesta' => 'Atendemos clientes en toda Colombia y Latinoamérica. Tenemos oficina en Bogotá pero trabajamos 100% remoto también.',
                    ],
                ],
            ],
            'order' => 5,
            'is_active' => true,
        ]);

        // Sección CTA Final
        Section::create([
            'page_id' => $landing1->id,
            'name' => 'cta_final',
            'title' => '¿Listo para transformar tu negocio?',
            'content' => 'Agenda una consultoría gratuita de 30 minutos y descubre cómo podemos ayudarte',
            'custom_data' => [
                'button_text' => 'Agendar Consultoría Gratis',
                'button_url' => '/contacto',
                'secondary_text' => 'O llámanos al +57 300 123 4567',
                'background_color' => '#007BFF',
            ],
            'order' => 6,
            'is_active' => true,
        ]);

        // SEO para Landing 1
        Seo::create([
            'page_id' => $landing1->id,
            'meta_title' => 'Software a Medida Bogotá | Desarrollo Personalizado 2026',
            'meta_description' => 'Desarrollamos software 100% personalizado en Bogotá. Soluciones escalables, soporte dedicado y ROI comprobable. Cotización gratis.',
            'meta_keywords' => 'software a medida bogotá, desarrollo software personalizado, software empresarial bogotá, desarrollo software colombia',
            'canonical_url' => 'https://tudominio.com/software-a-medida-bogota',
            'robots' => 'index,follow',
            'og_title' => 'Software a Medida en Bogotá | Tu Empresa',
            'og_description' => 'Transforma tu negocio con software diseñado para tus procesos únicos',
            'og_image' => '/images/og/software-bogota.jpg',
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'Software a Medida Bogotá',
            'twitter_description' => 'Desarrollo personalizado para empresas en Colombia',
            'focus_keyword' => 'software a medida bogotá',
            'sitemap_include' => true,
            'sitemap_priority' => 0.9,
            'sitemap_changefreq' => 'monthly',
            'is_active' => true,
        ]);

        // Landing 2: ERP a Medida
        $landing2 = Page::create([
            'slug' => 'erp-a-medida',
            'type' => 'landing',
            'title' => 'ERP a Medida para tu Empresa',
            'is_active' => true,
        ]);

        // Hero ERP
        Section::create([
            'page_id' => $landing2->id,
            'name' => 'hero',
            'title' => 'Sistema ERP Personalizado para tu Empresa',
            'content' => null,
            'custom_data' => [
                'subtitle' => 'Centraliza inventarios, ventas, compras y finanzas en un solo lugar',
                'cta_text' => 'Ver Demo Gratuita',
                'cta_url' => '/contacto',
                'background_image' => '/images/landings/hero-erp.jpg',
                'badge' => 'Integración Total',
            ],
            'order' => 1,
            'is_active' => true,
        ]);

        // Problema ERP
        Section::create([
            'page_id' => $landing2->id,
            'name' => 'problema',
            'title' => '¿Trabajas con múltiples sistemas desconectados?',
            'content' => 'Excel, software de inventarios, contabilidad separada... Pierdes tiempo sincronizando datos y cometiendo errores.',
            'custom_data' => [
                'icon' => 'fa-exclamation-triangle',
                'problemas' => [
                    'Datos duplicados y desactualizados',
                    'Reportes lentos y poco confiables',
                    'Sin visibilidad en tiempo real',
                    'Costos ocultos por módulos adicionales',
                ],
            ],
            'order' => 2,
            'is_active' => true,
        ]);

        // Solución ERP
        Section::create([
            'page_id' => $landing2->id,
            'name' => 'solucion',
            'title' => 'Un solo sistema, toda tu operación',
            'content' => 'ERP diseñado específicamente para tus procesos de negocio con módulos integrados.',
            'custom_data' => [
                'icon' => 'fa-cogs',
                'beneficios' => [
                    'Inventarios en tiempo real',
                    'Facturación electrónica integrada',
                    'Reportes financieros automáticos',
                    'CRM incluido',
                    'Multi-sede y multi-moneda',
                    'Dashboards personalizados',
                ],
                'modulos' => [
                    'Ventas y CRM',
                    'Inventarios y Almacén',
                    'Compras y Proveedores',
                    'Contabilidad y Finanzas',
                    'Recursos Humanos',
                    'Producción (opcional)',
                ],
            ],
            'order' => 3,
            'is_active' => true,
        ]);

        // FAQs ERP
        Section::create([
            'page_id' => $landing2->id,
            'name' => 'faqs',
            'title' => 'Preguntas Frecuentes sobre ERP',
            'content' => null,
            'custom_data' => [
                'items' => [
                    [
                        'pregunta' => '¿Qué incluye un ERP a medida?',
                        'respuesta' => 'Análisis de procesos, diseño personalizado, desarrollo, migración de datos, capacitación y 3 meses de soporte.',
                    ],
                    [
                        'pregunta' => '¿Puedo empezar con módulos básicos?',
                        'respuesta' => 'Sí, diseñamos el ERP modular para que empieces con lo esencial y agregues funcionalidades después.',
                    ],
                    [
                        'pregunta' => '¿Se integra con mi software actual?',
                        'respuesta' => 'Sí, desarrollamos APIs para conectar con tu contabilidad, e-commerce, bancos, etc.',
                    ],
                ],
            ],
            'order' => 4,
            'is_active' => true,
        ]);

        // CTA Final ERP
        Section::create([
            'page_id' => $landing2->id,
            'name' => 'cta_final',
            'title' => 'Solicita una demo personalizada',
            'content' => 'Te mostramos cómo funcionaría un ERP diseñado para tu empresa',
            'custom_data' => [
                'button_text' => 'Agendar Demo Gratis',
                'button_url' => '/contacto',
                'secondary_text' => 'Sin compromiso',
                'background_color' => '#28a745',
            ],
            'order' => 5,
            'is_active' => true,
        ]);

        // SEO para Landing 2
        Seo::create([
            'page_id' => $landing2->id,
            'meta_title' => 'ERP a Medida | Sistema Empresarial Personalizado',
            'meta_description' => 'Sistema ERP 100% personalizado para tu empresa. Integra ventas, inventarios, contabilidad y más en una sola plataforma.',
            'meta_keywords' => 'erp a medida, sistema erp personalizado, software empresarial, erp colombia',
            'canonical_url' => 'https://tudominio.com/erp-a-medida',
            'robots' => 'index,follow',
            'og_title' => 'ERP a Medida para tu Empresa',
            'og_description' => 'Sistema empresarial diseñado para tus procesos únicos',
            'og_type' => 'website',
            'focus_keyword' => 'erp a medida',
            'sitemap_include' => true,
            'sitemap_priority' => 0.9,
            'is_active' => true,
        ]);

        $this->command->info('✅ Landing pages creadas exitosamente!');
        $this->command->info('   - software-a-medida-bogota');
        $this->command->info('   - erp-a-medida');
    }
}
