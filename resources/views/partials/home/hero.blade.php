@php
    $homeContent = [];
    if (isset($page) && $page && $page->content) {
        $homeContent = json_decode($page->content, true) ?? [];
    }

    // Video del hero — prioridad:
    //  1. BD: $homeContent['hero_media']
    //  2. Cualquier mp4/webm/mov en /public/videos/
    //  3. Fallback decorativo
    $heroMedia = $homeContent['hero_media'] ?? ($homeContent['hero_image'] ?? null);
    $heroMediaExt = $heroMedia ? strtolower(pathinfo($heroMedia, PATHINFO_EXTENSION)) : null;
    $heroMediaIsVideo = in_array($heroMediaExt, ['mp4', 'webm', 'mov']);
    $heroMediaUrl = $heroMedia ? asset('storage/' . $heroMedia) : null;

    // Fallback: detección automática de videos en /public/videos/ (cacheada — usa
    // `php artisan cache:clear` si subes un nuevo video manualmente al folder).
    if (!$heroMediaUrl) {
        $fallback = \Illuminate\Support\Facades\Cache::rememberForever('hero_video_fallback', function () {
            $candidates = [
                'videos/hero.mp4', 'videos/hero.mp4.mp4', 'videos/hero-video.mp4',
                'videos/hero.webm', 'videos/hero.mov',
            ];
            foreach ($candidates as $rel) {
                if (file_exists(public_path($rel))) return ['url' => asset($rel), 'video' => true];
            }
            $files = glob(public_path('videos/*.{mp4,webm,mov}'), GLOB_BRACE);
            if ($files && count($files) > 0) {
                return ['url' => asset('videos/' . basename($files[0])), 'video' => true];
            }
            return null;
        });
        if ($fallback) {
            $heroMediaUrl     = $fallback['url'];
            $heroMediaIsVideo = $fallback['video'];
        }
    }
@endphp

<section class="relative min-h-screen flex items-center pt-36 pb-28 md:pb-36 overflow-hidden bg-white">

    {{-- Fondo --}}
    @if($heroMediaUrl)
        <div class="hero-video-bg">
            @if($heroMediaIsVideo)
                <video src="{{ $heroMediaUrl }}" autoplay muted loop playsinline preload="auto"></video>
            @else
                <img src="{{ $heroMediaUrl }}" alt="">
            @endif
        </div>
    @endif

    <div class="mt-container relative z-10">
        <div class="max-w-4xl">

            <div data-animate>
                <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                    <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                    Software a medida
                </span>
            </div>

            <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                {!! $homeContent['hero_title'] ?? 'Desarrollo web y software a medida para escalar tu negocio.' !!}
            </h1>

            <p class="mt-7 max-w-2xl text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                {!! $homeContent['hero_description'] ?? 'Diseñamos y desarrollamos soluciones web y plataformas a medida que ayudan a empresas y emprendedores a vender más, automatizar procesos y escalar digitalmente. Sin plantillas, sin limitaciones técnicas y totalmente adaptadas a tu negocio.' !!}
            </p>

            @php
                $heroBullets = array_values(array_filter([
                    $homeContent['benefit_1'] ?? null,
                    $homeContent['benefit_2'] ?? null,
                    $homeContent['benefit_3'] ?? null,
                ]));
            @endphp
            @if(count($heroBullets) > 0)
                <ul class="mt-9 space-y-3.5" data-animate-children>
                    @foreach($heroBullets as $bullet)
                        <li class="flex items-start gap-3 text-mt-text">
                            <span class="flex-shrink-0 w-6 h-6 mt-0.5 rounded-full bg-mt-accent-soft border border-mt-accent-line flex items-center justify-center">
                                <svg class="w-3 h-3 text-mt-accent" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-base md:text-[17px]">{{ $bullet }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-11 flex flex-wrap gap-3.5" data-animate>
                <a href="{{ route('contacto.index') }}" class="mt-btn-primary">
                    Cotizar mi proyecto
                    <span aria-hidden="true">→</span>
                </a>
                <a href="https://wa.me/573337246403?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20sus%20servicios"
                   target="_blank" rel="noopener"
                   class="mt-btn-ghost">
                    <svg class="w-[18px] h-[18px]" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.966-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hablemos por WhatsApp
                </a>
            </div>

            {{-- ===== Social proof: stats row ===== --}}
            @php
                $stats = array_values(array_filter([
                    [
                        'value' => $homeContent['hero_stat_1_value'] ?? null,
                        'label' => $homeContent['hero_stat_1_label'] ?? null,
                    ],
                    [
                        'value' => $homeContent['hero_stat_2_value'] ?? null,
                        'label' => $homeContent['hero_stat_2_label'] ?? null,
                    ],
                    [
                        'value' => $homeContent['hero_stat_3_value'] ?? null,
                        'label' => $homeContent['hero_stat_3_label'] ?? null,
                    ],
                ], fn($s) => !empty($s['value']) && !empty($s['label'])));
            @endphp
            @if(count($stats) > 0)
                <div class="mt-12 pt-8 border-t border-mt-border max-w-2xl
                            grid grid-cols-3 gap-x-6 sm:gap-x-10 gap-y-6" data-animate>
                    @foreach($stats as $stat)
                        <div>
                            <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight">
                                {{ $stat['value'] }}
                            </div>
                            <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">
                                {{ $stat['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</section>
