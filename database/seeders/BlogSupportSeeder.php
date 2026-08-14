<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Seo;
use Illuminate\Database\Seeder;

/**
 * Blogs de apoyo (cluster hub-and-spoke) para las 3 landings comerciales.
 *
 * Cada post = Page(type=blog) + Seo. La vista blog/show auto-genera el schema
 * BlogPosting y el OG/Twitter desde los campos del post; el Seo solo afina el
 * meta_title/description. Cada artículo enlaza internamente a SU landing
 * (spoke → hub) y a un caso real.
 *
 * Idempotente: updateOrCreate por slug. Para añadir un post, agrega una entrada
 * a posts(). El reading_time se calcula del contenido.
 */
class BlogSupportSeeder extends Seeder
{
    protected string $base = 'https://mytechsolutionsco.com';

    protected string $author = 'Equipo MY Tech Solutions';

    public function run(): void
    {
        foreach ($this->posts() as $data) {
            $words = str_word_count(strip_tags($data['content']));
            $readingTime = max(1, (int) ceil($words / 200));

            $page = Page::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'type' => 'blog',
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'category' => $data['category'],
                    'tags' => $data['tags'],
                    'author' => $this->author,
                    'reading_time' => $readingTime,
                    'published_at' => $data['published_at'],
                    'is_active' => true,
                ]
            );

            Seo::updateOrCreate(
                ['page_id' => $page->id],
                [
                    'page_id' => $page->id,
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'meta_keywords' => $data['tags'],
                    'canonical_url' => $this->base.'/blog/'.$data['slug'],
                    'robots' => 'index,follow',
                    'og_title' => $data['meta_title'],
                    'og_description' => $data['meta_description'],
                    'og_type' => 'article',
                    'og_url' => $this->base.'/blog/'.$data['slug'],
                    'og_site_name' => 'MY Tech Solutions',
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => $data['meta_title'],
                    'twitter_description' => $data['meta_description'],
                    'focus_keyword' => $data['focus_keyword'],
                    'sitemap_include' => true,
                    'sitemap_priority' => 0.7,
                    'sitemap_changefreq' => 'monthly',
                    // schema_markup vacío a propósito: blog/show auto-genera BlogPosting.
                    'is_active' => true,
                ]
            );

            $this->command?->info("Blog listo ({$readingTime} min): /blog/{$data['slug']}");
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function posts(): array
    {
        return [
            [
                'slug' => 'cuanto-cuesta-chatbot-ia-whatsapp',
                'title' => '¿Cuánto cuesta un chatbot con IA para WhatsApp en 2026? Guía de precios',
                'category' => 'tecnologia',
                'tags' => 'chatbot con ia para whatsapp, precio chatbot whatsapp, bot de whatsapp para empresas, asistente de ia whatsapp, cuanto cuesta un chatbot',
                'excerpt' => '¿Cuánto cuesta un chatbot con IA para WhatsApp? Te explicamos de qué depende el precio, los rangos reales en 2026 y la diferencia entre un bot genérico y un asistente a la medida.',
                'meta_title' => 'Cuánto Cuesta un Chatbot con IA para WhatsApp (2026) | MY Tech',
                'meta_description' => '¿Cuánto cuesta un chatbot con IA para WhatsApp en 2026? Rangos de precio reales, qué determina el costo y bot genérico vs asistente a la medida que cobra y agenda.',
                'focus_keyword' => 'cuánto cuesta un chatbot con ia para whatsapp',
                'published_at' => '2026-08-12 09:00:00',
                'content' => $this->contentChatbot(),
            ],
            [
                'slug' => 'cuanto-cuesta-tienda-online-a-la-medida',
                'title' => '¿Cuánto cuesta una tienda online a la medida en 2026? (y cuándo no usar Shopify)',
                'category' => 'desarrollo',
                'tags' => 'tienda online a la medida, cuanto cuesta un ecommerce, desarrollo de tienda virtual, shopify vs a la medida, precio tienda online',
                'excerpt' => '¿Cuánto cuesta una tienda online a la medida? Rangos reales en 2026, qué determina el precio, los costos ocultos de las plantillas y cuándo conviene Shopify y cuándo no.',
                'meta_title' => 'Cuánto Cuesta una Tienda Online a la Medida (2026) | MY Tech',
                'meta_description' => '¿Cuánto cuesta una tienda online a la medida en 2026? Precios reales, qué eleva el costo, los costos ocultos de Shopify y cuándo conviene un e-commerce a la medida.',
                'focus_keyword' => 'cuánto cuesta una tienda online a la medida',
                'published_at' => '2026-08-11 09:00:00',
                'content' => $this->contentEcommerce(),
            ],
            [
                'slug' => 'software-a-la-medida-vs-enlatado',
                'title' => 'Software a la medida vs. software enlatado: cuál le conviene a tu empresa',
                'category' => 'desarrollo',
                'tags' => 'software a la medida, software enlatado, desarrollo de software a la medida, saas erp crm a la medida, software empresarial',
                'excerpt' => 'Software a la medida o enlatado: cuál conviene según tu operación, presupuesto y planes de crecimiento. Ventajas, desventajas y cuándo cada uno tiene sentido.',
                'meta_title' => 'Software a la Medida vs. Enlatado: ¿Cuál Elegir? | MY Tech',
                'meta_description' => 'Software a la medida vs. enlatado: comparamos costos, control, escalabilidad e integraciones para que sepas cuál le conviene a tu empresa (con ejemplos reales).',
                'focus_keyword' => 'software a la medida vs enlatado',
                'published_at' => '2026-08-10 09:00:00',
                'content' => $this->contentSoftware(),
            ],
        ];
    }

