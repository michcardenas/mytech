{{-- ════════════════ CAPÍTULO 05 — CRÉDITOS DE PELÍCULA ════════════════ --}}
{{-- Fondo negro absoluto. Scroll vertical donde los créditos suben
     constantes (parallax inverso scrub). Termina en "FIN." y CTA. --}}

@php
    $creditos = [
        [
            'rol'  => $data['cred_1_rol']  ?? 'DIRECCIÓN GENERAL',
            'lista' => $data['cred_1_lista'] ?? 'Michael Cárdenas',
        ],
        [
            'rol'  => $data['cred_2_rol']  ?? 'PRODUCTO &amp; ESTRATEGIA',
            'lista' => $data['cred_2_lista'] ?? 'El cliente · La realidad · Nosotros',
        ],
        [
            'rol'  => $data['cred_3_rol']  ?? 'INGENIERÍA',
            'lista' => $data['cred_3_lista'] ?? "Laravel · Vue · Inertia · Tailwind\nGSAP · Lenis · Vite · MySQL · Redis",
        ],
        [
            'rol'  => $data['cred_4_rol']  ?? 'DISEÑO',
            'lista' => $data['cred_4_lista'] ?? "Figma · Sistema mt-* propio\nTipografía: Inter Tight · JetBrains Mono",
        ],
        [
            'rol'  => $data['cred_5_rol']  ?? 'INFRAESTRUCTURA',
            'lista' => $data['cred_5_lista'] ?? "Hostinger · Cloudflare\nGitHub Actions · Sentry",
        ],
        [
            'rol'  => $data['cred_6_rol']  ?? 'BANDA SONORA',
            'lista' => $data['cred_6_lista'] ?? "Lo-fi a las 11 PM\nSilencio productivo a las 3 AM",
        ],
        [
            'rol'  => $data['cred_7_rol']  ?? 'CAFEÍNA',
            'lista' => $data['cred_7_lista'] ?? "Café colombiano · Mate argentino\nMucho té verde",
        ],
        [
            'rol'  => $data['cred_8_rol']  ?? 'AGRADECIMIENTOS ESPECIALES',
            'lista' => $data['cred_8_lista'] ?? "A cada cliente que apostó temprano.\nA cada noche en que algo terminó funcionando.\nA la comunidad open-source que hace esto posible.",
        ],
    ];
@endphp

<section class="mt-sn-creditos" data-sn-creditos>
    <div class="mt-sn-creditos-grid" aria-hidden="true"></div>

    <header class="mt-sn-creditos-head">
        <div class="mt-container">
            <div class="mt-sn-cap-head mt-sn-cap-head-light">
                <span class="mt-sn-cap-mono">CAP. 05</span>
                <span class="mt-sn-cap-sep" aria-hidden="true">·</span>
                <span class="mt-sn-cap-name">{{ $data['cap5_label'] ?? 'Créditos' }}</span>
            </div>
            <h2 class="mt-sn-creditos-head-title">
                {{ $data['creditos_head'] ?? 'Nada se hace solo.' }}
            </h2>
        </div>
    </header>

    <div class="mt-sn-creditos-stage" data-sn-creditos-stage>
        <div class="mt-sn-creditos-roll" data-sn-creditos-roll>
            @foreach($creditos as $c)
                <div class="mt-sn-credito-bloque">
                    <span class="mt-sn-credito-rol">{!! $c['rol'] !!}</span>
                    <div class="mt-sn-credito-lista">
                        @foreach(explode("\n", $c['lista']) as $line)
                            <span>{!! $line !!}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mt-sn-credito-bloque mt-sn-credito-final" data-sn-fin>
                <span class="mt-sn-fin-text">FIN.</span>
                <span class="mt-sn-fin-sub">— Pero el código sigue corriendo.</span>
            </div>

            <div class="mt-sn-cta-bloque">
                <p class="mt-sn-cta-pre">{{ $data['cta_pre'] ?? '¿Te suena el manifiesto?' }}</p>
                <h3 class="mt-sn-cta-title text-balance">
                    {{ $data['cta_title'] ?? 'Vamos a construir algo' }}
                    <span class="italic">{{ $data['cta_title_accent'] ?? 'que dure.' }}</span>
                </h3>
                <div class="mt-sn-cta-row">
                    <a href="{{ route('contacto.index') }}" class="mt-btn-primary">
                        {{ $data['cta_button_text'] ?? 'Cuéntanos tu proyecto' }}
                        <span aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('proyectos.index') }}" class="mt-btn-ghost mt-btn-ghost-on-dark">
                        {{ $data['cta_secondary_text'] ?? 'Ver lo que hicimos' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
