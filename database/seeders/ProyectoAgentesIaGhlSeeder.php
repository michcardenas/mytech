<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use Illuminate\Database\Seeder;

/**
 * Proyecto de portafolio: agentes de IA multicanal construidos en GoHighLevel
 * (chat web real + WhatsApp y voz simulados) para una agencia de marketing.
 *
 * Idempotente: updateOrCreate por slug, correrlo N veces no duplica.
 *
 * NOTA DE HONESTIDAD: el negocio del demo ("Centro de Psicología Almara") es
 * FICTICIO y así se declara en el contenido. El cliente real es una agencia de
 * marketing y se mantiene anónimo. No se enlaza la demo porque vive en un
 * dominio del cliente.
 */
class ProyectoAgentesIaGhlSeeder extends Seeder
{
    public function run(): void
    {
        $slug = 'agentes-ia-multicanal-gohighlevel';

        Proyecto::updateOrCreate(
            ['slug' => $slug],
            [
                // ── Básicos ─────────────────────────────────────
                'nombre' => 'Agentes de IA Multicanal en GoHighLevel — Chat Web, WhatsApp y Voz',
                'pais' => 'España',
                'bandera_emoji' => '🇪🇸',
                'categoria' => 'admin',
                'badge_text' => 'Agentes IA · GoHighLevel',
                'descripcion' => 'Tres agentes de IA (chat web, WhatsApp y voz) con un mismo cerebro y una base de conocimiento intercambiable, construidos sobre GoHighLevel para una agencia de marketing.',
                'url' => null,
                'logo' => 'proyectos/og-images/agentes-ia-multicanal-gohighlevel.jpg',
                'alt_logo' => 'Agentes de IA multicanal en GoHighLevel: chat web, WhatsApp y voz',
                'tecnologias' => [
                    'GoHighLevel',
                    'Conversation AI',
                    'Knowledge Base (RAG)',
                    'OpenAI GPT-4.1',
                    'Chat Widget',
                    'Funnels & Calendars',
                    'Web Speech API',
                    'JavaScript',
                    'HTML / CSS',
                    'DNS / CNAME',
                    'Inteligencia Artificial',
                ],
                'estado' => 'en_vivo',
                'destacado' => true,
                'orden' => 1,
                'activo' => true,

                // ── SEO ─────────────────────────────────────────
                'focus_keyword' => 'agentes de ia multicanal',
                'secondary_keywords' => [
                    'chatbot con ia en gohighlevel',
                    'agente de voz con inteligencia artificial',
                    'agente de ia para whatsapp',
                    'automatizacion con ia para agencias',
                    'conversation ai gohighlevel',
                ],
                'excerpt' => 'Tres agentes de IA (chat web, WhatsApp y voz) que comparten un mismo cerebro y una base de conocimiento intercambiable, construidos sobre GoHighLevel para una agencia de marketing. Cambiar de sector es reemplazar un documento, no rehacer el bot.',
                'canonical_url' => 'https://mytechsolutionsco.com/proyectos/'.$slug,
                'robots' => 'index,follow',
                'meta_title' => 'Agentes de IA Multicanal en GoHighLevel: Chat, WhatsApp y Voz | MY Tech',
                'meta_description' => 'Caso: tres agentes de IA con un mismo cerebro y base de conocimiento intercambiable (chat web, WhatsApp y voz) sobre GoHighLevel, con reserva de citas y límites de seguridad.',
                'meta_keywords' => 'agentes de ia multicanal, chatbot gohighlevel, conversation ai, agente de voz ia, base de conocimiento ia, automatizacion agencias marketing',

                // ── Open Graph / Twitter ────────────────────────
                'og_image' => 'proyectos/og-images/agentes-ia-multicanal-gohighlevel.jpg',
                'alt_og_image' => 'Agentes de IA multicanal en GoHighLevel: chat web, WhatsApp y voz',
                'twitter_image' => 'proyectos/og-images/agentes-ia-multicanal-gohighlevel.jpg',
                'og_title' => 'Agentes de IA Multicanal en GoHighLevel — Chat Web, WhatsApp y Voz',
                'og_description' => 'Un solo cerebro, tres canales y una base de conocimiento que se cambia como quien cambia un documento. Caso de MY Tech Solutions.',
                'og_type' => 'article',
                'twitter_card' => 'summary_large_image',
                'twitter_title' => 'Agentes de IA Multicanal en GoHighLevel',
                'twitter_description' => 'Chat web, WhatsApp y voz con el mismo cerebro y una KB intercambiable, sobre GoHighLevel.',

                // ── Schema ──────────────────────────────────────
                'schema_type' => 'CreativeWork',

                // ── Metadata ────────────────────────────────────
                'breadcrumb_title' => 'Agentes de IA Multicanal en GoHighLevel',
                'reading_time' => 5,
                'publicado_en' => '2026-08-18',
                'industria' => 'MarTech / Automatización con IA',
                'client_size' => 'pyme',

                // ── Métricas ────────────────────────────────────
                'duracion_desarrollo' => '5 días',
                'equipo_size' => 1,
                'fecha_lanzamiento' => '2026-08-18',

                // ── Contenido ───────────────────────────────────
                'descripcion_extendida' => '<h2>El proyecto</h2>'
                    .'<p>Una agencia de marketing necesitaba <strong>enseñar a sus propios clientes</strong> qué puede hacer hoy un agente de inteligencia artificial. No quería una presentación con capturas: quería tres demos funcionales que se pudieran tocar, en los tres canales por los que la gente realmente escribe y llama.</p>'
                    .'<p>Desarrollamos tres agentes &mdash; <strong>chat web, WhatsApp y voz</strong> &mdash; que comparten un mismo cerebro y una misma base de conocimiento, centralizados en <strong>GoHighLevel</strong>. El agente atiende, responde con la información del negocio, reconoce cuándo la conversación es delicada y ofrece reservar una cita en un calendario real.</p>'
                    .'<p>El negocio usado en la demostración es <strong>ficticio</strong> (un centro de psicología inventado para el ejercicio). Fue una decisión de diseño: obliga al agente a sostener límites serios &mdash; no diagnosticar, no dar consejo clínico, no inventar &mdash; que son justo los que preocupan a cualquier empresa que se plantea poner una IA a hablar con sus clientes.</p>',

                'desafio' => '<h2>El desafío</h2>'
                    .'<p>Vender IA con diapositivas no convence a nadie. Pero montar tres agentes reales en tres canales distintos trae cuatro problemas a la vez:</p>'
                    .'<ul>'
                    .'<li><strong>Tres canales que se contradicen.</strong> Si cada agente se configura por separado, el de WhatsApp responde una cosa y el de voz otra. La demo pierde toda credibilidad.</li>'
                    .'<li><strong>El demo sirve para un solo sector.</strong> Un bot escrito a medida de un negocio no se puede reutilizar: para mostrárselo al siguiente cliente hay que rehacerlo entero.</li>'
                    .'<li><strong>Costos recurrentes que se disparan.</strong> WhatsApp oficial y la voz por teléfono implican verificación de Meta, número real y coste por minuto. Para una demostración, es gasto puro.</li>'
                    .'<li><strong>Un sector sensible.</strong> Con temas de salud mental, un agente que improvise no es un fallo simpático: es un problema. Había que garantizar que nunca diagnosticara ni inventara.</li>'
                    .'</ul>',

                'solucion' => '<h2>La solución</h2>'
                    .'<h3>Un solo cerebro para los tres canales</h3>'
                    .'<p>En lugar de tres bots, construimos <strong>un agente</strong> con una base de conocimiento única y lo expusimos en tres canales. El chat web corre de verdad sobre <strong>Conversation AI de GoHighLevel</strong>, conectado a una Knowledge Base entrenada con la información del negocio. WhatsApp y voz se resolvieron como <strong>simulaciones fieles</strong> que replican el mismo comportamiento, sin número real ni telefonía. Misma información, mismo tono, cero coste recurrente.</p>'
                    .'<h3>Arquitectura en tres capas intercambiables</h3>'
                    .'<p>La pieza que hace reutilizable el trabajo. El agente se separó en tres capas:</p>'
                    .'<ul>'
                    .'<li><strong>Capa 1 &mdash; comportamiento base (fija):</strong> el tono cercano, el flujo de reserva y la regla de oro &laquo;si no lo sé, derivo a una persona&raquo;.</li>'
                    .'<li><strong>Capa 2 &mdash; el negocio (intercambiable):</strong> nombre, servicios, precios, horarios y preguntas frecuentes viven en <strong>un solo documento</strong>. Cambiar de sector es reemplazar ese documento.</li>'
                    .'<li><strong>Capa 3 &mdash; límites del sector (ajustable):</strong> en psicología, no diagnosticar; en otro sector, se retoca esa capa y nada más.</li>'
                    .'</ul>'
                    .'<h3>Límites de seguridad y manejo de conversaciones sensibles</h3>'
                    .'<p>El agente responde únicamente con la información cargada: no inventa precios ni promete resultados. Y ante un mensaje que sugiere una crisis, no intenta resolverlo: responde con empatía, no da consejo clínico y <strong>deriva a los teléfonos de emergencia</strong> antes de continuar.</p>'
                    .'<h3>Reserva de citas real</h3>'
                    .'<p>La conversación no muere en el &laquo;te informamos&raquo;. El agente consulta disponibilidad en un calendario conectado y agenda la cita dentro de la misma charla.</p>'
                    .'<h3>Entrega para que el cliente lo maneje solo</h3>'
                    .'<p>El proyecto se entregó con los prompts documentados, una guía de administración, un guion de video instructivo, control total de la cuenta y un desglose escrito de los costos recurrentes reales.</p>',

                'resultados' => '<h2>Resultados</h2>'
                    .'<ul>'
                    .'<li><strong>Tres canales con una sola verdad:</strong> chat web, WhatsApp y voz responden lo mismo porque beben de la misma base de conocimiento.</li>'
                    .'<li><strong>Cambiar de sector = cambiar un documento.</strong> La agencia puede reutilizar la demo con clientes de otros rubros sin volver a construir el agente.</li>'
                    .'<li><strong>Coste recurrente casi nulo:</strong> al simular WhatsApp y voz, el gasto se reduce al consumo de IA del chat &mdash; céntimos por conversación &mdash; en vez de números de teléfono y minutos facturados.</li>'
                    .'<li><strong>Chat en vivo sobre dominio propio del cliente</strong>, publicado y funcionando en español.</li>'
                    .'<li><strong>Entregado en 5 días</strong>, con documentación y traspaso completo de accesos.</li>'
                    .'</ul>'
                    .'<p><em>Nota: se trata de un entorno de demostración con un negocio ficticio. Las cifras de uso corresponden al proyecto entregado, no a operación comercial real.</em></p>',

                'faqs' => [
                    [
                        'pregunta' => '¿Se puede tener un mismo agente de IA en chat web, WhatsApp y voz?',
                        'respuesta' => 'Sí, y es la forma correcta de hacerlo. En lugar de configurar un bot por canal —que acaba dando respuestas distintas en cada uno—, se construye un solo agente con una base de conocimiento compartida y se expone en los tres canales. Así el cliente recibe la misma información y el mismo tono escriba por donde escriba, y cuando actualizas un precio lo actualizas una vez.',
                    ],
                    [
                        'pregunta' => '¿Qué es GoHighLevel y por qué se usó para este proyecto?',
                        'respuesta' => 'GoHighLevel es una plataforma de marketing y CRM muy usada por agencias, que incluye Conversation AI (agentes conversacionales), bases de conocimiento, calendarios y funnels en un mismo lugar. Se eligió porque el cliente ya trabajaba sobre ella: permitía centralizar el agente, la base de conocimiento y la reserva de citas sin sumar herramientas externas.',
                    ],
                    [
                        'pregunta' => '¿Cómo se evita que un agente de IA invente información o dé consejos que no debe?',
                        'respuesta' => 'Con dos mecanismos. El primero es la base de conocimiento: el agente responde solo con la información cargada y, cuando no la tiene, lo dice y deriva a una persona en vez de improvisar. El segundo son los límites explícitos del sector, definidos en el prompt: en este caso, no diagnosticar ni dar consejo clínico. Ante un mensaje que sugiere una crisis, el agente responde con empatía y deriva a los teléfonos de emergencia.',
                    ],
                    [
                        'pregunta' => '¿Se puede adaptar el mismo agente a otro tipo de negocio?',
                        'respuesta' => 'Sí, y para eso se diseñó la arquitectura en tres capas. El comportamiento base se mantiene, los datos del negocio viven en un único documento intercambiable (servicios, precios, horarios, preguntas frecuentes) y los límites del sector se retocan según el rubro. Cambiar de un centro de psicología a una clínica dental o a una inmobiliaria es reemplazar ese documento, no reconstruir el agente.',
                    ],
                    [
                        'pregunta' => '¿Cuánto cuesta mantener un agente de IA como este?',
                        'respuesta' => 'El coste depende de qué canales sean reales. El chat web se paga por consumo de IA, que en volúmenes normales son céntimos por conversación. WhatsApp oficial y la voz por teléfono son la parte cara: implican verificación de Meta, un número provisionado y coste por minuto. Por eso en este proyecto se dejaron como simulaciones fieles, reduciendo el coste recurrente a prácticamente cero sin perder capacidad de demostración.',
                    ],
                    [
                        'pregunta' => '¿El negocio del demo es un cliente real?',
                        'respuesta' => 'No. El centro de psicología usado en la demostración es un negocio ficticio, creado a propósito para el ejercicio. Se eligió un sector sensible justamente porque obliga al agente a sostener límites estrictos —no diagnosticar, no inventar, derivar ante una crisis—, que son los que de verdad preocupan a una empresa antes de poner una IA a hablar con sus clientes.',
                    ],
                ],
            ]
        );

        $this->command?->info('Proyecto listo: /proyectos/'.$slug);
    }
}
