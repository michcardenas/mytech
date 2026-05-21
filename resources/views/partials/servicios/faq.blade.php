{{-- ═══════════════════════════════════════════════════════════════════
     /servicios — FAQ section + FAQPage schema
     Schema y contenido visible deben coincidir (requisito Google).
     ═══════════════════════════════════════════════════════════════════ --}}

@php
    /* Las 6 FAQ están en BD para que se editen desde admin sin tocar código.
       Fallback: defaults sólidos optimizados para preguntas long-tail. */
    $sc = isset($page) && $page && $page->content ? (json_decode($page->content, true) ?? []) : [];

    $faqs = [
        [
            'q' => $sc['serv_faq_1_q'] ?? '¿Cuánto cuesta desarrollar software a medida en Colombia y LATAM?',
            'a' => $sc['serv_faq_1_a'] ?? 'El precio depende del alcance: una web profesional con CMS arranca desde USD 500, un e-commerce o web compleja desde USD 1.000, una app web a medida o sistema interno desde USD 2.000, y un SaaS multi-tenant o plataforma con integraciones desde USD 4.500. Te damos un presupuesto cerrado con alcance, tiempos y precio en menos de 24 horas — sin sorpresas en factura.',
        ],
        [
            'q' => $sc['serv_faq_2_q'] ?? '¿Cuánto tarda construir una plataforma desde cero?',
            'a' => $sc['serv_faq_2_a'] ?? 'Una landing profesional tarda 2-3 semanas. Una web corporativa con CMS, 4-6 semanas. Una app web a medida o sistema interno, entre 8 y 12 semanas. Un SaaS multi-tenant o plataforma con integraciones complejas (SIIGO, Amazon DSP, etc.), entre 12 y 16 semanas. Trabajamos por sprints de 2 semanas con entregas demostrables en cada uno.',
        ],
        [
            'q' => $sc['serv_faq_3_q'] ?? '¿Por qué desarrollar a medida en lugar de usar un SaaS como Shopify, HubSpot o WordPress?',
            'a' => $sc['serv_faq_3_a'] ?? 'Si tu negocio tiene procesos únicos, complejos o no estandarizables (gestión interna específica, integraciones particulares, lógica de negocio propia), un SaaS terminará costándote más en mensualidades, limitaciones y workarounds. Lo a medida es dueño tu código, sin tope de usuarios, sin pagos perpetuos, y se moldea exactamente a tu operación. Si tu caso es estándar, te lo decimos honestamente y te recomendamos el SaaS — no te vendemos lo que no necesitas.',
        ],
        [
            'q' => $sc['serv_faq_4_q'] ?? '¿Qué stack tecnológico usan y por qué?',
            'a' => $sc['serv_faq_4_a'] ?? 'Stack maduro y probado: Laravel + Vue/Inertia para web y backends, React Native + Expo para apps móviles iOS/Android, MySQL/PostgreSQL para datos, Supabase cuando aplica serverless. Para IA usamos OpenAI/Anthropic APIs e integramos n8n para automatizaciones. Elegimos tecnología aburrida y estable que cualquier dev pueda mantener en 5 años, no la moda del mes.',
        ],
        [
            'q' => $sc['serv_faq_5_q'] ?? '¿Trabajan con empresas fuera de Colombia?',
            'a' => $sc['serv_faq_5_a'] ?? 'Sí. Tenemos clientes activos en 11 países: Colombia, Argentina, Chile, México, Guatemala, Costa Rica, España, Estados Unidos, República Dominicana, Ecuador, Uruguay y Australia. Trabajamos en español e inglés, asíncrono cuando funciona y reuniones en vivo cuando hace falta. Tu zona horaria es el límite, no la nuestra.',
        ],
        [
            'q' => $sc['serv_faq_6_q'] ?? '¿Qué pasa después de entregar el proyecto? ¿Hay mantenimiento?',
            'a' => $sc['serv_faq_6_a'] ?? 'Cada proyecto se entrega con documentación completa, código limpio y un período de soporte sin costo. Después puedes contratar mantenimiento mensual (bug fixes + actualizaciones de seguridad + iteraciones pequeñas), o contratar bloques de horas para evoluciones más grandes. Tu código es tuyo: lo entregamos en tu repositorio y puedes seguir con cualquier otro equipo si así lo decides.',
        ],
    ];
@endphp

{{-- ─── Visible FAQ ─── --}}
<section class="mt-serv-faq" data-serv-faq>
    <div class="mt-container">
        <header class="mt-serv-faq-head">
            <span class="mt-eyebrow-gray">[ Preguntas frecuentes ]</span>
            <h2 class="text-section font-display font-bold text-mt-text mt-3 text-balance">
                {{ $sc['serv_faq_title'] ?? 'Lo que más nos preguntan' }}
                <span class="text-mt-accent italic">{{ $sc['serv_faq_title_accent'] ?? 'antes de empezar.' }}</span>
            </h2>
            <p class="mt-serv-faq-sub">
                {{ $sc['serv_faq_subtitle'] ?? 'Las respuestas honestas a lo que toda empresa pregunta antes de contratar desarrollo a medida.' }}
            </p>
        </header>

        <div class="mt-serv-faq-list" x-data="{ open: null }">
            @foreach($faqs as $i => $faq)
                <article class="mt-serv-faq-item" :class="{ 'is-open': open === {{ $i }} }">
                    <button type="button"
                            class="mt-serv-faq-q"
                            @click="open = (open === {{ $i }} ? null : {{ $i }})"
                            :aria-expanded="open === {{ $i }}"
                            aria-controls="faq-a-{{ $i }}">
                        <span class="mt-serv-faq-q-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="mt-serv-faq-q-text">{{ $faq['q'] }}</span>
                        <span class="mt-serv-faq-q-icon" aria-hidden="true"></span>
                    </button>
                    <div class="mt-serv-faq-a"
                         id="faq-a-{{ $i }}"
                         x-show="open === {{ $i }}"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── JSON-LD FAQPage schema (debe coincidir con contenido visible arriba) ─── --}}
@push('head_extras')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text'  => $f['a'],
        ],
    ], $faqs),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush
