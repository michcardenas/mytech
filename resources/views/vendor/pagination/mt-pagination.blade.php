@if ($paginator->hasPages())
    <nav class="mt-pagi" aria-label="Paginación">

        <ul class="mt-pagi-list" role="navigation">

            {{-- ── Anterior ── --}}
            @if ($paginator->onFirstPage())
                <li class="mt-pagi-arrow is-disabled" aria-disabled="true">
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <span class="mt-pagi-arrow-text">Anterior</span>
                </li>
            @else
                <li>
                    <a class="mt-pagi-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior">
                        <span aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="mt-pagi-arrow-text">Anterior</span>
                    </a>
                </li>
            @endif

            {{-- ── Números de página ── --}}
            @foreach ($elements as $element)
                {{-- Separador "..." --}}
                @if (is_string($element))
                    <li class="mt-pagi-sep" aria-hidden="true">{{ $element }}</li>
                @endif

                {{-- Array de páginas --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="mt-pagi-num is-active" aria-current="page">
                                    {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a class="mt-pagi-num" href="{{ $url }}" aria-label="Ir a la página {{ $page }}">
                                    {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- ── Siguiente ── --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a class="mt-pagi-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Página siguiente">
                        <span class="mt-pagi-arrow-text">Siguiente</span>
                        <span aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                </li>
            @else
                <li class="mt-pagi-arrow is-disabled" aria-disabled="true">
                    <span class="mt-pagi-arrow-text">Siguiente</span>
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                </li>
            @endif
        </ul>

        {{-- Meta: "Mostrando X–Y de N" --}}
        <div class="mt-pagi-meta">
            Mostrando
            <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
            de <strong>{{ $paginator->total() }}</strong>
        </div>
    </nav>
@endif
