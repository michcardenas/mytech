{{-- ════════════════ CAPÍTULO 04 — LA GENTE ════════════════ --}}
{{-- Equipo en formato "ficha editorial": cada miembro tiene un row
     grande con foto a la izquierda y bio + cita a la derecha.
     Hover: la foto hace un parallax-tilt sutil. --}}

@php
    $team = [];
    for ($i = 1; $i <= 4; $i++) {
        if (! empty($data["team_{$i}_name"]) || $i === 1) {
            $team[] = [
                'name'  => $data["team_{$i}_name"]  ?? ($i === 1 ? 'Michael Cárdenas' : ''),
                'role'  => $data["team_{$i}_role"]  ?? ($i === 1 ? 'Founder · Full-stack lead' : ''),
                'bio'   => $data["team_{$i}_bio"]   ?? ($i === 1 ? 'Lleva más de una década escribiendo software que se queda. Cree que un buen producto se nota cuando deja de notarse.' : ''),
                'quote' => $data["team_{$i}_quote"] ?? ($i === 1 ? 'Lo que no se mide, no se mejora. Lo que no se entiende, no se sostiene.' : ''),
                'img'   => $data["team_{$i}_img"]   ?? null,
                'links' => [
                    'linkedin' => $data["team_{$i}_linkedin"] ?? null,
                    'github'   => $data["team_{$i}_github"]   ?? null,
                    'site'     => $data["team_{$i}_site"]     ?? null,
                ],
            ];
        }
    }
    $team = array_filter($team, fn($m) => ! empty($m['name']));
@endphp

<section class="mt-sn-gente" data-sn-gente>
    <div class="mt-container">
        <header class="mt-sn-cap-head">
            <span class="mt-sn-cap-mono">CAP. 04</span>
            <span class="mt-sn-cap-sep" aria-hidden="true">·</span>
            <span class="mt-sn-cap-name">{{ $data['cap4_label'] ?? 'La gente detrás' }}</span>
        </header>

        <h2 class="mt-sn-gente-head">
            {{ $data['gente_head'] ?? 'No vas a hablar con un comercial.' }}
            <span class="text-mt-accent italic">{{ $data['gente_head_accent'] ?? 'Vas a hablar con quien construye.' }}</span>
        </h2>

        <div class="mt-sn-gente-list">
            @foreach($team as $i => $m)
                <article class="mt-sn-miembro" data-sn-miembro style="--idx: {{ $i }};">
                    <div class="mt-sn-miembro-photo" data-sn-tilt>
                        @if($m['img'])
                            <img src="{{ asset('storage/'.$m['img']) }}" alt="{{ $m['name'] }}" loading="lazy">
                        @else
                            <div class="mt-sn-miembro-photo-empty">
                                <span aria-hidden="true">{{ collect(explode(' ', $m['name']))->map(fn($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('') }}</span>
                            </div>
                        @endif
                        <span class="mt-sn-miembro-photo-mark" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="mt-sn-miembro-body">
                        <span class="mt-sn-miembro-role mono">{{ $m['role'] }}</span>
                        <h3 class="mt-sn-miembro-name">{{ $m['name'] }}</h3>
                        @if($m['quote'])
                            <blockquote class="mt-sn-miembro-quote">
                                <span aria-hidden="true">"</span>{{ $m['quote'] }}<span aria-hidden="true">"</span>
                            </blockquote>
                        @endif
                        @if($m['bio'])
                            <p class="mt-sn-miembro-bio">{{ $m['bio'] }}</p>
                        @endif

                        @if(array_filter($m['links']))
                            <div class="mt-sn-miembro-links">
                                @if($m['links']['linkedin'])
                                    <a href="{{ $m['links']['linkedin'] }}" target="_blank" rel="noopener" aria-label="LinkedIn">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    </a>
                                @endif
                                @if($m['links']['github'])
                                    <a href="{{ $m['links']['github'] }}" target="_blank" rel="noopener" aria-label="GitHub">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                                    </a>
                                @endif
                                @if($m['links']['site'])
                                    <a href="{{ $m['links']['site'] }}" target="_blank" rel="noopener" aria-label="Web personal">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM3.6 9h16.8M3.6 15h16.8M12 3a14.5 14.5 0 010 18M12 3a14.5 14.5 0 000 18"/></svg>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
