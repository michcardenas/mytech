@php
    $gallery = is_array($proyecto->galeria) ? $proyecto->galeria : [];
@endphp

@if(count($gallery) > 0)
<section class="mt-pd-gallery py-24 md:py-32 bg-white border-t border-mt-border" data-pd-gallery>
    <div class="mt-container">

        <div class="max-w-2xl mb-16" data-animate>
            <span class="mt-eyebrow-gray">Visual</span>
            <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                El proyecto, por dentro.
            </h2>
            <p class="mt-4 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Capturas del producto en producción — interfaces, paneles y experiencia real del usuario.
            </p>
        </div>

        {{-- Masonry-like grid asimétrico --}}
        <div class="mt-pd-gallery-grid">
            @foreach($gallery as $i => $img)
                @php
                    $size = match($i % 5) {
                        0       => 'wide',     // grande horizontal
                        1, 4    => 'tall',     // alto
                        default => 'normal',
                    };
                @endphp
                <button type="button"
                        class="mt-pd-gallery-item mt-pd-gallery-{{ $size }}"
                        data-pd-gallery-item
                        data-index="{{ $i }}"
                        data-src="{{ asset('storage/'.$img) }}"
                        aria-label="Ampliar imagen {{ $i + 1 }}">
                    <img src="{{ asset('storage/'.$img) }}"
                         alt="Captura {{ $i + 1 }} de {{ $proyecto->nombre }}"
                         loading="lazy"
                         decoding="async">
                    <span class="mt-pd-gallery-zoom" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="11" cy="11" r="7"/>
                            <line x1="16.5" y1="16.5" x2="21" y2="21" stroke-linecap="round"/>
                            <line x1="11" y1="8" x2="11" y2="14" stroke-linecap="round"/>
                            <line x1="8" y1="11" x2="14" y2="11" stroke-linecap="round"/>
                        </svg>
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Lightbox modal --}}
    <div class="mt-pd-lightbox" data-pd-lightbox aria-hidden="true" role="dialog">
        <button type="button" class="mt-pd-lightbox-close" data-pd-lightbox-close aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
        </button>
        <button type="button" class="mt-pd-lightbox-prev" data-pd-lightbox-prev aria-label="Anterior">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <button type="button" class="mt-pd-lightbox-next" data-pd-lightbox-next aria-label="Siguiente">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="mt-pd-lightbox-content">
            <img src="" alt="" data-pd-lightbox-img>
        </div>
        <div class="mt-pd-lightbox-counter" data-pd-lightbox-counter>1 / {{ count($gallery) }}</div>
    </div>
</section>
@endif
