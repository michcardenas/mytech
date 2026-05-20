@php
    // Tomamos el post más reciente (paginator->items() ya viene ordenado por published_at desc)
    $featured = $posts->first() ?? null;
@endphp

@if($featured && $posts->currentPage() === 1)
<section class="mt-blog-featured py-16 md:py-24 bg-white" data-blog-featured>
    <div class="mt-container">

        <div class="flex items-center gap-3 mb-10" data-animate>
            <span class="mt-eyebrow-gray">Último publicado</span>
            <span class="flex-1 h-px bg-mt-border"></span>
            <span class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3">
                {{ $featured->published_at?->format('d M Y') }}
            </span>
        </div>

        <a href="{{ route('blog.show', $featured->slug) }}"
           class="mt-blog-featured-card"
           data-blog-featured-card>

            {{-- Imagen --}}
            <div class="mt-blog-featured-media">
                @if($featured->featured_image)
                    <img src="{{ asset('storage/'.$featured->featured_image) }}"
                         alt="{{ $featured->title }}"
                         loading="eager" decoding="async">
                @else
                    <div class="mt-blog-featured-media-empty">
                        <span aria-hidden="true">📰</span>
                    </div>
                @endif

                {{-- Categoría pill --}}
                @if($featured->category)
                    <span class="mt-blog-featured-cat">
                        {{ ucfirst(str_replace('-',' ', $featured->category)) }}
                    </span>
                @endif

                {{-- Reading time pill --}}
                @if($featured->reading_time)
                    <span class="mt-blog-featured-time">
                        ⏱ {{ $featured->reading_time }} min
                    </span>
                @endif
            </div>

            {{-- Copy --}}
            <div class="mt-blog-featured-copy">
                <h2 class="mt-blog-featured-title">
                    {{ $featured->title }}
                </h2>

                @if($featured->excerpt)
                    <p class="mt-blog-featured-excerpt">
                        {{ $featured->excerpt }}
                    </p>
                @endif

                <div class="mt-blog-featured-meta">
                    @if($featured->author)
                        <span class="mt-blog-featured-author">
                            <span class="mt-blog-featured-author-avatar">
                                {{ strtoupper(substr($featured->author, 0, 1)) }}
                            </span>
                            {{ $featured->author }}
                        </span>
                    @endif
                    <span class="flex-1"></span>
                    <span class="mt-blog-featured-cta">
                        Leer artículo
                        <span aria-hidden="true">→</span>
                    </span>
                </div>
            </div>
        </a>
    </div>
</section>
@endif
