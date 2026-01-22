{{-- Sección Solución --}}
@props(['section'])

@php
    $data = $section->custom_data ?? [];
    $title = $section->title ?? '';
    $content = $section->content ?? '';
    $icon = $data['icon'] ?? 'fa-check-circle';
    $beneficios = $data['beneficios'] ?? [];
    $modulos = $data['modulos'] ?? [];
@endphp

<section class="solucion-section py-16 bg-gradient-to-br from-green-50 to-blue-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            {{-- Icon & Title --}}
            <div class="text-center mb-12">
                <div class="inline-block p-4 bg-green-100 rounded-full mb-4">
                    <i class="fas {{ $icon }} text-4xl text-green-600"></i>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ $title }}
                </h2>
                @if($content)
                    <div class="text-lg text-gray-700 prose prose-lg max-w-3xl mx-auto">
                        {!! $content !!}
                    </div>
                @endif
            </div>

            {{-- Beneficios --}}
            @if(!empty($beneficios))
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($beneficios as $beneficio)
                        <div class="flex items-start gap-3 bg-white p-5 rounded-lg shadow-md hover:shadow-xl transition-shadow border-t-4 border-green-500">
                            <i class="fas fa-check-circle text-green-500 mt-1 text-xl"></i>
                            <span class="text-gray-800 font-medium">{{ $beneficio }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Módulos (si existen, como en ERP) --}}
            @if(!empty($modulos))
                <div class="bg-white rounded-xl shadow-lg p-8 mt-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                        Módulos Incluidos
                    </h3>
                    <div class="grid md:grid-cols-3 gap-4">
                        @foreach($modulos as $modulo)
                            <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                                <i class="fas fa-cube text-blue-600"></i>
                                <span class="text-gray-800 font-semibold">{{ $modulo }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
