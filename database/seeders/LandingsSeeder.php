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
                        'https://www.facebook.com/profile.php?id=61575108256490',
                        'https://www.instagram.com/mytech_solutions',
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
}