    protected function contentChatbot(): string
    {
        return <<<'HTML'
<p>En Latinoamérica, WhatsApp no es un canal más: es <em>el</em> canal. Tus clientes te escriben por ahí a toda hora esperando respuesta inmediata. Un <strong>chatbot con inteligencia artificial para WhatsApp</strong> puede atender, resolver dudas, cobrar y agendar 24/7 sin que tengas que estar pegado al teléfono. La pregunta que todos hacen es: ¿cuánto cuesta?</p>
<p>La respuesta honesta es «depende», pero no de forma vaga. En esta guía te explicamos exactamente <strong>de qué depende el precio</strong>, los rangos reales en 2026 y por qué no todos los «chatbots» valen lo mismo.</p>

<h2>Qué es (y qué no es) un chatbot con IA para WhatsApp</h2>
<p>Hay que distinguir dos cosas que suelen venderse con el mismo nombre:</p>
<ul>
<li><strong>Bot de flujos (botones):</strong> sigue un árbol rígido de «pulse 1, pulse 2». Es barato, pero frustra al cliente en cuanto se sale del guion.</li>
<li><strong>Asistente con IA:</strong> entiende lenguaje natural (con modelos como Claude de Anthropic), responde con <strong>la información real de tu negocio</strong>, mantiene el hilo de la conversación y lleva al cliente a comprar o agendar. Es lo que la gente imagina cuando piensa en «un chatbot inteligente».</li>
</ul>
<p>Nosotros desarrollamos justamente lo segundo: <a href="/chatbots-ia-whatsapp">chatbots con IA para WhatsApp a la medida</a> que atienden, cobran y agendan. Esa diferencia es la que más pesa en el precio.</p>

<h2>De qué depende el precio</h2>
<p>El costo de un chatbot con IA para WhatsApp se mueve según cinco factores:</p>
<ul>
<li><strong>Base de conocimiento:</strong> cuánta información de tu negocio tiene que manejar (servicios, precios, políticas, preguntas frecuentes).</li>
<li><strong>Integraciones:</strong> ¿solo responde, o también cobra por Mercado Pago, valida el pago y agenda en Google Calendar? Cada integración suma.</li>
<li><strong>CRM y embudo:</strong> si quieres que cada conversación se convierta en un cliente dentro de un pipeline que puedas gestionar.</li>
<li><strong>Normativa:</strong> sectores como salud o finanzas exigen que el bot nunca invente precios ni prometa resultados. Eso requiere reglas específicas.</li>
<li><strong>Idiomas y volumen:</strong> multi-idioma y alto volumen de conversaciones pueden cambiar la arquitectura.</li>
</ul>

<h2>Rangos de precio reales en 2026</h2>
<p>Con esos factores en mente, estos son los rangos que verás en el mercado:</p>
<ul>
<li><strong>Bot de flujos genérico:</strong> desde USD 100–300. Sirve para responder «horarios y ubicación», poco más.</li>
<li><strong>Asistente con IA a la medida:</strong> desde <strong>USD 900 (~$3.600.000 COP)</strong>, y escala según integraciones (pagos, agenda, CRM) y automatizaciones. Es una inversión, no una suscripción eterna: el sistema es tuyo.</li>
<li><strong>Plataformas por suscripción (tipo Go High Level):</strong> mensualidades de USD 50–500 «para siempre», y aun así no se adaptan del todo a tu operación ni a tu normativa.</li>
</ul>
<p>La clave: un asistente a la medida se paga una vez y se ajusta exactamente a tu negocio; una plantilla se alquila indefinidamente y tú te adaptas a ella.</p>

<h2>Bot genérico vs. asistente a la medida</h2>
<p>Un bot genérico es rápido de montar, pero tiene tres problemas: no entiende bien lo que le escriben, puede inventar información, y no se integra de verdad con tus pagos y tu agenda. Un asistente a la medida entiende contexto real, responde <strong>solo con tu información</strong> (sin alucinar) y cierra el ciclo completo: atiende, cobra, valida el pago y agenda.</p>
<p>Lo vimos en la práctica con la <a href="/proyectos/crm-asistente-ia-whatsapp-clinica-jasmin-blanco">Dra. Jasmin Blanco</a>: un CRM con asistente de IA que atiende a los pacientes por WhatsApp, cobra la consulta, valida el pago y agenda la cita, cumpliendo la normativa médica.</p>

<h2>¿Qué debe incluir para no llevarte sorpresas?</h2>
<p>Antes de contratar, verifica que la propuesta incluya:</p>
<ul>
<li>Asistente entrenado con <strong>tu</strong> información (no respuestas genéricas).</li>
<li>Integración con tu número de WhatsApp actual.</li>
<li>Cobro y agendamiento si tu negocio los necesita.</li>
<li>Un panel donde veas las conversaciones, el embudo y las métricas.</li>
<li>Capacitación y soporte, y que el <strong>código sea tuyo</strong>.</li>
</ul>

<h2>¿Vale la pena la inversión?</h2>
<p>Haz la cuenta: ¿cuántos clientes pierdes al mes por responder tarde o no responder? Un asistente que atiende en segundos, 24/7, y que además cobra y agenda, se paga solo cuando recupera esos clientes que hoy se van con la competencia que contestó primero.</p>

<h2>En resumen</h2>
<p>Un chatbot con IA para WhatsApp a la medida arranca <strong>desde USD 900</strong> y escala según lo que necesite tu operación. No es un gasto: es un sistema propio que atiende, cobra y agenda por ti. Si quieres ver cómo se vería en tu negocio, mira nuestra página de <a href="/chatbots-ia-whatsapp">chatbots con IA para WhatsApp</a> o escríbenos y te mostramos una demo, sin compromiso.</p>
HTML;
    }

