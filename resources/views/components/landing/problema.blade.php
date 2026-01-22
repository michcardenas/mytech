{{-- Sección Problema --}}
@props(['section'])

@php
    $data = $section->custom_data ?? [];
    $title = $section->title ?? '';
    $content = $section->content ?? '';
    $icon = $data['icon'] ?? 'fa-exclamation-triangle';
    $problemas = $data['problemas'] ?? [];
@endphp

<section class="problema-section py-16 bg-red-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            {{-- Icon & Title --}}
            <div class="text-center mb-12">
                <div class="inline-block p-4 bg-red-100 rounded-full mb-4">
                    <i class="fas {{ $icon }} text-4xl text-red-600"></i>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ $title }}
                </h2>
                @if($content)
                    <div class="text-lg text-gray-700 prose prose-lg max-w-none">
                        {!! $content !!}
                    </div>
                @endif
            </div>

            {{-- Lista de Problemas --}}
            @if(!empty($problemas))
                <div class="grid md:grid-cols-2 gap-4 mt-8">
                    @foreach($problemas as $problema)
                        <div class="flex items-start gap-3 bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                            <i class="fas fa-times-circle text-red-500 mt-1"></i>
                            <span class="text-gray-800">{{ $problema }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
