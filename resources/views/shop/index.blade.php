@extends('layouts.app')

@section('content')

<style>
    body {
        background-color: #FCFAF1;
        color: #101820;
        font-family: 'Inter', Arial, sans-serif;
    }

    .catalog-section {
        min-height: 100vh;
        padding: 40px 0;
    }

    .catalog-title {
        color: #101820;
        font-size: 2.2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
    }

    /* Filtro Simple */
    .filter-bar {
        background: linear-gradient(135deg, #FCFAF1 0%, #F8F6ED 100%);
        border: 2px solid #00A9E0;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 40px;
        display: flex;
        gap: 20px;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        box-shadow: 0 5px 20px rgba(0, 169, 224, 0.1);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-label {
        color: #101820;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .filter-select {
        background: #FCFAF1;
        border: 2px solid #00A9E0;
        border-radius: 8px;
        color: #101820;
        padding: 10px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 140px;
    }

    .filter-select:focus {
        outline: none;
        border-color: #00CFB4;
        box-shadow: 0 0 0 3px rgba(0, 207, 180, 0.2);
    }

    /* Grid de Productos */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .product-card {
        display: flex;
        flex-direction: column;
        background: linear-gradient(145deg, #FCFAF1 0%, #F8F6ED 100%);
        border: 2px solid rgba(0, 169, 224, 0.2);
        border-radius: 15px;
        overflow: hidden;
        min-height: 100%;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        border-color: #00CFB4;
        box-shadow: 0 10px 30px rgba(0, 169, 224, 0.2);
    }

    .pc-media {
        position: relative;
        overflow: hidden;
        background: #F8F6ED;
    }

    .pc-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .product-card:hover .pc-img {
        transform: scale(1.05);
    }

    .product-carousel .carousel-item {
        transition: transform .6s ease;
    }

    /* BODY */
    .pc-body {
        padding: 20px;
        color: #101820;
        flex: 1 1 auto;
    }

    .pc-category {
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #00A9E0;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .pc-title {
        margin: 0 0 12px;
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pc-title a {
        color: #101820;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .pc-title a:hover {
        color: #00CFB4;
    }

    .pc-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-top: auto;
    }

    .pc-price {
        color: #00CFB4;
        font-weight: 800;
        font-size: 1.2rem;
    }

    .pc-weight {
        color: #666;
        font-size: 0.85rem;
    }

    /* ACTIONS */
    .pc-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        padding: 15px 20px 20px;
        border-top: 2px solid rgba(0, 169, 224, 0.1);
        flex-wrap: wrap;
    }

    .btn-ghost, .btn-solid {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
        min-width: 120px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 0.9rem;
        border: 2px solid;
    }

    /* Outline */
    .btn-ghost {
        color: #00A9E0;
        background: transparent;
        border-color: #00A9E0;
    }

    .btn-ghost:hover {
        color: #FCFAF1;
        background: #00A9E0;
        border-color: #00A9E0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 169, 224, 0.3);
    }

    /* Solid */
    .btn-solid {
        color: #FCFAF1;
        background: linear-gradient(135deg, #00CFB4, #00A9E0);
        border-color: #00CFB4;
    }

    .btn-solid:hover {
        background: linear-gradient(135deg, #00A9E0, #00CFB4);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 207, 180, 0.4);
    }

    @media (max-width: 576px) {
        .pc-img {
            height: 220px;
        }
        .btn-ghost, .btn-solid {
            min-width: 100%;
        }
    }

    /* No products message */
    .no-products {
        text-align: center;
        color: #666;
        font-size: 1.2rem;
        margin-top: 60px;
        padding: 40px;
        background: #F8F6ED;
        border-radius: 15px;
        border: 2px solid rgba(0, 169, 224, 0.2);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 50px;
        display: flex;
        justify-content: center;
    }

    /* Carrusel Styles */
    .electrahome-carousel-section {
        background: linear-gradient(135deg, #101820 0%, #1a252f 100%);
        margin: 0;
        padding: 0;
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        margin-bottom: 0;
    }

    .category-hero-slide {
        position: relative;
        height: 100%;
        overflow: hidden;
    }

    .slide-background {
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        z-index: 1;
    }

    .slide-bg-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        opacity: 0.3;
    }

    .slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(16, 24, 32, 0.9) 0%, 
            rgba(16, 24, 32, 0.7) 50%, 
            rgba(16, 24, 32, 0.3) 100%);
    }

    .slide-content {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        z-index: 2;
        padding: 50px 0;
    }

    .slide-text {
        color: white;
        padding-right: 40px;
    }

    .category-label {
        background: linear-gradient(135deg, rgba(0, 169, 224, 0.2), rgba(0, 207, 180, 0.2));
        border: 2px solid rgba(0, 207, 180, 0.4);
        border-radius: 25px;
        padding: 10px 22px;
        margin-bottom: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        color: #FCFAF1;
    }

    .slide-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        line-height: 1.1;
        color: #FCFAF1;
    }

    .slide-description {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
        line-height: 1.6;
        max-width: 500px;
        color: #FCFAF1;
    }

    .shop-category-btn {
        display: inline-block;
        background: linear-gradient(135deg, #00A9E0, #00CFB4);
        color: white;
        padding: 16px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(0, 169, 224, 0.4);
    }

    .shop-category-btn:hover {
        background: linear-gradient(135deg, #00CFB4, #00A9E0);
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 207, 180, 0.5);
        color: white;
        text-decoration: none;
    }

    .slide-image {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .category-product-image {
        max-width: 100%;
        max-height: 320px;
        object-fit: contain;
        border-radius: 15px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }

    .category-product-image:hover {
        transform: scale(1.05);
    }

    /* Controles del carrusel */
    .carousel-control-prev,
    .carousel-control-next {
        width: 55px;
        height: 55px;
        background: rgba(0, 169, 224, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(0, 207, 180, 0.4);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .carousel-control-prev {
        left: 30px;
    }

    .carousel-control-next {
        right: 30px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1;
        background: rgba(0, 169, 224, 0.4);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 22px;
        height: 22px;
    }

    /* Indicadores */
    .carousel-indicators {
        bottom: 30px;
        margin-bottom: 0;
    }

    .carousel-indicators button {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: rgba(0, 169, 224, 0.4);
        border: 2px solid rgba(0, 207, 180, 0.6);
        transition: all 0.3s ease;
        margin: 0 6px;
    }

    .carousel-indicators button.active {
        background: #00A9E0;
        border-color: #FCFAF1;
        transform: scale(1.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .catalog-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
        }

        .filter-bar {
            flex-direction: column;
            gap: 15px;
            padding: 20px;
        }

        .filter-group {
            width: 100%;
            justify-content: space-between;
        }

        .filter-select {
            min-width: 160px;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .slide-title {
            font-size: 2.2rem;
        }

        .slide-description {
            font-size: 1rem;
            padding: 0 20px;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 45px;
            height: 45px;
        }
    }

    @media (max-width: 480px) {
        .catalog-section {
            padding: 20px 0;
        }

        .products-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .slide-title {
            font-size: 1.8rem;
        }

        .category-label {
            padding: 8px 16px;
            font-size: 0.75rem;
            margin-bottom: 15px;
        }

        .slide-description {
            font-size: 0.9rem;
            padding: 0 15px;
        }

        .shop-category-btn {
            padding: 14px 28px;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .carousel-indicators {
            bottom: 20px;
        }

        .category-product-image {
            max-height: 220px;
        }
    }
</style>

<div class="electrahome-carousel-section">
    <div id="categoryCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($categories as $index => $category)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="category-hero-slide">
                        <div class="slide-background">
                            <img src="{{ $category->image ? Storage::url($category->image) : asset('images/category-placeholder.jpg') }}"
                                 class="slide-bg-image" alt="{{ $category->name }}">
                            <div class="slide-overlay"></div>
                        </div>
                        <div class="slide-content">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="slide-text">
                                            <div class="category-label">{{ $category->products_count }} Productos Disponibles</div>
                                            <h1 class="slide-title">{{ strtoupper($category->name) }}</h1>
                                            <p class="slide-description">
                                                Descubre la mejor calidad en {{ strtolower($category->name) }} con garantía oficial. 
                                                Equipos duraderos y eficientes diseñados para hacer tu vida más fácil.
                                            </p>
                                            <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="shop-category-btn">
                                                VER {{ strtoupper($category->name) }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-none d-lg-block">
                                        <div class="slide-image">
                                            <img src="{{ $category->image ? Storage::url($category->image) : asset('images/category-placeholder.jpg') }}"
                                                 alt="{{ $category->name }}" class="category-product-image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Indicadores -->
        <div class="carousel-indicators">
            @foreach($categories as $index => $category)
                <button type="button" data-bs-target="#categoryCarousel" data-bs-slide-to="{{ $index }}" 
                        class="{{ $index == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        
        <!-- Controles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</div>

<div class="catalog-section">
    <div class="container">
        <h1 class="catalog-title">Nuestros Productos</h1>

    <!-- Filtros Arreglados -->
<div class="filter-bar">
    <form method="GET" action="{{ route('shop.index') }}" class="d-flex gap-3 align-items-center flex-wrap justify-content-center">
        <div class="filter-group">
            <label class="filter-label">Categoría:</label>
            <select name="category" class="filter-select" onchange="this.form.submit()">
                <option value="">Todas</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </option>
                @endforeach
            </select>
        </div>
        
        @if($countries && $countries->count() > 0)
        <div class="filter-group">
            <label class="filter-label">País:</label>
            <select name="country" class="filter-select" onchange="this.form.submit()">
                <option value="">Todos</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Búsqueda opcional -->
        <div class="filter-group">
            <label class="filter-label">Buscar:</label>
            <input type="text" 
                   name="search" 
                   class="filter-select" 
                   placeholder="Buscar productos..."
                   value="{{ request('search') }}"
                   style="min-width: 200px;">
        </div>

        <div class="filter-group">
            <label class="filter-label">Ordenar por:</label>
            <select name="sort" class="filter-select" onchange="this.form.submit()">
                <option value="">Destacados</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más Nuevos</option>
            </select>
        </div>

        <!-- Botón de búsqueda -->
        <div class="filter-group">
            <button type="submit" class="btn-solid" style="min-width: auto; padding: 10px 20px;">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>

        <!-- Limpiar filtros -->
        @if(request()->hasAny(['category', 'country', 'search', 'sort']))
        <div class="filter-group">
            <a href="{{ route('shop.index') }}" class="btn-ghost" style="min-width: auto; padding: 10px 20px;">
                <i class="fas fa-times"></i> Limpiar
            </a>
        </div>
        @endif

        <div class="filter-group">
            <span class="filter-label">
                <strong>{{ isset($totalProducts) ? $totalProducts : $products->total() }}</strong> productos
                @if(isset($hasFilters) && $hasFilters)
                    <small style="color: #00A9E0;">(filtrados)</small>
                @endif
            </span>
        </div>
    </form>
</div>

        <!-- Grid de Productos -->
        <div class="products-grid">
            @forelse ($products as $product)
                <div class="product-card">
                    {{-- MEDIA --}}
                    <div class="pc-media">
                        @php $imgs = $product->images; @endphp
                        @if($imgs->count() > 1)
                            <div id="productCarousel-{{ $product->id }}" class="carousel slide product-carousel" data-bs-ride="carousel" data-bs-interval="3000">
                                <div class="carousel-inner">
                                    @foreach($imgs as $k => $img)
                                        <div class="carousel-item {{ $k === 0 ? 'active' : '' }}">
                                            <img src="{{ Storage::url($img->image) }}"
                                                 class="pc-img"
                                                 alt="{{ $product->name }}"
                                                 loading="lazy"
                                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route('product.show', $product) }}" class="pc-media-link">
                                <img src="{{ $imgs->first()?->image ? Storage::url($imgs->first()->image) : asset('images/placeholder.jpg') }}"
                                     class="pc-img"
                                     alt="{{ $product->name }}"
                                     loading="lazy">
                            </a>
                        @endif
                    </div>

                    {{-- BODY --}}
                    <div class="pc-body">
                        <div class="pc-category">{{ $product->category->name ?? 'Sin categoría' }}</div>

                        <h3 class="pc-title">
                            <a href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                        </h3>

                        @php
                            $totalPrice = (float)($product->price ?? 0) + (float)($product->interest ?? 0);
                        @endphp
                        <div class="pc-price-row">
                            <div class="pc-price">${{ number_format($totalPrice, 0, ',', '.') }}</div>
                            <div class="pc-weight">c/u</div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="pc-actions">
                        <a href="{{ route('product.show', $product) }}" class="btn-ghost">
                            <i class="fas fa-eye"></i> <span>Ver Detalles</span>
                        </a>
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline-block">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn-solid" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart"></i> 
                                <span>{{ $product->stock <= 0 ? 'Agotado' : 'Agregar' }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-products">
                        <i class="fas fa-search mb-3" style="font-size: 3rem; color: #00A9E0;"></i>
                        <h3>No se encontraron productos</h3>
                        <p>No hay productos disponibles con los filtros seleccionados.</p>
                        <a href="{{ route('shop.index') }}" class="btn-solid mt-3">
                            <i class="fas fa-refresh me-2"></i>Ver Todos los Productos
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if(isset($products) && $products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // Inicializar carrusel cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar carruseles de productos
        document.querySelectorAll('.product-carousel').forEach(el => {
            new bootstrap.Carousel(el, {
                interval: 3000,
                ride: 'carousel',
                pause: 'hover',
                touch: true,
                wrap: true
            });
        });

        // Inicializar carrusel principal
        if (typeof bootstrap !== 'undefined') {
            const carouselElement = document.getElementById('categoryCarousel');
            if (carouselElement) {
                const carousel = new bootstrap.Carousel(carouselElement, {
                    interval: 5000,
                    ride: 'carousel',
                    wrap: true,
                    touch: true
                });
                
                console.log('Carrusel principal inicializado correctamente');
            }
        } else {
            console.warn('Bootstrap no está cargado. Verifica que Bootstrap JS esté incluido en tu layout.');
        }

        // Manejo de errores de imágenes
        const images = document.querySelectorAll('.slide-bg-image, .category-product-image, .pc-img');
        images.forEach(function(img) {
            img.addEventListener('error', function() {
                console.log('Error cargando imagen:', this.src);
                this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMDBBOUUwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxOCIgZmlsbD0iI2ZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiBubyBkaXNwb25pYmxlPC90ZXh0Pjwvc3ZnPg==';
            });
        });
    });

    // Función para reinicializar carrusel si es necesario
    function reinitializeCarousel() {
        const carouselElement = document.getElementById('categoryCarousel');
        if (carouselElement && typeof bootstrap !== 'undefined') {
            const existingCarousel = bootstrap.Carousel.getInstance(carouselElement);
            if (existingCarousel) {
                existingCarousel.dispose();
            }
            
            const newCarousel = new bootstrap.Carousel(carouselElement, {
                interval: 5000,
                ride: 'carousel',
                wrap: true,
                touch: true
            });
            
            console.log('Carrusel reinicializado');
            return newCarousel;
        }
        return null;
    }

    window.reinitializeCarousel = reinitializeCarousel;
</script>

@endsection