@php
    use App\Models\Page;

    $cats     = $categories ?? [];
    $allTags  = $allTags ?? [];

    // Si estamos en la página 1, skip el primer post (ya está en featured)
    $items = $posts->currentPage() === 1
        ? $posts->slice(1)->values()
        : $posts->getCollection();

    // Tints por categoría — design system
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

    $currentCat = request('category', '');
@endphp

<section class="mt-blog-grid py-12 md:py-20 bg-mt-bg-2 border-t border-mt-border" id="posts" data-blog-grid-section>
    <div class="mt-container">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10" data-animate>
            <div class="max-w-2xl">
                <span class="mt-eyebrow-gray">Todos los artículos</span>
                <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                    Explora cada
                    <span class="text-mt-accent italic">guía</span>.
                </h2>
            </div>
            <div class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3">
                {{ $posts->total() }} {{ $posts->total() === 1 ? 'artículo' : 'artículos' }} · página {{ $posts->currentPage() }} / {{ $posts->lastPage() }}
            </div>
        </div>

        {{-- Filtros sticky por categoría --}}
        <div class="mt-blog-filters" data-blog-filters>
            <div class="mt-blog-filters-track">
                <a href="{{ route('blog.index') }}"
                   class="mt-blog-filter {{ ! $currentCat ? 'is-active' : '' }}">
                    Todos
                    <span class="mt-blog-filter-count">{{ $posts->total() }}</span>
                </a>
                @foreach($cats as $catKey => $catLabel)
                    <a href="{{ route('blog.category', $catKey) }}"
                       class="mt-blog-filter {{ $currentCat === $catKey ? 'is-active' : '' }}"
                       style="--filter-tint: {{ $catTints[$catKey] ?? '#2563EB' }};">
                        {{ $catLabel }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Grid de posts --}}
        @if($items->count() > 0)
            <div class="mt-blog-grid-list" data-animate-children>
                @foreach($items as $post)
                    @php
                        $postTint = $catTints[$post->category] ?? '#2563EB';
                        $tags = $post->getTagsArray();
                    @endphp
                    <article class="mt-blog-card" style="--post-tint: {{ $postTint }};">
                        <a href="{{ route('blog.show', $post->slug) }}" class="mt-blog-card-link">

                            {{-- Media --}}
                            <div class="mt-blog-card-media">
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/'.$post->featured_image) }}"
                                         alt="{{ $post->title }}"
                                         loading="lazy" decoding="async">
                                @else
                                    <div class="mt-blog-card-media-empty">
                                        <span aria-hidden="true">{{ strtoupper(substr($post->title, 0, 2)) }}</span>
                                    </div>
                                @endif

                                @if($post->category)
                                    <span class="mt-blog-card-cat">
                                        {{ ucfirst(str_replace('-',' ', $post->category)) }}
                                    </span>
                                @endif

                                @if($post->reading_time)
                                    <span class="mt-blog-card-time">⏱ {{ $post->reading_time }} min</span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="mt-blog-card-content">
                                <h3 class="mt-blog-card-title">{{ $post->title }}</h3>

                                @if($post->excerpt)
                                    <p class="mt-blog-card-excerpt">{{ \Illuminate\Support\Str::limit($post->excerpt, 130) }}</p>
                                @endif

                                <div class="mt-blog-card-meta">
                                    @if($post->author)
                                        <span class="mt-blog-card-author">
                                            <span class="mt-blog-card-author-avatar">{{ strtoupper(substr($post->author, 0, 1)) }}</span>
                                            {{ $post->author }}
                                        </span>
                                    @endif
                                    <span class="flex-1"></span>
                                    <time datetime="{{ $post->published_at?->toIso8601String() }}">
                                        {{ $post->published_at?->format('d M Y') }}
                                    </time>
                                </div>
                            </div>

                            <span class="mt-blog-card-strip" aria-hidden="true"></span>
                        </a>
                    </article>
                @endforeach
            </div>

            {{-- Paginación premium custom --}}
            @if($posts->hasPages())
                <div class="mt-blog-pagination-wrap" data-animate>
                    {{ $posts->withQueryString()->links('vendor.pagination.mt-pagination') }}
                </div>
            @endif
        @else
            <div class="mt-blog-empty">
                <p class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3 mb-3">Sin artículos</p>
                <p class="text-mt-text-2 text-lg">No hay artículos publicados aún.</p>
                <a href="{{ route('blog.index') }}" class="mt-6 mt-btn-ghost inline-flex">
                    Ver todos los artículos
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        @endif

        {{-- Tags populares --}}
        @if(count($allTags) > 0)
            <div class="mt-blog-tagcloud" data-animate>
                <h3 class="mt-blog-tagcloud-title">
                    <span class="mt-blog-tagcloud-line"></span>
                    Temas populares
                </h3>
                <ul>
                    @foreach($allTags as $tagName => $tagCount)
                        <li>
                            <a href="{{ route('blog.tag', $tagName) }}">
                                {{ $tagName }}
                                <span class="mt-blog-tag-count">{{ $tagCount }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
</section>
