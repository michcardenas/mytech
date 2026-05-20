@if($proyecto->testimonio)
<section class="mt-pd-testimonio py-24 md:py-36 bg-white border-t border-mt-border relative overflow-hidden">

    {{-- Quote mark gigante decorativo --}}
    <div class="mt-pd-testimonio-bg-quote" aria-hidden="true">
        <span>"</span>
    </div>

    <div class="mt-container relative z-10">
        <div class="max-w-4xl mx-auto text-center" data-animate>
            <span class="mt-eyebrow-gray">Lo que dice el cliente</span>

            <blockquote class="mt-8">
                <p class="mt-pd-testimonio-quote">
                    {{ $proyecto->testimonio }}
                </p>
                @if($proyecto->testimonio_autor)
                    <footer class="mt-pd-testimonio-author">
                        <div class="mt-pd-testimonio-author-name">{{ $proyecto->testimonio_autor }}</div>
                        @if($proyecto->testimonio_cargo)
                            <div class="mt-pd-testimonio-author-role">{{ $proyecto->testimonio_cargo }}</div>
                        @endif
                    </footer>
                @endif
            </blockquote>
        </div>
    </div>
</section>
@endif
