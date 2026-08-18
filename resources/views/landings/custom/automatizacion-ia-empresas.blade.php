@extends('layouts.app-home')

{{--
    Landing: Automatización con IA para empresas.
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = automatizacion-ia-empresas), editable desde /admin/seo/{id}/edit.
    Aquí solo va el CONTENIDO + el FAQPage (via head_extras, para no duplicarse
    con el @graph principal que el layout limpia de FAQPage).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, quiero automatizar procesos de mi empresa con IA.');
    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . $waMsg;
    $casoUrl = url('/proyectos/proteccion-laboral');

    $faqs = [
        [
            'q' => '¿Qué es la automatización con IA para empresas?',
            'a' => 'Es integrar inteligencia artificial dentro de tu operación para que haga el trabajo repetitivo que hoy consume horas de tu equipo: leer y clasificar correos, redactar documentos, interpretar contratos, resumir casos y extraer datos. No es un chatbot: es software que trabaja dentro de tus herramientas actuales y bajo tus reglas.',
        ],
        [
            'q' => '¿En qué se diferencia de usar una IA genérica en el navegador?',
            'a' => 'Una herramienta genérica te obliga a copiar y pegar, no conoce tu operación y no deja rastro en tus sistemas. Nosotros conectamos la IA directamente a tu correo, tus documentos y tu base de datos, con las reglas de tu negocio, permisos por rol y trazabilidad de cada acción. El resultado queda dentro de tu plataforma, no en una ventana de chat.',
        ],
        [
            'q' => '¿Qué procesos se pueden automatizar con IA?',
            'a' => 'Los que son repetitivos y se basan en texto o documentos: clasificar y responder correos, redactar contratos y minutas a partir de plantillas, interpretar cláusulas, resumir expedientes o historiales largos, extraer datos de PDFs y facturas, generar informes y alimentar tu CRM. Empezamos por el proceso que más horas te está costando.',
        ],
        [
            'q' => '¿Se conecta con las herramientas que ya uso?',
            'a' => 'Sí. Trabajamos con Gmail y Google Workspace (correo, Drive, Calendar), bases de datos, tu ERP o CRM y cualquier servicio con API. En el caso de Protección Laboral integramos Gmail API y Google Drive API para que la IA leyera los correos y trabajara sobre los documentos reales de la firma.',
        ],
        [
            'q' => '¿La IA puede equivocarse o inventar información?',
            'a' => 'Por eso la construimos con límites. La IA responde fundamentada solo en tus documentos y tus datos, no en conocimiento general de internet. Y en los procesos sensibles dejamos siempre un paso de revisión humana: la IA prepara el borrador, una persona aprueba antes de que salga. Tú decides qué se automatiza del todo y qué requiere visto bueno.',
        ],
        [
            'q' => '¿Cuánto cuesta y en cuánto tiempo se ve el resultado?',
            'a' => 'Un proyecto de automatización a la medida arranca desde aproximadamente USD 1.200 (~$4.800.000 COP) y escala según los procesos e integraciones. Cotizamos por fases: empezamos automatizando un solo proceso para que veas el ahorro real en semanas, y desde ahí ampliamos.',
        ],
    ];

    $capacidades = [
        ['icon' => 'mail', 'title' => 'Lee y clasifica tus correos', 'desc' => 'Conectada a Gmail, la IA identifica de qué se trata cada correo, lo asocia al caso o cliente correcto y prepara la respuesta.'],
        ['icon' => 'doc', 'title' => 'Redacta documentos', 'desc' => 'Genera contratos, minutas, informes y respuestas a partir de tus plantillas y de los datos reales que ya viven en tu sistema.'],
        ['icon' => 'scan', 'title' => 'Interpreta contratos y PDFs', 'desc' => 'Lee documentos largos, extrae cláusulas, fechas, montos y obligaciones, y te avisa de lo que requiere atención.'],
        ['icon' => 'summary', 'title' => 'Resume casos e historiales', 'desc' => 'Convierte expedientes de cientos de páginas en un resumen accionable que tu equipo lee en dos minutos.'],
        ['icon' => 'shield', 'title' => 'Con tus reglas y permisos', 'desc' => 'Responde solo con tu información, respeta los permisos por rol y deja trazabilidad de cada acción automatizada.'],
        ['icon' => 'plug', 'title' => 'Dentro de tus herramientas', 'desc' => 'Se integra con Gmail, Drive, tu ERP, tu CRM y cualquier API. No cambias de plataforma: la IA entra a la tuya.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Mapeamos dónde se va el tiempo', 'desc' => 'Revisamos contigo los procesos del día a día y elegimos el que más horas consume y más se puede automatizar. Ahí empezamos.'],
        ['num' => '02', 'title' => 'Conectamos tus fuentes', 'desc' => 'Enlazamos correo, documentos, base de datos y las herramientas que ya usas, para que la IA trabaje con información real y no con supuestos.'],
        ['num' => '03', 'title' => 'Definimos reglas y controles', 'desc' => 'Establecemos qué puede hacer sola, qué requiere aprobación humana, quién ve qué y cómo queda registrada cada acción.'],
        ['num' => '04', 'title' => 'En producción y midiendo', 'desc' => 'Queda operando dentro de tu plataforma. Medimos las horas ahorradas y ampliamos a los siguientes procesos.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-ai-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-ai-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        Automatización · IA aplicada
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Automatiza con IA el trabajo que <span class="text-mt-accent">te roba el día</span>.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Integramos inteligencia artificial (Claude) dentro de tu operación para que lea tus correos, redacte tus documentos, interprete tus contratos y resuma tus casos &mdash; con tus reglas y dentro de tus herramientas. No es un chatbot: es software a la medida.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'Trabaja dentro de tu correo, tus documentos y tu base de datos',
                        'Solo usa tu información, con permisos por rol y trazabilidad',
                        'Tú decides qué hace sola y qué pasa por revisión humana',
                    ] as $bullet)
                        <li class="flex items-start gap-3 text-mt-text">
                            <span class="flex-shrink-0 w-6 h-6 mt-0.5 rounded-full bg-mt-accent-soft border border-mt-accent-line flex items-center justify-center">
                                <svg class="w-3 h-3 text-mt-accent" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-base md:text-[17px]">{{ $bullet }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-11 flex flex-wrap gap-3.5" data-animate>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-btn-primary">
                        Quiero automatizar con IA
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#caso" class="mt-btn-ghost">
                        Ver un caso real
                    </a>
                </div>
            </div>

            {{-- Mockup: flujo de automatización --}}
            <div class="flex justify-center lg:justify-end" data-animate>
                <div class="mt-ai-flow">
                    <div class="mt-ai-flow-header">
                        <span class="mt-ai-dot" aria-hidden="true"></span>
                        <span class="mt-ai-dot" aria-hidden="true"></span>
                        <span class="mt-ai-dot" aria-hidden="true"></span>
                        <span class="mt-ai-flow-title">Bandeja del equipo</span>
                    </div>

                    <div class="mt-ai-flow-body">
                        <div class="mt-ai-card mt-ai-card-in">
                            <span class="mt-ai-tag">Entra</span>
                            <p class="mt-ai-card-t">Correo del cliente</p>
                            <p class="mt-ai-card-d">&laquo;Adjunto el contrato firmado, ¿me confirman las fechas de preaviso?&raquo; &middot; PDF de 34 págs.</p>
                        </div>

                        <div class="mt-ai-arrow" aria-hidden="true">
                            <span class="mt-ai-arrow-line"></span>
                            <span class="mt-ai-arrow-badge">IA</span>
                            <span class="mt-ai-arrow-line"></span>
                        </div>

                        <div class="mt-ai-card mt-ai-card-out">
                            <span class="mt-ai-tag mt-ai-tag-ok">Sale</span>
                            <p class="mt-ai-card-t">Listo en segundos</p>
                            <ul class="mt-ai-list">
                                <li><span class="mt-ai-check" aria-hidden="true">&#10003;</span> Contrato leído y cláusulas extraídas</li>
                                <li><span class="mt-ai-check" aria-hidden="true">&#10003;</span> Preaviso: 30 días &middot; vence 14 sep</li>
                                <li><span class="mt-ai-check" aria-hidden="true">&#10003;</span> Resumen guardado en el expediente</li>
                                <li><span class="mt-ai-check" aria-hidden="true">&#10003;</span> Borrador de respuesta redactado</li>
                            </ul>
                        </div>

                        <div class="mt-ai-approve">
                            <span class="mt-ai-approve-label">Espera tu visto bueno</span>
                            <span class="mt-ai-approve-btn">Aprobar y enviar</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- TRUST BAR                                                     --}}
{{-- ============================================================= --}}
<section class="bg-mt-bg-2 border-y border-mt-border py-8">
    <div class="mt-container">
        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-mt-text-2">
            <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Construido con</span>
            @foreach ([
                'IA de Claude (Anthropic)',
                'Gmail API',
                'Google Drive API',
                'Laravel',
                'Tu ERP o CRM',
            ] as $item)
                <span class="inline-flex items-center gap-2 text-sm font-medium text-mt-text">
                    <span class="w-1.5 h-1.5 rounded-full bg-mt-accent"></span>{{ $item }}
                </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- PROBLEMA                                                      --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white">
    <div class="mt-container">
        <div class="max-w-3xl" data-animate>
            <span class="mt-eyebrow-gray">El problema</span>
            <h2 class="mt-4 text-section font-display text-mt-text">
                Tu equipo cobra por criterio, no por copiar y pegar.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Leer correos, buscar el documento correcto, revisar cláusulas, redactar lo mismo de siempre, pasar datos de un lado a otro. Son horas todos los días que no se facturan y que agotan a la gente que debería estar resolviendo lo importante.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Horas en tareas repetitivas', 'd' => 'El trabajo mecánico se come el tiempo del trabajo que sí genera valor y se cobra.'],
                ['t' => 'Información dispersa', 'd' => 'El dato está en un correo, en un PDF y en una carpeta de Drive. Nadie lo tiene junto cuando lo necesita.'],
                ['t' => 'Errores por volumen', 'd' => 'Cuando hay que revisar cientos de páginas a mano, algo se pasa por alto. Y sale caro.'],
            ] as $p)
                <div class="rounded-2xl border border-mt-border bg-mt-bg-2 p-6" data-animate>
                    <div class="w-10 h-10 rounded-xl bg-white border border-mt-border flex items-center justify-center text-mt-accent mb-4">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.4 14.55A2 2 0 003.6 21.4h16.8a2 2 0 001.72-3l-8.4-14.55a2 2 0 00-3.44 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-display font-semibold text-mt-text leading-tight">{{ $p['t'] }}</h3>
                    <p class="mt-2 text-mt-text-2 text-[14.5px] leading-relaxed">{{ $p['d'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CAPACIDADES / SOLUCIÓN                                        --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-mt-bg-2 border-t border-mt-border">
    <div class="mt-container">
        <div class="max-w-3xl mb-14" data-animate>
            <span class="mt-eyebrow">La solución</span>
            <h2 class="mt-4 text-section font-display text-mt-text">
                IA que trabaja dentro de tu operación, no en una ventana aparte.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Nada de copiar y pegar en un chat. La inteligencia artificial queda integrada a tus herramientas y hace el trabajo donde ya vive tu información.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($capacidades as $c)
                <div class="group rounded-2xl border border-mt-border bg-white p-6 transition-colors duration-300 hover:border-mt-accent" data-animate>
                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-mt-border bg-white text-mt-text transition-colors duration-300 group-hover:border-mt-accent group-hover:text-mt-accent">
                        @switch($c['icon'])
                            @case('mail')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6"/></svg>
                                @break
                            @case('doc')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V8l-5-5z"/><path stroke-linecap="round" d="M9 13h6M9 17h4"/></svg>
                                @break
                            @case('scan')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V6a2 2 0 012-2h2M16 4h2a2 2 0 012 2v2M20 16v2a2 2 0 01-2 2h-2M8 20H6a2 2 0 01-2-2v-2"/><path stroke-linecap="round" d="M4 12h16"/></svg>
                                @break
                            @case('summary')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 10h16M4 14h10M4 18h6"/></svg>
                                @break
                            @case('shield')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 3v6c0 5-3.5 8.4-8 9-4.5-.6-8-4-8-9V6l8-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12l1.8 1.8L15 10"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7V4h6v3M9 7H7a2 2 0 00-2 2v3a4 4 0 004 4h6a4 4 0 004-4V9a2 2 0 00-2-2h-2M12 16v4"/></svg>
                        @endswitch
                    </span>
                    <h3 class="mt-4 text-lg font-display font-semibold text-mt-text leading-tight">{{ $c['title'] }}</h3>
                    <p class="mt-2 text-mt-text-2 text-[14.5px] leading-relaxed">{{ $c['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CÓMO FUNCIONA                                                 --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-32" data-animate>
                    <span class="mt-eyebrow-gray">Cómo funciona</span>
                    <h2 class="mt-4 text-section font-display text-mt-text">Empezamos por el proceso que más te cuesta.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        No automatizamos todo de golpe. Elegimos un proceso, lo dejamos funcionando y medimos el ahorro antes de seguir.
                    </p>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 mt-8 text-mt-accent hover:gap-3 transition-all text-sm font-mono uppercase tracking-wider">
                        Empezar ahora <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
            <div class="lg:col-span-8 flex flex-col gap-4">
                @foreach ($pasos as $paso)
                    <div class="flex items-start gap-5 rounded-2xl border border-mt-border bg-white p-6 md:p-7 transition-colors duration-300 hover:border-mt-accent" data-animate>
                        <span class="flex-shrink-0 font-display font-semibold text-2xl md:text-3xl text-mt-accent/25 leading-none w-12">{{ $paso['num'] }}</span>
                        <div>
                            <h3 class="text-lg md:text-xl font-display font-semibold text-mt-text leading-tight">{{ $paso['title'] }}</h3>
                            <p class="mt-2 text-mt-text-2 text-[15px] leading-relaxed">{{ $paso['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CASO REAL                                                     --}}
{{-- ============================================================= --}}
<section id="caso" class="relative py-28 md:py-36 bg-mt-bg-dark overflow-hidden scroll-mt-24">
    <div class="mt-container relative z-10">
        <div class="max-w-3xl" data-animate>
            <span class="font-mono text-[11px] md:text-xs uppercase tracking-[0.22em] text-mt-accent-on-dark">Caso real · en producción</span>
            <h2 class="mt-4 text-section font-display text-white">
                Una firma laboralista que redacta e interpreta contratos con IA.
            </h2>
        </div>

        <div class="mt-12 grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7" data-animate>
                <p class="text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                    Para <strong class="text-white">Protección Laboral</strong>, una firma laboralista en Colombia, desarrollamos un software de gestión jurídica a la medida con IA de Claude que <strong class="text-white">automatiza los correos</strong>, <strong class="text-white">redacta documentos</strong>, <strong class="text-white">interpreta contratos</strong> y <strong class="text-white">resume los casos</strong>. La IA trabaja conectada a Gmail y Google Drive, sobre los documentos reales de la firma y con permisos por rol.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ $casoUrl }}" class="mt-btn-primary">
                        Ver el caso completo <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-btn-ghost mt-btn-ghost-on-dark">
                        Quiero algo así
                    </a>
                </div>
            </div>
            <div class="lg:col-span-5 grid grid-cols-2 gap-4" data-animate>
                @foreach ([
                    ['k' => 'Gmail', 'v' => 'Correos automatizados'],
                    ['k' => 'Drive', 'v' => 'Documentos conectados'],
                    ['k' => 'Claude', 'v' => 'IA de Anthropic'],
                    ['k' => 'Por rol', 'v' => 'Permisos y trazabilidad'],
                ] as $m)
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                        <div class="text-2xl md:text-3xl font-display font-semibold text-white leading-none tracking-tight">{{ $m['k'] }}</div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.14em] text-mt-text-on-dark leading-snug">{{ $m['v'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- COMPARATIVA                                                   --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="max-w-3xl mb-12" data-animate>
            <span class="mt-eyebrow-gray">Por qué a la medida</span>
            <h2 class="mt-4 text-section font-display text-mt-text">Copiar y pegar en un chat no es automatizar.</h2>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-mt-border" data-animate>
            <table class="w-full min-w-[640px] text-left border-collapse">
                <thead>
                    <tr class="bg-mt-bg-2 text-mt-text">
                        <th class="p-4 md:p-5 font-mono text-[11px] uppercase tracking-[0.14em] text-mt-text-3 font-medium"></th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-border">Hacerlo a mano</th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-border">IA genérica en el navegador</th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-accent-line bg-mt-accent-soft text-mt-accent">IA integrada a la medida</th>
                    </tr>
                </thead>
                <tbody class="text-[14.5px]">
                    @foreach ([
                        ['t' => 'Trabaja dentro de tu correo y tus documentos', 'mano' => '≈', 'gen' => '✕'],
                        ['t' => 'Usa solo tu información, sin conocimiento inventado', 'mano' => '≈', 'gen' => '✕'],
                        ['t' => 'Deja el resultado guardado en tu sistema', 'mano' => '≈', 'gen' => '✕'],
                        ['t' => 'Permisos por rol y trazabilidad de cada acción', 'mano' => '✕', 'gen' => '✕'],
                        ['t' => 'Paso de aprobación humana donde tú lo definas', 'mano' => '≈', 'gen' => '✕'],
                        ['t' => 'Escala sin sumar más horas de equipo', 'mano' => '✕', 'gen' => '≈'],
                    ] as $i => $fila)
                        <tr class="{{ $i % 2 ? 'bg-mt-bg-2/50' : 'bg-white' }} border-t border-mt-border">
                            <td class="p-4 md:p-5 text-mt-text font-medium">{{ $fila['t'] }}</td>
                            <td class="p-4 md:p-5 border-l border-mt-border text-center">
                                <span class="text-mt-text-3">{{ $fila['mano'] }}</span>
                            </td>
                            <td class="p-4 md:p-5 border-l border-mt-border text-center">
                                <span class="text-mt-text-3">{{ $fila['gen'] }}</span>
                            </td>
                            <td class="p-4 md:p-5 border-l border-mt-accent-line bg-mt-accent-soft/40 text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-mt-accent text-white">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- PRECIOS                                                       --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-mt-bg-2 border-t border-mt-border">
    <div class="mt-container">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-10 items-center">
            <div data-animate>
                <span class="mt-eyebrow-gray">Inversión</span>
                <h2 class="mt-4 text-section font-display text-mt-text">Se paga con las horas que deja de perder tu equipo.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Construimos una solución que es <strong class="text-mt-text">tuya</strong>: tu código, tu plataforma, sin suscripción eterna. Cotizamos por fases, empezando por un solo proceso para que veas el ahorro antes de ampliar.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Diagnóstico del proceso y del tiempo que consume',
                        'Integración con tu correo, documentos y base de datos',
                        'Reglas, permisos por rol y paso de aprobación humana',
                        'Capacitación y soporte para tu equipo',
                    ] as $inc)
                        <li class="flex items-start gap-3 text-mt-text">
                            <span class="flex-shrink-0 w-6 h-6 mt-0.5 rounded-full bg-mt-accent-soft border border-mt-accent-line flex items-center justify-center">
                                <svg class="w-3 h-3 text-mt-accent" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-[15px]">{{ $inc }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-3xl border border-mt-border bg-white p-8 md:p-10 shadow-mt-medium" data-animate>
                <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Proyecto a la medida</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-mt-text-2 text-lg">desde</span>
                    <span class="text-5xl md:text-6xl font-display font-semibold text-mt-text tracking-tight">USD&nbsp;1.200</span>
                </div>
                <div class="mt-1 text-mt-text-3 font-mono text-sm">≈ $4.800.000 COP · por fases</div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-8 w-full justify-center mt-btn-primary">
                    Cotizar mi automatización
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-3 w-full justify-center mt-btn-ghost">
                    Prefiero un formulario
                </a>
                <p class="mt-5 text-center text-mt-text-3 text-[12.5px] leading-relaxed">
                    El valor final depende de los procesos e integraciones que necesites.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- FAQ                                                           --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-32" data-animate>
                    <span class="mt-eyebrow-gray">Preguntas frecuentes</span>
                    <h2 class="mt-4 text-section font-display text-mt-text">Lo que más nos preguntan.</h2>
                    <p class="mt-6 text-mt-text-2 text-base leading-relaxed">
                        ¿Tienes otra duda? Escríbenos por WhatsApp y te respondemos &mdash; sí, con una persona.
                    </p>
                </div>
            </div>
            <div class="lg:col-span-8 flex flex-col divide-y divide-mt-border border-t border-b border-mt-border" data-animate>
                @foreach ($faqs as $faq)
                    <details class="group py-5">
                        <summary class="flex items-start justify-between gap-4 cursor-pointer list-none">
                            <span class="text-lg font-display font-semibold text-mt-text leading-snug">{{ $faq['q'] }}</span>
                            <span class="flex-shrink-0 mt-1 w-6 h-6 rounded-full border border-mt-border flex items-center justify-center text-mt-accent transition-transform duration-300 group-open:rotate-45" aria-hidden="true">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                            </span>
                        </summary>
                        <p class="mt-3 pr-10 text-mt-text-2 text-[15px] leading-relaxed">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CTA FINAL                                                     --}}
{{-- ============================================================= --}}
<section class="relative py-24 md:py-32 bg-mt-bg-dark overflow-hidden">
    <div class="mt-ai-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Que la IA haga lo repetitivo &mdash; tu equipo, lo que de verdad importa.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntanos qué proceso te está costando más horas y te decimos si se puede automatizar. Sin compromiso.
        </p>
        <div class="mt-10 flex flex-wrap gap-4 justify-center" data-animate>
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-btn-primary">
                Hablar por WhatsApp
                <span aria-hidden="true">&rarr;</span>
            </a>
            <a href="{{ route('contacto.index') }}" class="mt-btn-ghost mt-btn-ghost-on-dark">
                Agendar una llamada
            </a>
        </div>
    </div>
</section>

@endsection

{{-- ============================================================= --}}
{{-- SCHEMA: FAQPage (via head_extras para no duplicar el @graph)  --}}
{{-- ============================================================= --}}
@push('head_extras')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($f) => [
        '@type' => 'Question',
        'name' => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ])->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

{{-- ============================================================= --}}
{{-- ESTILOS ESPECÍFICOS DE LA LANDING                             --}}
{{-- ============================================================= --}}
@push('styles')
<style>
    /* Glows decorativos */
    .mt-ai-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 78% 8%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 8% 20%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-ai-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Mockup del flujo de automatización */
    .mt-ai-flow {
        width: 100%; max-width: 400px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14), 0 4px 14px rgba(37,99,235,0.06);
        overflow: hidden;
    }
    .mt-ai-flow-header {
        display: flex; align-items: center; gap: 0.4rem;
        padding: 0.75rem 1rem;
        background: #F9FAFB;
        border-bottom: 1px solid #E5E7EB;
    }
    .mt-ai-dot { width: 9px; height: 9px; border-radius: 50%; background: #D1D5DB; }
    .mt-ai-flow-title {
        margin-left: 0.6rem;
        font-size: 12px; color: #6B7280;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        letter-spacing: 0.04em;
    }
    .mt-ai-flow-body { padding: 1.1rem; display: flex; flex-direction: column; }

    .mt-ai-card {
        border: 1px solid #E5E7EB; border-radius: 14px;
        padding: 0.85rem 0.95rem; background: #fff;
    }
    .mt-ai-card-in  { background: #F9FAFB; }
    .mt-ai-card-out { border-color: rgba(37,99,235,0.28); background: rgba(37,99,235,0.035); }
    .mt-ai-tag {
        display: inline-block; margin-bottom: 0.45rem;
        font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        color: #6B7280; background: #fff; border: 1px solid #E5E7EB;
        border-radius: 999px; padding: 0.15rem 0.5rem;
    }
    .mt-ai-tag-ok { color: #2563EB; border-color: rgba(37,99,235,0.3); background: rgba(37,99,235,0.06); }
    .mt-ai-card-t { font-size: 14px; font-weight: 600; color: #111827; line-height: 1.35; }
    .mt-ai-card-d { margin-top: 0.3rem; font-size: 12.5px; color: #6B7280; line-height: 1.5; }

    .mt-ai-list { margin-top: 0.55rem; display: flex; flex-direction: column; gap: 0.35rem; }
    .mt-ai-list li { display: flex; gap: 0.45rem; font-size: 12.5px; color: #374151; line-height: 1.45; }
    .mt-ai-check { color: #2563EB; font-weight: 700; flex-shrink: 0; }

    .mt-ai-arrow { display: flex; align-items: center; gap: 0.5rem; padding: 0.55rem 0.2rem; }
    .mt-ai-arrow-line { flex: 1; height: 1px; background: linear-gradient(90deg, transparent, #D1D5DB, transparent); }
    .mt-ai-arrow-badge {
        font-size: 10.5px; font-weight: 700; letter-spacing: 0.12em;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        color: #fff; background: #2563EB;
        border-radius: 999px; padding: 0.2rem 0.6rem;
        box-shadow: 0 0 0 4px rgba(37,99,235,0.10);
        animation: mtAiPulse 2.4s infinite ease-in-out;
    }
    @keyframes mtAiPulse {
        0%, 100% { box-shadow: 0 0 0 4px rgba(37,99,235,0.10); }
        50%      { box-shadow: 0 0 0 9px rgba(37,99,235,0.04); }
    }

    .mt-ai-approve {
        margin-top: 0.85rem; display: flex; align-items: center; justify-content: space-between;
        gap: 0.75rem; padding: 0.6rem 0.75rem;
        border: 1px dashed #D1D5DB; border-radius: 12px; background: #FCFCFD;
    }
    .mt-ai-approve-label { font-size: 11.5px; color: #6B7280; }
    .mt-ai-approve-btn {
        font-size: 11.5px; font-weight: 600; color: #fff; background: #111827;
        border-radius: 8px; padding: 0.35rem 0.7rem; white-space: nowrap;
    }

    @media (prefers-reduced-motion: reduce) {
        .mt-ai-arrow-badge { animation: none; }
    }
</style>
@endpush

@push('scripts')
<script>
    /* Red de seguridad: si el observer de reveals de la home no corre en esta
       ruta, revelamos el contenido igual para no dejar nada invisible. */
    window.addEventListener('load', function () {
        setTimeout(function () {
            if (!document.querySelector('[data-animate].is-visible')) {
                document.querySelectorAll('[data-animate]').forEach(function (el) {
                    el.classList.add('is-visible');
                });
            }
        }, 700);
    });
</script>
@endpush