    protected function contentEcommerce(): string
    {
        return <<<'HTML'
<p>Montar una tienda online nunca fue tan fácil… ni tan confuso. En cinco minutos abres una en Shopify, pero después descubres las comisiones, los límites del plan y las funciones que tu negocio necesita y no caben. Por eso muchas empresas terminan preguntándose: <strong>¿cuánto cuesta una tienda online a la medida</strong>, y cuándo vale la pena frente a una plantilla?</p>
<p>Aquí te damos rangos reales de 2026, qué determina el precio y en qué casos Shopify sí es buena idea… y en cuáles no.</p>

<h2>Plantilla vs. tienda a la medida: la diferencia real</h2>
<p>Una <strong>plantilla</strong> (Shopify, Wix, WooCommerce con temas) es un molde que alquilas: rápido de arrancar, pero te adaptas a lo que el plan permite y sueles pagar comisión por venta o apps de pago. Una <strong>tienda a la medida</strong> se desarrolla para tu operación: el código y los datos son tuyos, no pagas comisión por venta, y se integra con lo que ya usas.</p>
<p>Nosotros desarrollamos <a href="/desarrollo-ecommerce">tiendas online a la medida</a> sobre Laravel, con catálogo, pagos, checkout optimizado y SEO técnico de fábrica.</p>

<h2>De qué depende el precio de un e-commerce a la medida</h2>
<ul>
<li><strong>Tamaño del catálogo:</strong> número de productos, variantes, categorías y control de inventario.</li>
<li><strong>Pasarelas de pago:</strong> Stripe, Wompi, Mercado Pago, Sistecrédito, PSE, pago contra entrega. Cada integración suma.</li>
<li><strong>Integraciones:</strong> facturación electrónica (DIAN, SIIGO), transportadoras, ERPs, WhatsApp.</li>
<li><strong>Diseño y experiencia:</strong> un checkout optimizado para convertir no es lo mismo que un tema genérico.</li>
<li><strong>SEO y contenido:</strong> si quieres aparecer en Google y no depender solo de pagar publicidad.</li>
</ul>

<h2>Rangos de precio reales en 2026</h2>
<ul>
<li><strong>Plantilla (Shopify/Wix):</strong> desde USD 30/mes de plan + comisiones + apps. Barato al inicio, caro a largo plazo.</li>
<li><strong>Tienda a la medida:</strong> desde <strong>USD 1.200 (~$4.800.000 COP)</strong> para una tienda que vende, y escala según catálogo, pasarelas e integraciones. Se cotiza por fases.</li>
<li><strong>Plataformas complejas / marketplaces:</strong> a partir de ahí, según reglas de negocio, múltiples vendedores, comisiones, etc.</li>
</ul>

<h2>Los costos ocultos de las plantillas</h2>
<p>El precio de lista de una plantilla engaña. Suma la <strong>comisión por cada venta</strong>, las apps de pago mensuales, el tema premium, y el hecho de que <strong>nunca eres dueño</strong> de la plataforma. Con el tiempo, muchas tiendas pagan más en renta y comisiones de lo que habría costado una tienda propia.</p>

<h2>¿Cuándo sí usar Shopify y cuándo no?</h2>
<p><strong>Shopify tiene sentido</strong> si estás validando una idea, vendes pocos productos estándar y no necesitas integraciones especiales. <strong>Una tienda a la medida tiene sentido</strong> si ya vendes en serio, necesitas integrarte con tu facturación o tu ERP, quieres SEO real, o las comisiones ya te están comiendo el margen.</p>
<p>Un ejemplo: <a href="/proyectos/nuvion-glass">Nuvion Glass</a> (México) es un e-commerce de lentes con filtro de luz azul que desarrollamos a la medida, con checkout de Stripe y SEO, hoy en producción.</p>

<h2>¿Qué debe incluir tu tienda a la medida?</h2>
<ul>
<li>Catálogo, carrito y checkout optimizado (móvil primero).</li>
<li>Las pasarelas de pago que uses + pago contra entrega.</li>
<li>Panel de administración propio, sin comisión por venta.</li>
<li>SEO técnico (URLs limpias, velocidad, schema de producto).</li>
<li>Capacitación, soporte y el código de tu lado.</li>
</ul>

<h2>En resumen</h2>
<p>Una tienda online a la medida arranca <strong>desde USD 1.200</strong> y crece contigo, sin comisiones por venta ni límites de plantilla. Si quieres dejar de pagarle renta a una plataforma y tener tu propio e-commerce, mira nuestra página de <a href="/desarrollo-ecommerce">desarrollo de tiendas online a la medida</a> o cuéntanos qué vendes y te mostramos cómo se vería.</p>
HTML;
    }

