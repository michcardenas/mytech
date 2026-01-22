<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Pages - Índice</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-center mb-12">Landing Pages Disponibles</h1>

        <div class="max-w-4xl mx-auto grid gap-6">
            @forelse($landings as $landing)
                <a href="/{{ $landing->slug }}"
                   class="block bg-white rounded-lg shadow-md hover:shadow-xl transition-all p-6 border-l-4 border-blue-600">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $landing->title }}
                    </h2>
                    @if($landing->seo)
                        <p class="text-gray-600">
                            {{ $landing->seo->meta_description }}
                        </p>
                    @endif
                    <div class="mt-4 text-blue-600 font-semibold">
                        Ver landing →
                    </div>
                </a>
            @empty
                <div class="text-center text-gray-500 py-12">
                    <p>No hay landing pages activas</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
