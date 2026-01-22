{{-- Sección Proyectos Destacados --}}
@props(['section', 'proyectos'])

@php
    $title = $section->title ?? 'Nuestros Proyectos';
    $content = $section->content ?? '';
    $data = $section->custom_data ?? [];
    $mostrarTestimonios = $data['mostrar_testimonios'] ?? false;
@endphp

<section class="proyectos-section py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            {{-- Title --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ $title }}
                </h2>
                @if($content)
                    <div class="text-lg text-gray-600 prose prose-lg max-w-none">
                        {!! $content !!}
                    </div>
                @endif
            </div>

            {{-- Grid de Proyectos --}}
            @if($proyectos && $proyectos->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($proyectos as $proyecto)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2">
                            {{-- Logo/Badge --}}
                            <div class="p-6 bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-2xl">{{ $proyecto->bandera_emoji }}</span>
                                    <span class="bg-white/20 px-3 py-1 rounded-full text-xs">
                                        {{ $proyecto->badge_text }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold">{{ $proyecto->nombre }}</h3>
                            </div>

                            {{-- Contenido --}}
                            <div class="p-6">
                                <p class="text-gray-600 mb-4">
                                    {{ Str::limit($proyecto->descripcion, 120) }}
                                </p>

                                {{-- Tecnologías --}}
                                @if($proyecto->tecnologias)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach(array_slice($proyecto->tecnologias, 0, 3) as $tech)
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                                {{ $tech }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Testimonio (si está habilitado) --}}
                                @if($mostrarTestimonios && $proyecto->testimonio)
                                    <div class="border-t pt-4 mt-4">
                                        <p class="text-sm italic text-gray-600">
                                            "{{ Str::limit($proyecto->testimonio, 100) }}"
                                        </p>
                                        @if($proyecto->testimonio_autor)
                                            <p class="text-xs text-gray-500 mt-2">
                                                - {{ $proyecto->testimonio_autor }}
                                                @if($proyecto->testimonio_cargo)
                                                    , {{ $proyecto->testimonio_cargo }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Link --}}
                                @if($proyecto->url)
                                    <a href="{{ $proyecto->url }}"
                                       target="_blank"
                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 mt-4 font-semibold">
                                        Ver proyecto
                                        <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-12">
                    <i class="fas fa-folder-open text-5xl mb-4 opacity-50"></i>
                    <p>No hay proyectos destacados configurados aún</p>
                </div>
            @endif
        </div>
    </div>
</section>