    protected function contentSoftware(): string
    {
        return <<<'HTML'
<p>Toda empresa que crece llega al mismo punto: las hojas de cálculo se quedan cortas y el software «enlatado» que compró ya no encaja con cómo trabaja. Ahí aparece la decisión: ¿seguir adaptándote a un producto genérico o mandar a hacer un <strong>software a la medida</strong>? Esta guía compara ambas opciones sin humo, para que sepas cuál le conviene a tu empresa.</p>

<h2>Qué es cada uno</h2>
<ul>
<li><strong>Software enlatado:</strong> un producto genérico que compras o alquilas por suscripción (un ERP, un CRM, una plataforma «todo en uno»). Tú te adaptas a lo que trae.</li>
<li><strong>Software a la medida:</strong> un sistema desarrollado específicamente para tu operación (una plataforma SaaS, un ERP, un CRM, un panel). Se ajusta a tu proceso, no al revés.</li>
</ul>
<p>Nosotros desarrollamos <a href="/software-a-la-medida">software a la medida</a> sobre Laravel: plataformas SaaS, ERPs, CRMs y portales para empresas de LATAM, USA y Europa.</p>

<h2>Las ventajas del software a la medida</h2>
<ul>
<li><strong>Se ajusta a tu proceso real:</strong> no pagas por módulos que no usas ni te falta lo que sí necesitas.</li>
<li><strong>El código y los datos son tuyos:</strong> no dependes de los planes ni de las decisiones de un proveedor externo.</li>
<li><strong>Escala sin límites de licencia:</strong> no pagas «por usuario» ni «por módulo» para siempre.</li>
<li><strong>Se integra con lo que ya usas:</strong> facturación (DIAN, SIIGO), pagos, WhatsApp, ERPs, cualquier API.</li>
<li><strong>Automatiza y reduce errores:</strong> elimina el copiar y pegar entre herramientas.</li>
</ul>

<h2>Las ventajas del software enlatado</h2>
<p>Seamos justos: el enlatado también tiene su lugar. Es <strong>más rápido de arrancar</strong> (lo instalas y ya), tiene un <strong>costo inicial bajo</strong> y sirve cuando tu proceso es estándar y no necesitas nada particular. Si eres un negocio pequeño con necesidades muy comunes, un enlatado puede ser suficiente.</p>

<h2>Cuándo conviene cada uno</h2>
<p><strong>Elige enlatado</strong> si: tu proceso es estándar, el presupuesto inicial es muy ajustado y no necesitas integraciones especiales.</p>
<p><strong>Elige a la medida</strong> si: tu operación tiene lógica propia, el enlatado te obliga a trabajar «como no trabajas», necesitas integrarte con otros sistemas, o las licencias mensuales ya se están volviendo caras a medida que creces.</p>

<h2>El mito del precio</h2>
<p>Se cree que el software a la medida es carísimo. La realidad en 2026: un panel o un MVP a la medida arranca <strong>desde USD 1.500 (~$6.000.000 COP)</strong> y se cotiza por fases. Empiezas con lo esencial y escalas con más módulos cuando el negocio lo pida. Frente a años de suscripciones de un enlatado que nunca es tuyo, la cuenta suele salir a favor de lo propio.</p>

<h2>Ejemplos reales</h2>
<p>Estos son algunos sistemas a la medida que hoy usan empresas todos los días:</p>
<ul>
<li><a href="/proyectos/talent-map-gestion-talento-nom035">Talent Map</a> — plataforma SaaS de gestión de talento humano (México), con cumplimiento de la NOM-035.</li>
<li><a href="/proyectos/sinden-ordenes-produccion">Sinden</a> — ERP de gestión de órdenes y producción (Colombia).</li>
<li><a href="/proyectos/clc-facturacion-electronica">CLC &amp; CIA</a> — plataforma de facturación electrónica DIAN.</li>
</ul>

<h2>En resumen</h2>
<p>El enlatado es rápido y barato al inicio, pero te adaptas tú. El software a la medida es una inversión que se ajusta a tu empresa, es tuyo y escala contigo. Si tu operación ya se siente apretada en herramientas genéricas, mira nuestra página de <a href="/software-a-la-medida">software a la medida</a> o cuéntanos qué necesitas y te proponemos cómo resolverlo.</p>
HTML;
    }
}
