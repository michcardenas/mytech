@extends('layouts.app_admin')

@section('content')
<style>
    /* Fondo principal */
    body, .container, .container-fluid {
        background: #101820 !important;
        color: #FCFAF1;
    }

    /* Contenedor principal */
    .main-content {
        background: #1a252f;
        padding: 30px;
        border-radius: 8px;
        border: 1px solid #00A9E0;
        min-height: 80vh;
    }

    /* Título */
    h2 {
        color: #00A9E0 !important;
        margin-bottom: 30px;
    }

    /* Grid de opciones */
    .admin-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    /* Tarjetas de opciones */
    .admin-card {
        background: rgba(16, 24, 32, 0.8);
        border: 2px solid rgba(0, 169, 224, 0.3);
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #FCFAF1;
    }

    .admin-card:hover {
        border-color: #00A9E0;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 169, 224, 0.3);
        color: #FCFAF1;
        text-decoration: none;
    }

    .admin-card .icon {
        font-size: 3rem;
        color: #00A9E0;
        margin-bottom: 15px;
        display: block;
    }

    .admin-card h4 {
        color: #00CFB4;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .admin-card p {
        color: rgba(252, 250, 241, 0.8);
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .main-content {
            padding: 20px;
        }
        
        .admin-options {
            grid-template-columns: 1fr;
        }
        
        .admin-card {
            padding: 20px;
        }
        
        .admin-card .icon {
            font-size: 2.5rem;
        }
    }
</style>

<div class="main-content">
    <div class="container py-4">
        <h2>👑 Panel de Administración</h2>
        
        <p style="color: rgba(252, 250, 241, 0.8); margin-bottom: 30px;">
            Bienvenido al panel de administración. Desde aquí puedes gestionar todo el contenido de tu sitio web.
        </p>

        <div class="admin-options">
            
            <!-- Gestionar Páginas -->
            <a href="{{ route('admin.pages.index') }}" class="admin-card">
                <span class="icon">📄</span>
                <h4>Gestionar Páginas</h4>
                <p>Edita el contenido de Inicio, Quiénes Somos, Servicios y Contacto. Agrega texto, imágenes y videos.</p>
            </a>

            <!-- Productos -->
            <a href="{{ route('admin.products.index') }}" class="admin-card">
                <span class="icon">📦</span>
                <h4>Productos</h4>
                <p>Administra tu catálogo de productos, precios, stock e imágenes.</p>
            </a>

            <!-- Países -->
            <a href="{{ route('admin.countries.index') }}" class="admin-card">
                <span class="icon">🌍</span>
                <h4>Países</h4>
                <p>Gestiona los países disponibles para envíos y configuraciones.</p>
            </a>

            <!-- Ciudades -->
            <a href="{{ route('admin.cities.index') }}" class="admin-card">
                <span class="icon">🏙️</span>
                <h4>Ciudades</h4>
                <p>Administra las ciudades disponibles para cada país.</p>
            </a>

            <!-- Categorías (si tienes) -->
       

            <!-- Configuración General -->
            <div class="admin-card" style="opacity: 0.6; cursor: not-allowed;">
                <span class="icon">⚙️</span>
                <h4>Configuración</h4>
                <p>Próximamente: Configuración general del sitio web.</p>
            </div>

        </div>

    

    </div>
</div>
@endsection