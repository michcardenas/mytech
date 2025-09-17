@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-blue: #007BFF;
        --primary-dark: #0056b3;
        --dark-text: #2c3e50;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --gradient-blue: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
        --shadow-soft: 0 10px 30px rgba(0, 123, 255, 0.1);
        --shadow-hover: 0 20px 40px rgba(0, 123, 255, 0.15);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Contenedor principal */
    .admin-dashboard {
        background: var(--white);
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Encabezado */
    .dashboard-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        border-radius: 20px;
        border: 2px solid rgba(0, 123, 255, 0.1);
    }

    .dashboard-title {
        color: var(--dark-text);
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .dashboard-subtitle {
        color: #666;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Grid de opciones */
    .admin-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    /* Tarjetas de opciones */
    .admin-card {
        background: var(--white);
        border: 2px solid rgba(0, 123, 255, 0.1);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        transition: var(--transition);
        text-decoration: none;
        color: var(--dark-text);
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .admin-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-blue);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .admin-card:hover {
        border-color: var(--primary-blue);
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        color: var(--dark-text);
        text-decoration: none;
    }

    .admin-card:hover::before {
        transform: scaleX(1);
    }

    .admin-card .icon {
        font-size: 3.5rem;
        color: var(--primary-blue);
        margin-bottom: 1.5rem;
        display: block;
        transition: var(--transition);
    }

    .admin-card:hover .icon {
        transform: scale(1.1);
        color: var(--primary-dark);
    }

    .admin-card h4 {
        color: var(--dark-text);
        margin-bottom: 1rem;
        font-weight: 700;
        font-size: 1.3rem;
    }

    .admin-card p {
        color: #666;
        margin-bottom: 0;
        font-size: 1rem;
        line-height: 1.6;
    }

    /* Estadísticas rápidas */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .stat-card {
        background: var(--gradient-blue);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: var(--shadow-soft);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-dashboard {
            padding: 1rem;
        }

        .dashboard-header {
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .dashboard-title {
            font-size: 2rem;
            flex-direction: column;
            gap: 0.5rem;
        }

        .admin-options {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .admin-card {
            padding: 1.5rem;
        }

        .admin-card .icon {
            font-size: 3rem;
        }

        .quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .dashboard-title {
            font-size: 1.8rem;
        }

        .quick-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-dashboard">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-crown"></i>
            Panel de Administración
        </h1>
        <p class="dashboard-subtitle">Gestiona tu sitio web de MY Tech Solutions</p>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-number">5</div>
            <p class="stat-label">Páginas Totales</p>
        </div>
        <div class="stat-card">
            <div class="stat-number">3</div>
            <p class="stat-label">Páginas Publicadas</p>
        </div>
        <div class="stat-card">
            <div class="stat-number">2</div>
            <p class="stat-label">Borradores</p>
        </div>
    </div>

    <!-- Opciones de administración -->
    <div class="admin-options">
        <a href="{{ route('admin.pages.index') }}" class="admin-card">
            <i class="fas fa-file-alt icon"></i>
            <h4>Gestión de Páginas</h4>
            <p>Crea, edita y administra todas las páginas de tu sitio web. Controla el contenido de Inicio, Servicios, Proyectos y más.</p>
        </a>

        <a href="#" class="admin-card">
            <i class="fas fa-chart-line icon"></i>
            <h4>Estadísticas</h4>
            <p>Visualiza el rendimiento de tu sitio web, visitas de páginas y métricas importantes para tu negocio.</p>
        </a>

        <a href="#" class="admin-card">
            <i class="fas fa-cogs icon"></i>
            <h4>Configuración</h4>
            <p>Ajusta la configuración general del sitio, información de contacto y preferencias del sistema.</p>
        </a>

        <a href="#" class="admin-card">
            <i class="fas fa-images icon"></i>
            <h4>Galería de Medios</h4>
            <p>Gestiona imágenes, videos y otros archivos multimedia utilizados en tu sitio web.</p>
        </a>
    </div>
</div>
@endsection