{{-- Sección FAQs --}}
@props(['section'])

@php
    $title = $section->title ?? 'Preguntas Frecuentes';
    $content = $section->content ?? '';
    $data = $section->custom_data ?? [];
    $items = $data['items'] ?? [];
@endphp

<section class="faqs-section py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            {{-- Title --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ $title }}
                </h2>
                @if($content)
                    <p class="text-lg text-gray-600">
                        {{ $content }}
                    </p>
                @endif
            </div>

            {{-- Accordion de FAQs --}}
            @if(!empty($items))
                <div class="space-y-4">
                    @foreach($items as $index => $item)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <button
                                class="faq-question w-full px-6 py-4 text-left bg-white hover:bg-gray-50 flex justify-between items-center"
                                onclick="toggleFaq({{ $index }})"
                                type="button">
                                <span class="font-semibold text-gray-900 pr-4">
                                    {{ $item['pregunta'] ?? '' }}
                                </span>
                                <i class="fas fa-chevron-down text-blue-600 transition-transform duration-200" id="icon-{{ $index }}"></i>
                            </button>
                            <div class="faq-answer px-6 py-4 bg-gray-50 hidden" id="answer-{{ $index }}">
                                <p class="text-gray-700">
                                    {{ $item['respuesta'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- JavaScript para el accordion --}}
                <script>
                    function toggleFaq(index) {
                        const answer = document.getElementById('answer-' + index);
                        const icon = document.getElementById('icon-' + index);

                        if (answer.classList.contains('hidden')) {
                            answer.classList.remove('hidden');
                            icon.style.transform = 'rotate(180deg)';
                        } else {
                            answer.classList.add('hidden');
                            icon.style.transform = 'rotate(0deg)';
                        }
                    }
                </script>
            @else
                <div class="text-center text-gray-500 py-12">
                    <p>No hay preguntas frecuentes configuradas</p>
                </div>
            @endif
        </div>
    </div>
</section>
