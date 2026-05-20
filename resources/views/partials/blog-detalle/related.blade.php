@php
    $catTints = [
        'tecnologia'   => '#2563EB',
        'desarrollo'   => '#10B981',
        'diseno'       => '#EC4899',
        'marketing'    => '#F59E0B',
        'negocios'     => '#8B5CF6',
        'tutoriales'   => '#0F766E',
        'noticias'     => '#EF4444',
        'casos-exito'  => '#2563EB',
    ];
@endphp

@if($relatedPosts->count() > 0)
<section class="mt-bd-related py-20 md:py-28 bg-mt-bg-2 border-t border-mt-border">
    <div class="mt-container">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12" data-animate>
            <div class="max-w-2xl">
                <span class="mt-eyebrow-gray">Sigue leyendo</span>
                <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                    Artículos
                    <span class="text-mt-accent italic">relacionados</span>.
                </h2>
            </div>
            <a href="{{ route('blog.index') }}" class="mt-btn-ghost self-start md:self-end">
                Ver todos los artículos
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-animate-children>
            @foreach($relatedPosts as $rp)
                @php
                    $rTint = $catTints[$rp->category] ?? '#2563EB';
                @endphp
                <article class="mt-blog-card" style="--post-tint: {{ $rTint }};">
                    <a href="{{ route('blog.show', $rp->slug) }}" class="mt-blog-card-link">
                        <div class="mt-blog-card-media">
                            @if($rp->featured_image)
                                <img src="{{ asset('storage/'.$rp->featured_image) }}"
                                     alt="{{ $rp->title }}" loading="lazy">
                            @else
                                <div class="mt-blog-card-media-empty">
                                    <span aria-hidden="true">{{ strtoupper(substr($rp->title, 0, 2)) }}</span>
                                </div>
                            @endif
                            @if($rp->category)
                                <span class="mt-blog-card-cat">{{ ucfirst(str_replace('-',' ', $rp->category)) }}</span>
                            @endif
                            @if($rp->reading_time)
                                <span class="mt-blog-card-time">⏱ {{ $rp->reading_time }} min</span>
                            @endif
                        </div>
                        <div class="mt-blog-card-content">
                            <h3 class="mt-blog-card-title">{{ $rp->title }}</h3>
                            @if($rp->excerpt)
                                <p class="mt-blog-card-excerpt">{{ \Illuminate\Support\Str::limit($rp->excerpt, 110) }}</p>
                            @endif
                            <div class="mt-blog-card-meta">
                                @if($rp->author)
                                    <span class="mt-blog-card-author">
                                        <span class="mt-blog-card-author-avatar">{{ strtoupper(substr($rp->author, 0, 1)) }}</span>
                                        {{ $rp->author }}
                                    </span>
                                @endif
                                <span class="flex-1"></span>
                                <time>{{ $rp->published_at?->format('d M Y') }}</time>
                            </div>
                        </div>
                        <span class="mt-blog-card-strip" aria-hidden="true"></span>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
