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
        --gradient-teal: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        --gradient-purple: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        --gradient-orange: linear-gradient(135deg, #e17055 0%, #fdcb6e 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .admin-dashboard {
        background: var(--light-gray);
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: 80vh;
    }

    /* Header */
    .dashboard-header {
        margin-bottom: 2rem;
        padding: 2rem 2.5rem;
        background: var(--gradient-blue);
        border-radius: 16px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .dashboard-header-left h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 0.3rem 0;
        color: white;
    }

    .dashboard-header-left p {
        margin: 0;
        opacity: 0.85;
        font-size: 0.95rem;
    }

    .dashboard-date {
        background: rgba(255,255,255,0.15);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.9rem;
        backdrop-filter: blur(4px);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-soft);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: var(--transition);
        border: 1px solid rgba(0,0,0,0.04);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.blue { background: var(--gradient-blue); }
    .stat-icon.teal { background: var(--gradient-teal); }
    .stat-icon.purple { background: var(--gradient-purple); }
    .stat-icon.orange { background: var(--gradient-orange); }

    .stat-info { flex: 1; }

    .stat-number {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--dark-text);
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .stat-label {
        font-size: 0.78rem;
        color: #888;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }

    /* Section Title */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--primary-blue);
        font-size: 0.95rem;
    }

    /* Admin Cards Grid */
    .admin-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .admin-card {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 16px;
        padding: 1.75rem;
        text-decoration: none;
        color: var(--dark-text);
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .admin-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-blue);
        transform: scaleX(0);
        transition: transform 0.3s ease;
        transform-origin: left;
    }

    .admin-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
        color: var(--dark-text);
        text-decoration: none;
        border-color: rgba(0, 123, 255, 0.15);
    }

    .admin-card:hover::after {
        transform: scaleX(1);
    }

    .admin-card-header {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin-bottom: 0.75rem;
    }

    .admin-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        color: white;
        flex-shrink: 0;
    }

    .admin-card-icon.blue { background: var(--gradient-blue); }
    .admin-card-icon.teal { background: var(--gradient-teal); }
    .admin-card-icon.purple { background: var(--gradient-purple); }
    .admin-card-icon.orange { background: var(--gradient-orange); }

    .admin-card h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--dark-text);
    }

    .admin-card p {
        color: #777;
        margin: 0;
        font-size: 0.88rem;
        line-height: 1.5;
        flex: 1;
    }

    .admin-card-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        margin-top: 0.85rem;
        padding: 0.3rem 0.75rem;
        background: rgba(0, 123, 255, 0.08);
        color: var(--primary-blue);
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        align-self: flex-start;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .quick-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.15rem;
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 10px;
        color: var(--dark-text);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
    }

    .quick-action-btn:hover {
        background: var(--primary-blue);
        color: white;
        text-decoration: none;
        border-color: var(--primary-blue);
        transform: translateY(-1px);
    }

    .quick-action-btn i {
        font-size: 0.85rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-dashboard {
            padding: 1rem;
        }

        .dashboard-header {
            padding: 1.5rem;
            flex-direction: column;
            text-align: center;
        }

        .dashboard-header-left h1 {
            font-size: 1.4rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .admin-options {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            flex-direction: column;
        }

        .quick-action-btn {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-header-left h1 {
            font-size: 1.2rem;
        }
    }
</style>

<div class="admin-dashboard">
    {{-- Header --}}
    <div class="dashboard-header">
        <div class="dashboard-header-left">
            <h1><i class="fas fa-crown"></i> Panel de Administracion</h1>
            <p>Gestiona tu sitio web de MY Tech Solutions</p>
        </div>
        <div class="dashboard-date">
            <i class="far fa-calendar-alt"></i> {{ now()->translatedFormat('d M, Y') }}
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $totalPages }}</div>
                <p class="stat-label">Paginas</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $activePages }}</div>
                <p class="stat-label">Activas</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-project-diagram"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $totalProyectos }}</div>
                <p class="stat-label">Proyectos</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-blog"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $publishedBlog }}</div>
                <p class="stat-label">Blog Posts</p>
            </div>
        </div>
    </div>

    {{-- Acciones rapidas --}}
    <h3 class="section-title"><i class="fas fa-bolt"></i> Acciones rapidas</h3>
    <div class="quick-actions">
        <a href="{{ route('admin.pages.create') }}" class="quick-action-btn">
            <i class="fas fa-plus"></i> Nueva Pagina
        </a>
        <a href="{{ route('admin.proyectos.create') }}" class="quick-action-btn">
            <i class="fas fa-plus"></i> Nuevo Proyecto
        </a>
        <a href="{{ route('admin.pages.create') }}" class="quick-action-btn">
            <i class="fas fa-pen"></i> Nuevo Blog Post
        </a>
    </div>

    {{-- Admin Cards --}}
    <h3 class="section-title"><i class="fas fa-th-large"></i> Administracion</h3>
    <div class="admin-options">
        <a href="{{ route('admin.pages.index') }}" class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-icon blue"><i class="fas fa-file-alt"></i></div>
                <h4>Gestion de Paginas</h4>
            </div>
            <p>Crea, edita y administra todas las paginas de tu sitio web. Controla el contenido de Inicio, Servicios y mas.</p>
            <div class="admin-card-badge">
                <i class="fas fa-circle" style="font-size:0.45rem"></i>
                {{ $activePages }} activas / {{ $draftPages }} borradores
            </div>
        </a>

        <a href="{{ route('admin.proyectos.index') }}" class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-icon teal"><i class="fas fa-project-diagram"></i></div>
                <h4>Proyectos</h4>
            </div>
            <p>Gestiona tu portafolio de proyectos. Agrega nuevos casos de exito, tecnologias y testimonios.</p>
            <div class="admin-card-badge">
                <i class="fas fa-circle" style="font-size:0.45rem"></i>
                {{ $activeProyectos }} activos / {{ $featuredProyectos }} destacados
            </div>
        </a>

        <a href="{{ route('admin.pages.index') }}" class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-icon purple"><i class="fas fa-blog"></i></div>
                <h4>Blog</h4>
            </div>
            <p>Publica articulos, tutoriales y noticias. Gestiona categorias, etiquetas y contenido SEO.</p>
            <div class="admin-card-badge">
                <i class="fas fa-circle" style="font-size:0.45rem"></i>
                {{ $publishedBlog }} publicados / {{ $totalBlog }} total
            </div>
        </a>

        <a href="{{ route('admin.pages.index') }}" class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-icon orange"><i class="fas fa-search"></i></div>
                <h4>SEO</h4>
            </div>
            <p>Optimiza el posicionamiento de tu sitio. Configura meta tags, Open Graph y datos estructurados.</p>
            <div class="admin-card-badge">
                <i class="fas fa-circle" style="font-size:0.45rem"></i>
                Configuracion por pagina
            </div>
        </a>
    </div>
</div>
@endsection
