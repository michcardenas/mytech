<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Seo;
use Illuminate\Database\Seeder;

/**
 * Landing pages comerciales (type = landing).
 *
 * Cada landing = un registro `Page` + su registro `Seo` (meta + schema JSON-LD).
 * El diseño premium vive en resources/views/landings/custom/{slug}.blade.php y lo
 * sirve LandingController@show vía el catch-all /{slug}.
 *
 * Idempotente: usa updateOrCreate por slug, así que correrlo N veces no duplica.
 * Para añadir una landing nueva, agrega una entrada a landings() y crea su vista.
 */
class LandingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->landings() as $data) {
            $page = Page::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'type' => 'landing',
                    'title' => $data['title'],
                    'is_active' => true,
                ]
            );

            Seo::updateOrCreate(
                ['page_id' => $page->id],
                $data['seo'] + ['page_id' => $page->id, 'is_active' => true]
            );

            $this->command?->info("Landing lista: /{$data['slug']}");
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function landings(): array
    {
        $base = 'https://mytechsolutionsco.com';

        return [
            [
                'slug' => 'chatbots-ia-whatsapp',
                'title' => 'Chatbots con IA para WhatsApp',
                'seo' => [
                    'meta_title' => 'Chatbots con IA para WhatsApp que Cobran y Agendan | MY Tech',
                    'meta_description' => 'Desarrollamos chatbots con IA (Claude) para WhatsApp que atienden 24/7, responden con la info de tu negocio, cobran, validan el pago y agendan citas. Cotiza gratis.',
                    'meta_keywords' => 'chatbot con ia para whatsapp, bot de whatsapp para empresas, asistente de ia por whatsapp, chatbot con inteligencia artificial, agente de ia whatsapp colombia, desarrollo de chatbots',
                    'canonical_url' => $base.'/chatbots-ia-whatsapp',
                    'robots' => 'index,follow',
                    'og_title' => 'Chatbots con IA para WhatsApp que atienden, cobran y agendan',
                    'og_description' => 'Asistentes con IA (Claude) para WhatsApp: atienden con tu información, cobran por Mercado Pago o transferencia, validan el pago y agendan la cita. A la medida, con CRM.',
                    'og_type' => 'website',
                    'og_url' => $base.'/chatbots-ia-whatsapp',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Chatbots con IA para WhatsApp que atienden, cobran y agendan',
                    'twitter_description' => 'Asistentes con IA (Claude) para WhatsApp: atienden con tu info, cobran, validan el pago y agendan. A la medida, con CRM. Cotiza gratis.',
                    'focus_keyword' => 'chatbot con ia para whatsapp',
                    'breadcrumb_title' => 'Chatbots con IA para WhatsApp',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->chatbotsSchema($base),
                ],
            ],
            [
                'slug' => 'desarrollo-ecommerce',
                'title' => 'Desarrollo de E-commerce a la Medida',
                'seo' => [
                    'meta_title' => 'Desarrollo de Tiendas Online a la Medida que Venden | MY Tech',
                    'meta_description' => 'Desarrollamos tiendas online y e-commerce a la medida en Laravel: catálogo, inventario, pagos (Stripe, Wompi, Mercado Pago), checkout optimizado y SEO. Tu tienda, sin límites.',
                    'meta_keywords' => 'desarrollo de ecommerce a la medida, tienda online a la medida, desarrollo de tienda virtual, ecommerce colombia, desarrollo tienda online, tienda online laravel',
                    'canonical_url' => $base.'/desarrollo-ecommerce',
                    'robots' => 'index,follow',
                    'og_title' => 'Desarrollo de E-commerce y Tiendas Online a la Medida',
                    'og_description' => 'Tiendas online a la medida que venden: catálogo, pagos (Stripe, Wompi, Mercado Pago), checkout optimizado, panel propio y SEO técnico. Sin comisiones por venta ni límites de plantilla.',
                    'og_type' => 'website',
                    'og_url' => $base.'/desarrollo-ecommerce',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Desarrollo de E-commerce y Tiendas Online a la Medida',
                    'twitter_description' => 'Tiendas online a la medida que venden: catálogo, pagos, checkout optimizado y SEO. Sin comisiones ni límites de plantilla. Cotiza gratis.',
                    'focus_keyword' => 'desarrollo de ecommerce a la medida',
                    'breadcrumb_title' => 'Desarrollo de E-commerce a la Medida',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->ecommerceSchema($base),
                ],
            ],
            [
                'slug' => 'software-a-la-medida',
                'title' => 'Desarrollo de Software a la Medida',
                'seo' => [
                    'meta_title' => 'Software a la Medida: SaaS, ERP y CRM para Empresas | MY Tech',
                    'meta_description' => 'Desarrollamos software a la medida para empresas: SaaS, ERPs, CRMs, paneles y plataformas en Laravel que se ajustan a tu operación, se integran y escalan. Cotiza gratis.',
                    'meta_keywords' => 'desarrollo de software a la medida, software a la medida para empresas, desarrollo de saas, desarrollo de erp a la medida, desarrollo de crm, software empresarial colombia',
                    'canonical_url' => $base.'/software-a-la-medida',
                    'robots' => 'index,follow',
                    'og_title' => 'Desarrollo de Software a la Medida: SaaS, ERP y CRM',
                    'og_description' => 'Software a la medida para empresas: SaaS, ERPs, CRMs, paneles y plataformas que se ajustan a tu operación, se integran con lo que ya usas y escalan. El código es tuyo.',
                    'og_type' => 'website',
                    'og_url' => $base.'/software-a-la-medida',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Desarrollo de Software a la Medida: SaaS, ERP y CRM',
                    'twitter_description' => 'Software a la medida para empresas: SaaS, ERPs, CRMs y plataformas que se ajustan a tu operación y escalan. El código es tuyo. Cotiza gratis.',
                    'focus_keyword' => 'desarrollo de software a la medida',
                    'breadcrumb_title' => 'Desarrollo de Software a la Medida',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->softwareSchema($base),
                ],
            ],
            [
                'slug' => 'automatizacion-ia-empresas',
                'title' => 'Automatización con IA para Empresas',
                'seo' => [
                    'meta_title' => 'Automatización con IA para Empresas: Correos y Documentos | MY Tech',
                    'meta_description' => 'Integramos IA (Claude) en tu operación para automatizar correos, redacción de documentos, interpretación de contratos y resúmenes de casos. Dentro de tus herramientas, con tus reglas.',
                    'meta_keywords' => 'automatizacion con inteligencia artificial, automatizar procesos con ia, agentes de ia para empresas, ia para automatizar tareas, implementar ia en mi empresa, software juridico con ia, automatizacion de documentos con ia',
                    'canonical_url' => $base.'/automatizacion-ia-empresas',
                    'robots' => 'index,follow',
                    'og_title' => 'Automatización con IA para Empresas | MY Tech Solutions',
                    'og_description' => 'IA integrada a tu correo, tus documentos y tu base de datos: lee, clasifica, redacta, interpreta contratos y resume casos. Con permisos por rol y aprobación humana.',
                    'og_type' => 'website',
                    'og_url' => $base.'/automatizacion-ia-empresas',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Automatización con IA para Empresas | MY Tech Solutions',
                    'twitter_description' => 'IA que lee tus correos, redacta documentos e interpreta contratos dentro de tus herramientas. A la medida, no una plantilla. Cotiza gratis.',
                    'focus_keyword' => 'automatizacion con inteligencia artificial',
                    'breadcrumb_title' => 'Automatización con IA para Empresas',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->automatizacionIaSchema($base),
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function automatizacionIaSchema(string $base): array
    {
        $url = $base.'/automatizacion-ia-empresas';

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => $base.'/#organization',
                    'name' => 'MY Tech Solutions',
                    'url' => $base,
                    'logo' => $base.'/images/icon.png',
                    'sameAs' => [
                        'https://www.instagram.com/mytech_solutions',
                        'https://www.facebook.com/profile.php?id=61575108256490',
                        'https://www.linkedin.com/company/110759244',
                        'https://www.tiktok.com/@mytechsolutionsco',
                    ],
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => $url.'#webpage',
                    'url' => $url,
                    'name' => 'Automatización con IA para Empresas: Correos y Documentos | MY Tech',
                    'description' => 'Integración de inteligencia artificial (Claude) en la operación de la empresa para automatizar correos, redacción de documentos, interpretación de contratos y resúmenes de casos.',
                    'inLanguage' => 'es',
                    'isPartOf' => ['@id' => $base.'/#website'],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $base.'/#website',
                    'url' => $base,
                    'name' => 'MY Tech Solutions',
                    'publisher' => ['@id' => $base.'/#organization'],
                    'inLanguage' => 'es',
                ],
                [
                    '@type' => 'Service',
                    '@id' => $url.'#service',
                    'name' => 'Automatización de procesos con inteligencia artificial',
                    'serviceType' => 'Automatización con IA e integración de agentes inteligentes',
                    'url' => $url,
                    'description' => 'Integramos IA de Claude (Anthropic) dentro de la operación de la empresa para automatizar la lectura y clasificación de correos, la redacción de documentos, la interpretación de contratos y el resumen de casos, conectada a Gmail, Google Drive, bases de datos y el ERP o CRM del cliente, con permisos por rol, trazabilidad y aprobación humana.',
                    'provider' => ['@id' => $base.'/#organization'],
                    'areaServed' => [
                        ['@type' => 'Country', 'name' => 'Colombia'],
                        ['@type' => 'Country', 'name' => 'México'],
                        ['@type' => 'Country', 'name' => 'Argentina'],
                        ['@type' => 'Country', 'name' => 'Chile'],
                        ['@type' => 'Country', 'name' => 'Perú'],
                        ['@type' => 'Country', 'name' => 'España'],
                    ],
                    'offers' => [
                        '@type' => 'AggregateOffer',
                        'priceCurrency' => 'USD',
                        'lowPrice' => '1200',
                        'offerCount' => '1',
                        'url' => $url,
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => $url.'#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Inicio',
                            'item' => $base,
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'Automatización con IA para Empresas',
                            'item' => $url,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function chatbotsSchema(string $base): array
    {
        $url = $base.'/chatbots-ia-whatsapp';

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => $base.'/#organization',
                    'name' => 'MY Tech Solutions',
                    'url' => $base,
                    'logo' => $base.'/images/icon.png',
                    'sameAs' => [
                        'https://www.instagram.com/mytech_solutions',
                        'https://www.facebook.com/profile.php?id=61575108256490',
                        'https://www.linkedin.com/company/110759244',
                        'https://www.tiktok.com/@mytechsolutionsco',
                    ],
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => $url.'#webpage',
                    'url' => $url,
                    'name' => 'Chatbots con IA para WhatsApp que Cobran y Agendan | MY Tech',
                    'description' => 'Desarrollo de chatbots y asistentes con IA (Claude) para WhatsApp que atienden, cobran, validan el pago y agendan citas.',
                    'inLanguage' => 'es',
                    'isPartOf' => ['@id' => $base.'/#website'],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $base.'/#website',
                    'url' => $base,
                    'name' => 'MY Tech Solutions',
                    'publisher' => ['@id' => $base.'/#organization'],
                    'inLanguage' => 'es',
                ],
                [
                    '@type' => 'Service',
                    '@id' => $url.'#service',
                    'name' => 'Desarrollo de chatbots con IA para WhatsApp',
                    'serviceType' => 'Desarrollo de chatbots con inteligencia artificial',
                    'url' => $url,
                    'description' => 'Chatbots y asistentes con IA (Claude de Anthropic) para WhatsApp que atienden a tus clientes 24/7 con la información de tu negocio, cobran por Mercado Pago o transferencia, validan el pago y agendan citas, integrados a un CRM a la medida.',
                    'provider' => ['@id' => $base.'/#organization'],
                    'areaServed' => [
                        ['@type' => 'Country', 'name' => 'Colombia'],
                        ['@type' => 'Country', 'name' => 'México'],
                        ['@type' => 'Country', 'name' => 'Argentina'],
                        ['@type' => 'Country', 'name' => 'Chile'],
                        ['@type' => 'Country', 'name' => 'España'],
                    ],
                    'offers' => [
                        '@type' => 'AggregateOffer',
                        'priceCurrency' => 'USD',
                        'lowPrice' => '900',
                        'offerCount' => '1',
                        'url' => $url,
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => $url.'#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Inicio',
                            'item' => $base,
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'Chatbots con IA para WhatsApp',
                            'item' => $url,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function ecommerceSchema(string $base): array
    {
        $url = $base.'/desarrollo-ecommerce';

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => $base.'/#organization',
                    'name' => 'MY Tech Solutions',
                    'url' => $base,
                    'logo' => $base.'/images/icon.png',
                    'sameAs' => [
                        'https://www.instagram.com/mytech_solutions',
                        'https://www.facebook.com/profile.php?id=61575108256490',
                        'https://www.linkedin.com/company/110759244',
                        'https://www.tiktok.com/@mytechsolutionsco',
                    ],
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => $url.'#webpage',
                    'url' => $url,
                    'name' => 'Desarrollo de Tiendas Online a la Medida que Venden | MY Tech',
                    'description' => 'Desarrollo de e-commerce y tiendas online a la medida en Laravel: catálogo, pagos, checkout optimizado y SEO técnico.',
                    'inLanguage' => 'es',
                    'isPartOf' => ['@id' => $base.'/#website'],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $base.'/#website',
                    'url' => $base,
                    'name' => 'MY Tech Solutions',
                    'publisher' => ['@id' => $base.'/#organization'],
                    'inLanguage' => 'es',
                ],
                [
                    '@type' => 'Service',
                    '@id' => $url.'#service',
                    'name' => 'Desarrollo de e-commerce y tiendas online a la medida',
                    'serviceType' => 'Desarrollo de comercio electrónico a la medida',
                    'url' => $url,
                    'description' => 'Desarrollo de tiendas online y plataformas de e-commerce a la medida sobre Laravel: catálogo e inventario, pasarelas de pago (Stripe, Wompi, Mercado Pago, Sistecrédito), checkout optimizado, panel de administración propio, SEO técnico e integraciones con facturación y envíos.',
                    'provider' => ['@id' => $base.'/#organization'],
                    'areaServed' => [
                        ['@type' => 'Country', 'name' => 'Colombia'],
                        ['@type' => 'Country', 'name' => 'México'],
                        ['@type' => 'Country', 'name' => 'Ecuador'],
                        ['@type' => 'Country', 'name' => 'Argentina'],
                        ['@type' => 'Country', 'name' => 'España'],
                    ],
                    'offers' => [
                        '@type' => 'AggregateOffer',
                        'priceCurrency' => 'USD',
                        'lowPrice' => '1200',
                        'offerCount' => '1',
                        'url' => $url,
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => $url.'#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Inicio',
                            'item' => $base,
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'Desarrollo de E-commerce a la Medida',
                            'item' => $url,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function softwareSchema(string $base): array
    {
        $url = $base.'/software-a-la-medida';

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => $base.'/#organization',
                    'name' => 'MY Tech Solutions',
                    'url' => $base,
                    'logo' => $base.'/images/icon.png',
                    'sameAs' => [
                        'https://www.instagram.com/mytech_solutions',
                        'https://www.facebook.com/profile.php?id=61575108256490',
                        'https://www.linkedin.com/company/110759244',
                        'https://www.tiktok.com/@mytechsolutionsco',
                    ],
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => $url.'#webpage',
                    'url' => $url,
                    'name' => 'Software a la Medida: SaaS, ERP y CRM para Empresas | MY Tech',
                    'description' => 'Desarrollo de software a la medida para empresas: SaaS, ERPs, CRMs, paneles y plataformas web en Laravel.',
                    'inLanguage' => 'es',
                    'isPartOf' => ['@id' => $base.'/#website'],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $base.'/#website',
                    'url' => $base,
                    'name' => 'MY Tech Solutions',
                    'publisher' => ['@id' => $base.'/#organization'],
                    'inLanguage' => 'es',
                ],
                [
                    '@type' => 'Service',
                    '@id' => $url.'#service',
                    'name' => 'Desarrollo de software a la medida',
                    'serviceType' => 'Desarrollo de software a la medida',
                    'url' => $url,
                    'description' => 'Desarrollo de software a la medida para empresas sobre Laravel: plataformas SaaS multi-tenant, ERPs y paneles administrativos, CRMs, marketplaces y portales, con automatizaciones, integraciones y reportes, ajustados a la operación de cada negocio.',
                    'provider' => ['@id' => $base.'/#organization'],
                    'areaServed' => [
                        ['@type' => 'Country', 'name' => 'Colombia'],
                        ['@type' => 'Country', 'name' => 'México'],
                        ['@type' => 'Country', 'name' => 'Argentina'],
                        ['@type' => 'Country', 'name' => 'España'],
                        ['@type' => 'Country', 'name' => 'Estados Unidos'],
                    ],
                    'offers' => [
                        '@type' => 'AggregateOffer',
                        'priceCurrency' => 'USD',
                        'lowPrice' => '1500',
                        'offerCount' => '1',
                        'url' => $url,
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => $url.'#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Inicio',
                            'item' => $base,
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'Desarrollo de Software a la Medida',
                            'item' => $url,
                        ],
                    ],
                ],
            ],
        ];
    }
}
