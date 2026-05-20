@php
    // Contenido editable desde /pages/1/edit (page slug='inicio')
    $homeContent = [];
    if (isset($page) && $page && $page->content) {
        $homeContent = json_decode($page->content, true) ?? [];
    }

    // Defaults — si el admin borra todo, no se rompe el render
    $defaultPasos = [
        ['num' => '01', 'title' => 'Descubrimos',  'lead' => 'Conversación profunda para entender tu negocio.',     'desc' => 'Identificamos objetivos, audiencia, presupuesto y plazos. Salimos con un plan técnico claro y una propuesta sin ambigüedades.',           'tags' => 'Análisis, Estrategia, Roadmap'],
        ['num' => '02', 'title' => 'Diseñamos',    'lead' => 'Arquitectura técnica y experiencia de usuario.',      'desc' => 'Definimos stack, integraciones y flujos UX/UI antes de escribir una línea de código. Validamos contigo cada decisión.',                    'tags' => 'UX/UI, Stack, Integraciones'],
        ['num' => '03', 'title' => 'Construimos',  'lead' => 'Desarrollo iterativo con previews semanales.',        'desc' => 'Recibes avances cada semana y puedes pedir ajustes en cada sprint. Código limpio, documentado y probado. Sin sorpresas.',                  'tags' => 'Laravel, Vue · React, Testing'],
        ['num' => '04', 'title' => 'Lanzamos',     'lead' => 'Deploy, capacitación y soporte continuo.',            'desc' => 'Subimos a producción, capacitamos a tu equipo y quedamos disponibles para mejoras, ajustes y nuevas funcionalidades.',                    'tags' => 'Deploy, Capacitación, Soporte'],
    ];

    // Cada paso lee primero de BD, fallback al default
    $pasos = [];
    foreach ($defaultPasos as $i => $d) {
        $n = $i + 1;
        $tagsRaw = $homeContent['proceso_paso_'.$n.'_tags'] ?? $d['tags'];
        $pasos[] = [
            'num'   => $homeContent['proceso_paso_'.$n.'_num']   ?? $d['num'],
            'title' => $homeContent['proceso_paso_'.$n.'_title'] ?? $d['title'],
            'lead'  => $homeContent['proceso_paso_'.$n.'_lead']  ?? $d['lead'],
            'desc'  => $homeContent['proceso_paso_'.$n.'_desc']  ?? $d['desc'],
            'tags'  => array_values(array_filter(array_map('trim', explode(',', $tagsRaw)))),
        ];
    }
@endphp

<section class="mt-process-section relative bg-mt-bg overflow-hidden" data-process-section>

    {{-- Pista (track) que se mueve horizontal con scroll vertical --}}
    <div class="mt-process-pin" data-process-pin>

        {{-- Header sticky a la izquierda --}}
        <div class="mt-process-header">
            <div class="mt-container w-full">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center min-h-[60vh]">
                    <div class="lg:col-span-5">
                        <span class="mt-eyebrow-gray">{{ $homeContent['proceso_eyebrow'] ?? 'Cómo trabajamos' }}</span>
                        <h2 class="mt-4 text-section font-display text-mt-text">
                            {{ $homeContent['proceso_title_main'] ?? 'De una idea a una plataforma en' }}
                            <span class="text-mt-accent">{{ $homeContent['proceso_title_accent'] ?? 'producción' }}</span>.
                        </h2>
                        <p class="mt-5 text-mt-text-2 text-base md:text-lg leading-relaxed max-w-md">
                            {{ $homeContent['proceso_subtitle'] ?? 'Cuatro fases, comunicación constante y entregables claros en cada paso. Así llevamos cada proyecto desde la primera conversación hasta el lanzamiento.' }}
                        </p>
                        {{-- Hint swipe (solo mobile; desktop ya tiene la barra de progreso inferior) --}}
                        <span class="mt-process-swipe-hint lg:hidden">
                            <svg class="w-4 h-4" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M13 5l7 7-7 7"/>
                            </svg>
                            {{ $homeContent['proceso_swipe_hint'] ?? 'Desliza' }}
                        </span>
                    </div>

                    {{-- Track horizontal con las cards --}}
                    <div class="lg:col-span-7 relative mt-process-track-wrap">
                        <ul class="mt-process-track" data-process-track>
                            @foreach($pasos as $i => $paso)
                                <li class="mt-process-step {{ $i === 0 ? 'is-snap-active' : '' }}" data-process-step data-step-index="{{ $i }}">
                                    <div class="mt-process-step-inner">
                                        <div class="mt-process-step-num">
                                            {{ $paso['num'] }}
                                        </div>
                                        <h3 class="mt-process-step-title">{{ $paso['title'] }}</h3>
                                        <p class="mt-process-step-lead">{{ $paso['lead'] }}</p>
                                        <p class="mt-process-step-desc">{{ $paso['desc'] }}</p>
                                        <ul class="mt-process-step-tags">
                                            @foreach($paso['tags'] as $tag)
                                                <li>{{ $tag }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Dots de paginación (solo mobile) --}}
                        <div class="mt-process-dots" data-process-dots>
                            @foreach($pasos as $i => $paso)
                                <button type="button"
                                        class="{{ $i === 0 ? 'is-active' : '' }}"
                                        data-dot-index="{{ $i }}"
                                        aria-label="Ir al paso {{ $paso['num'] }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Progreso (barra de avance) --}}
                <div class="mt-process-progress">
                    <span class="mt-process-progress-track">
                        <span class="mt-process-progress-fill" data-process-progress></span>
                    </span>
                    <span class="mt-process-progress-label" data-process-progress-label>01 / {{ count($pasos) }}</span>
                </div>
            </div>
        </div>

    </div>
</section>
