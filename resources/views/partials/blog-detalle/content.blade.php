@php
    // Detectar URL para share buttons
    $postUrl = route('blog.show', $post->slug);
    $tags = $post->getTagsArray();
@endphp

<section class="mt-bd-content py-16 md:py-24 bg-white">
    <div class="mt-container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">

            {{-- Sidebar (TOC + share + meta) — solo desktop --}}
            <aside class="hidden lg:block lg:col-span-3">
                <div class="mt-bd-sidebar" data-bd-sidebar>

                    {{-- TOC scroll-spy --}}
                    <div class="mt-bd-toc" data-bd-toc>
                        <h3 class="mt-bd-toc-title">
                            <span class="mt-bd-toc-line"></span>
                            En este artículo
                        </h3>
                        <ul class="mt-bd-toc-list" data-bd-toc-list>
                            {{-- Generado por JS al detectar h2/h3 del contenido --}}
                        </ul>
                    </div>

                    {{-- Share editorial vertical --}}
                    <div class="mt-bd-share-vertical">
                        <h3 class="mt-bd-toc-title">
                            <span class="mt-bd-toc-line"></span>
                            Compartir
                        </h3>
                        <div class="mt-bd-share-vertical-list">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($postUrl) }}&text={{ urlencode($post->title) }}"
                               target="_blank" rel="noopener"
                               aria-label="Compartir en Twitter / X"
                               class="mt-bd-share-btn">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($postUrl) }}"
                               target="_blank" rel="noopener"
                               aria-label="Compartir en LinkedIn"
                               class="mt-bd-share-btn">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.063 2.063 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title.' '.$postUrl) }}"
                               target="_blank" rel="noopener"
                               aria-label="Compartir por WhatsApp"
                               class="mt-bd-share-btn">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.966-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </a>
                            <button type="button"
                                    class="mt-bd-share-btn"
                                    data-bd-copy-link
                                    data-url="{{ $postUrl }}"
                                    aria-label="Copiar enlace">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 13a5 5 0 007 0l4-4a5 5 0 00-7-7l-1 1" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 11a5 5 0 00-7 0l-4 4a5 5 0 007 7l1-1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Contenido principal --}}
            <article class="lg:col-span-9 lg:pl-6">
                <div class="mt-bd-prose" data-bd-content>
                    {!! $post->content !!}
                </div>

                {{-- Tags inline al final del contenido --}}
                @if(count($tags) > 0)
                    <div class="mt-bd-tags-inline">
                        <span class="mt-bd-tags-label">Etiquetas</span>
                        <ul>
                            @foreach($tags as $tag)
                                <li>
                                    <a href="{{ route('blog.tag', \Illuminate\Support\Str::slug($tag)) }}">{{ $tag }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Share buttons inline (mobile + bottom siempre) --}}
                <div class="mt-bd-share-bottom">
                    <span class="mt-bd-share-bottom-label">¿Te gustó este artículo?</span>
                    <div class="mt-bd-share-bottom-buttons">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($postUrl) }}&text={{ urlencode($post->title) }}"
                           target="_blank" rel="noopener" class="mt-bd-share-btn mt-bd-share-btn-lg">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            X
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($postUrl) }}"
                           target="_blank" rel="noopener" class="mt-bd-share-btn mt-bd-share-btn-lg">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.063 2.063 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title.' '.$postUrl) }}"
                           target="_blank" rel="noopener" class="mt-bd-share-btn mt-bd-share-btn-lg">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.966-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                        <button type="button" class="mt-bd-share-btn mt-bd-share-btn-lg" data-bd-copy-link data-url="{{ $postUrl }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 13a5 5 0 007 0l4-4a5 5 0 00-7-7l-1 1" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 11a5 5 0 00-7 0l-4 4a5 5 0 007 7l1-1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Copiar
                        </button>
                    </div>
                </div>

                {{-- Author box --}}
                @if($post->author)
                    <div class="mt-bd-author-box">
                        <div class="mt-bd-author-avatar">{{ strtoupper(substr($post->author, 0, 1)) }}</div>
                        <div class="mt-bd-author-info">
                            <div class="mt-bd-author-label">Escrito por</div>
                            <div class="mt-bd-author-name">{{ $post->author }}</div>
                            <p class="mt-bd-author-bio">
                                Equipo de MY Tech Solutions. Construimos software a medida para empresas en LATAM desde 2018.
                            </p>
                        </div>
                    </div>
                @endif
            </article>

        </div>
    </div>
</section>
