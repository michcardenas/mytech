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
            [
                'slug' => 'software-facturacion-electronica-dian',
                'title' => 'Software de Facturación Electrónica DIAN',
                'seo' => [
                    'meta_title' => 'Software de Facturación Electrónica DIAN a la Medida | MY Tech',
                    'meta_description' => 'Desarrollamos e integramos software de facturación electrónica homologado por la DIAN: emisión de facturas, notas y documentos soporte en XML UBL 2.1, CUFE, integración con SIIGO y tu ERP. Cotiza gratis.',
                    'meta_keywords' => 'software facturacion electronica dian, facturacion electronica a la medida, software homologado dian, integracion siigo dian, factura electronica colombia, documento soporte electronico, software erp homologado dian facturacion electronica',
                    'canonical_url' => $base.'/software-facturacion-electronica-dian',
                    'robots' => 'index,follow',
                    'og_title' => 'Software de Facturación Electrónica DIAN a la Medida',
                    'og_description' => 'Facturación electrónica homologada por la DIAN dentro de tu operación: facturas, notas y documentos soporte en XML UBL 2.1, CUFE y validación en tiempo real, integrada con SIIGO y tu ERP.',
                    'og_type' => 'website',
                    'og_url' => $base.'/software-facturacion-electronica-dian',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Software de Facturación Electrónica DIAN a la Medida',
                    'twitter_description' => 'Facturación electrónica homologada DIAN dentro de tu sistema: XML UBL 2.1, CUFE, validación en tiempo real e integración con SIIGO. Cotiza gratis.',
                    'focus_keyword' => 'software facturacion electronica dian',
                    'breadcrumb_title' => 'Facturación Electrónica DIAN',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->serviceSchema(
                        $base,
                        'software-facturacion-electronica-dian',
                        'Desarrollo e integración de software de facturación electrónica DIAN',
                        'Software de Facturación Electrónica DIAN a la Medida | MY Tech',
                        'Desarrollo e integración de facturación electrónica homologada por la DIAN.',
                        'Desarrollo e integración de facturación electrónica homologada por la DIAN',
                        'Desarrollamos e integramos facturación electrónica homologada por la DIAN dentro del software del cliente: emisión de facturas de venta, notas crédito y débito y documentos soporte en XML UBL 2.1, generación de CUFE, validación previa ante la DIAN, representación gráfica en PDF y envío al adquiriente, con integración a SIIGO y al ERP o e-commerce del cliente.',
                        [['@type' => 'Country', 'name' => 'Colombia']],
                        '750',
                        'Facturación Electrónica DIAN',
                    ),
                ],
            ],
            [
                'slug' => 'software-erp-a-la-medida',
                'title' => 'Software ERP a la Medida',
                'seo' => [
                    'meta_title' => 'Software ERP a la Medida para Empresas en Colombia | MY Tech',
                    'meta_description' => 'Desarrollamos ERPs a la medida en Laravel: inventario, ventas, compras, cartera, producción y contabilidad en una sola plataforma que se ajusta a tu operación, se integra con la DIAN y escala. Cotiza gratis.',
                    'meta_keywords' => 'software erp a la medida, desarrollo de erp, erp para empresas colombia, sistema erp a la medida, erp inventario ventas contabilidad, software de gestion empresarial, erp homologado dian',
                    'canonical_url' => $base.'/software-erp-a-la-medida',
                    'robots' => 'index,follow',
                    'og_title' => 'Software ERP a la Medida para Empresas',
                    'og_description' => 'Un ERP que se ajusta a tu operación, no al revés: inventario, ventas, compras, cartera, producción y contabilidad en una plataforma propia, integrada con la DIAN y tu facturación, que escala contigo.',
                    'og_type' => 'website',
                    'og_url' => $base.'/software-erp-a-la-medida',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Software ERP a la Medida para Empresas',
                    'twitter_description' => 'ERP a la medida: inventario, ventas, cartera, producción y contabilidad en una plataforma propia que se integra con la DIAN y escala. Cotiza gratis.',
                    'focus_keyword' => 'software erp a la medida',
                    'breadcrumb_title' => 'Software ERP a la Medida',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->serviceSchema(
                        $base,
                        'software-erp-a-la-medida',
                        'Desarrollo de software ERP a la medida',
                        'Software ERP a la Medida para Empresas en Colombia | MY Tech',
                        'Desarrollo de sistemas ERP a la medida para empresas.',
                        'Desarrollo de software ERP a la medida',
                        'Desarrollamos sistemas ERP a la medida sobre Laravel que unifican la operación de la empresa en una sola plataforma: inventario y kardex, ventas y cotizaciones, compras, cartera y crédito, producción y órdenes de trabajo, listas de precios y contabilidad, con integración a la facturación electrónica de la DIAN, reportes e integraciones con las herramientas que la empresa ya usa.',
                        [
                            ['@type' => 'Country', 'name' => 'Colombia'],
                            ['@type' => 'Country', 'name' => 'México'],
                            ['@type' => 'Country', 'name' => 'España'],
                        ],
                        '1500',
                        'Software ERP a la Medida',
                    ),
                ],
            ],
            [
                'slug' => 'software-crm-a-la-medida',
                'title' => 'Software CRM a la Medida',
                'seo' => [
                    'meta_title' => 'Software CRM a la Medida: Pipeline, Leads y Ventas | MY Tech',
                    'meta_description' => 'Desarrollamos CRMs a la medida en Laravel: pipeline de ventas, gestión de leads, seguimiento, cotizaciones, reportes y automatizaciones, integrados con WhatsApp, correo y tu operación. Cotiza gratis.',
                    'meta_keywords' => 'software crm a la medida, desarrollo de crm, crm para empresas colombia, sistema crm a la medida, crm de ventas, pipeline de ventas, gestion de leads, crm integrado con whatsapp',
                    'canonical_url' => $base.'/software-crm-a-la-medida',
                    'robots' => 'index,follow',
                    'og_title' => 'Software CRM a la Medida: Pipeline, Leads y Ventas',
                    'og_description' => 'Un CRM que refleja cómo vendes tú: pipeline por etapas, leads, seguimiento, cotizaciones y reportes, integrado con WhatsApp, correo y tu operación. Tu código, sin licencias por usuario.',
                    'og_type' => 'website',
                    'og_url' => $base.'/software-crm-a-la-medida',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Software CRM a la Medida: Pipeline, Leads y Ventas',
                    'twitter_description' => 'CRM a la medida: pipeline por etapas, leads, seguimiento y reportes, integrado con WhatsApp y correo. Tu código, sin licencias por usuario. Cotiza gratis.',
                    'focus_keyword' => 'software crm a la medida',
                    'breadcrumb_title' => 'Software CRM a la Medida',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->serviceSchema(
                        $base,
                        'software-crm-a-la-medida',
                        'Desarrollo de software CRM a la medida',
                        'Software CRM a la Medida: Pipeline, Leads y Ventas | MY Tech',
                        'Desarrollo de sistemas CRM a la medida para gestión comercial.',
                        'Desarrollo de software CRM a la medida',
                        'Desarrollamos sistemas CRM a la medida sobre Laravel que reflejan el proceso comercial real de la empresa: pipeline de ventas por etapas, gestión de leads y contactos, bitácora de seguimiento, cotizaciones y propuestas, agenda y recordatorios, reportes por asesor y automatizaciones, integrados con WhatsApp, correo, agenda y el resto de la operación del negocio.',
                        [
                            ['@type' => 'Country', 'name' => 'Colombia'],
                            ['@type' => 'Country', 'name' => 'México'],
                            ['@type' => 'Country', 'name' => 'España'],
                        ],
                        '900',
                        'Software CRM a la Medida',
                    ),
                ],
            ],
            [
                'slug' => 'desarrollo-de-apps-moviles',
                'title' => 'Desarrollo de Apps Móviles',
                'seo' => [
                    'meta_title' => 'Desarrollo de Apps Móviles a la Medida (iOS y Android) | MY Tech',
                    'meta_description' => 'Desarrollamos apps móviles a la medida para iOS y Android con React Native: una sola base de código, publicación en las tiendas, backend propio, notificaciones y pagos. Cotiza gratis.',
                    'meta_keywords' => 'desarrollo de apps moviles, desarrollo de aplicaciones moviles, crear una app a la medida, app ios android a la medida, desarrollo app react native, empresa de desarrollo de apps colombia, aplicaciones moviles para empresas',
                    'canonical_url' => $base.'/desarrollo-de-apps-moviles',
                    'robots' => 'index,follow',
                    'og_title' => 'Desarrollo de Apps Móviles a la Medida (iOS y Android)',
                    'og_description' => 'Apps móviles para iOS y Android con una sola base de código en React Native: backend propio, notificaciones push, pagos, mapas y publicación en App Store y Google Play. A la medida de tu negocio.',
                    'og_type' => 'website',
                    'og_url' => $base.'/desarrollo-de-apps-moviles',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Desarrollo de Apps Móviles a la Medida (iOS y Android)',
                    'twitter_description' => 'Apps para iOS y Android con una sola base de código (React Native): backend propio, notificaciones, pagos y publicación en las tiendas. Cotiza gratis.',
                    'focus_keyword' => 'desarrollo de apps moviles',
                    'breadcrumb_title' => 'Desarrollo de Apps Móviles',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->serviceSchema(
                        $base,
                        'desarrollo-de-apps-moviles',
                        'Desarrollo de aplicaciones móviles a la medida',
                        'Desarrollo de Apps Móviles a la Medida (iOS y Android) | MY Tech',
                        'Desarrollo de apps móviles nativas para iOS y Android a la medida.',
                        'Desarrollo de aplicaciones móviles para iOS y Android',
                        'Desarrollamos aplicaciones móviles a la medida para iOS y Android con React Native (Expo): una sola base de código para ambas plataformas, backend y panel de administración propios, autenticación, notificaciones push, pagos, geolocalización y mapas, y publicación en App Store y Google Play, integradas con los sistemas que la empresa ya usa.',
                        [
                            ['@type' => 'Country', 'name' => 'Colombia'],
                            ['@type' => 'Country', 'name' => 'México'],
                            ['@type' => 'Country', 'name' => 'República Dominicana'],
                            ['@type' => 'Country', 'name' => 'España'],
                        ],
                        '1200',
                        'Desarrollo de Apps Móviles',
                    ),
                ],
            ],
            [
                'slug' => 'integracion-de-sistemas',
                'title' => 'Integración de Sistemas y APIs',
                'seo' => [
                    'meta_title' => 'Integración de Sistemas y APIs a la Medida | MY Tech',
                    'meta_description' => 'Conectamos los sistemas que ya usas: ERP, CRM, e-commerce, pasarelas de pago, facturación DIAN/SIIGO, WhatsApp, Google y cualquier API. Que tus plataformas hablen entre sí, sin copiar y pegar. Cotiza gratis.',
                    'meta_keywords' => 'integracion de sistemas, integracion de apis, conectar software, integracion erp crm, integracion de pasarelas de pago, integracion siigo dian, sincronizacion de sistemas, middleware a la medida',
                    'canonical_url' => $base.'/integracion-de-sistemas',
                    'robots' => 'index,follow',
                    'og_title' => 'Integración de Sistemas y APIs a la Medida',
                    'og_description' => 'Que tus plataformas hablen entre sí: ERP, CRM, e-commerce, pagos (Wompi, Stripe, Sistecrédito), facturación DIAN/SIIGO, WhatsApp y Google, conectados con APIs a la medida. Sin copiar y pegar.',
                    'og_type' => 'website',
                    'og_url' => $base.'/integracion-de-sistemas',
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => 'Integración de Sistemas y APIs a la Medida',
                    'twitter_description' => 'Conectamos ERP, CRM, e-commerce, pagos, facturación DIAN/SIIGO, WhatsApp y Google con APIs a la medida. Que tus sistemas hablen entre sí. Cotiza gratis.',
                    'focus_keyword' => 'integracion de sistemas',
                    'breadcrumb_title' => 'Integración de Sistemas',
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.9,
                    'sitemap_changefreq' => 'monthly',
                    'schema_markup' => $this->serviceSchema(
                        $base,
                        'integracion-de-sistemas',
                        'Integración de sistemas y desarrollo de APIs a la medida',
                        'Integración de Sistemas y APIs a la Medida | MY Tech',
                        'Integración de sistemas empresariales y desarrollo de APIs a la medida.',
                        'Integración de sistemas y desarrollo de APIs',
                        'Conectamos los sistemas que la empresa ya usa mediante integraciones y APIs a la medida: ERP, CRM, e-commerce, pasarelas de pago (Wompi, Stripe, Mercado Pago, Sistecrédito), facturación electrónica DIAN y SIIGO, WhatsApp, correo y servicios de Google (Gmail, Drive, Calendar), sincronizando datos en ambos sentidos, con webhooks, colas y trazabilidad, para eliminar el trabajo manual de copiar y pegar entre plataformas.',
                        [
                            ['@type' => 'Country', 'name' => 'Colombia'],
                            ['@type' => 'Country', 'name' => 'México'],
                            ['@type' => 'Country', 'name' => 'España'],
                        ],
                        '800',
                        'Integración de Sistemas',
                    ),
                ],
            ],
        ];
    }

    /**
     * Constructor genérico del @graph JSON-LD para las landings de servicio.
     *
     * @param  array<int, array<string, string>>  $areasServed
     * @return array<string, mixed>
     */
    protected function serviceSchema(
        string $base,
        string $slug,
        string $serviceName,
        string $webpageName,
        string $webpageDescription,
        string $serviceType,
        string $serviceDescription,
        array $areasServed,
        string $lowPrice,
        string $breadcrumbName,
    ): array {
        $url = $base.'/'.$slug;

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
                    'name' => $webpageName,
                    'description' => $webpageDescription,
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
                    'name' => $serviceName,
                    'serviceType' => $serviceType,
                    'url' => $url,
                    'description' => $serviceDescription,
                    'provider' => ['@id' => $base.'/#organization'],
                    'areaServed' => $areasServed,
                    'offers' => [
                        '@type' => 'AggregateOffer',
                        'priceCurrency' => 'USD',
                        'lowPrice' => $lowPrice,
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
                            'name' => $breadcrumbName,
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
