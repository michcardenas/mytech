<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Panel') · MY Tech Solutions</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS y JS (las páginas admin dependen de Bootstrap + FontAwesome) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --mt-accent: #2563EB;
            --mt-accent-hover: #1D4ED8;
            --mt-sidebar-bg: #0F172A;
            --mt-sidebar-bg-2: #0B1220;
            --mt-sidebar-w: 266px;
            --mt-topbar-h: 64px;
            --mt-transition: all .25s cubic-bezier(.4,0,.2,1);
        }

        * { box-sizing: border-box; }

        body.mtadmin-body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #F3F4F6;
            color: #1F2937;
            min-height: 100vh;
        }

        /* ===================== SIDEBAR ===================== */
        .mtadmin-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--mt-sidebar-w);
            background: var(--mt-sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: var(--mt-transition);
            overflow: hidden;
        }

        .mtadmin-brand {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: 1.35rem 1.5rem 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .mtadmin-brand img {
            height: 34px; width: auto;
            filter: brightness(0) invert(1);
        }

        /* display/background/padding/border explícitos para anular el
           "nav { background:#013105; display:flex; ... }" global de style.css */
        .mtadmin-sidebar .mtadmin-nav {
            flex: 1 1 auto;
            display: block;
            background: transparent;
            border-bottom: none;
            overflow-y: auto;
            padding: 1rem .85rem 1.5rem;
        }
        .mtadmin-nav::-webkit-scrollbar { width: 6px; }
        .mtadmin-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 3px; }

        .mtadmin-group-label {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #64748B;
            padding: 0 .85rem;
            margin: 1.25rem 0 .5rem;
        }
        .mtadmin-group-label:first-child { margin-top: 0; }

        .mtadmin-link {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: .7rem .85rem;
            margin-bottom: .15rem;
            border-radius: 10px;
            color: #CBD5E1;
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            position: relative;
            transition: var(--mt-transition);
        }
        .mtadmin-link i {
            width: 20px;
            text-align: center;
            font-size: .95rem;
            flex-shrink: 0;
            transition: var(--mt-transition);
        }
        .mtadmin-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }
        .mtadmin-link.is-active {
            background: rgba(37,99,235,.16);
            color: #fff;
            font-weight: 600;
        }
        .mtadmin-link.is-active i { color: #60A5FA; }
        .mtadmin-link.is-active::before {
            content: '';
            position: absolute;
            left: -.85rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px; height: 22px;
            background: var(--mt-accent);
            border-radius: 0 4px 4px 0;
        }
        .mtadmin-link .mtadmin-badge {
            margin-left: auto;
            background: var(--mt-accent);
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
            padding: .1rem .45rem;
            border-radius: 999px;
            line-height: 1.4;
        }

        /* Footer del sidebar */
        .mtadmin-sidebar-foot {
            border-top: 1px solid rgba(255,255,255,.07);
            padding: .9rem .85rem 1.1rem;
        }
        .mtadmin-user {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .5rem .6rem;
            border-radius: 10px;
            margin-bottom: .4rem;
        }
        .mtadmin-user-avatar {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .9rem;
            flex-shrink: 0;
        }
        .mtadmin-user-info { min-width: 0; }
        .mtadmin-user-name {
            color: #fff; font-size: .85rem; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .mtadmin-user-role { color: #64748B; font-size: .73rem; }

        .mtadmin-foot-actions { display: flex; gap: .5rem; }
        .mtadmin-foot-btn {
            flex: 1;
            display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
            padding: .55rem .5rem;
            border-radius: 9px;
            font-size: .78rem; font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,.1);
            color: #CBD5E1;
            background: transparent;
            cursor: pointer;
            transition: var(--mt-transition);
        }
        .mtadmin-foot-btn:hover { background: rgba(255,255,255,.07); color: #fff; }
        .mtadmin-foot-btn.danger:hover { background: rgba(239,68,68,.15); border-color: rgba(239,68,68,.4); color: #FCA5A5; }

        /* ===================== SHELL / TOPBAR ===================== */
        .mtadmin-shell {
            margin-left: var(--mt-sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--mt-transition);
        }

        .mtadmin-topbar {
            position: sticky; top: 0;
            height: var(--mt-topbar-h);
            background: rgba(255,255,255,.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0 1.25rem;
            z-index: 1040;
        }
        .mtadmin-burger {
            display: none;
            background: none; border: none;
            font-size: 1.25rem; color: #1F2937; cursor: pointer;
            padding: .35rem .5rem; border-radius: 8px;
        }
        .mtadmin-burger:hover { background: #F3F4F6; }
        .mtadmin-topbar-title {
            font-size: 1.02rem; font-weight: 700; color: #1F2937; margin: 0;
        }
        .mtadmin-topbar-right { margin-left: auto; display: flex; align-items: center; gap: .6rem; }
        .mtadmin-ghost-link {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .5rem .9rem;
            border: 1px solid #E5E7EB; border-radius: 9px;
            color: #4B5563; font-size: .83rem; font-weight: 600;
            text-decoration: none; transition: var(--mt-transition);
        }
        .mtadmin-ghost-link:hover { border-color: var(--mt-accent); color: var(--mt-accent); }

        .mtadmin-main { flex: 1; }

        /* Overlay para móvil */
        .mtadmin-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,.5);
            opacity: 0; visibility: hidden;
            transition: var(--mt-transition);
            z-index: 1045;
        }
        .mtadmin-overlay.is-open { opacity: 1; visibility: visible; }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 991.98px) {
            .mtadmin-sidebar { transform: translateX(-100%); box-shadow: 0 0 50px rgba(0,0,0,.3); }
            .mtadmin-sidebar.is-open { transform: translateX(0); }
            .mtadmin-shell { margin-left: 0; }
            .mtadmin-burger { display: inline-flex; }
        }
    </style>

    @vite(['resources/js/app.js'])
</head>
<body class="mtadmin-body">

    @php
        $mtUser     = auth()->user();
        $mtUserName = $mtUser->name ?? 'Usuario';
        $mtInitials = collect(explode(' ', trim($mtUserName)))->take(2)->map(fn($p) => mb_substr($p, 0, 1))->implode('');

        // Título del topbar derivado de la ruta activa (sin tocar cada vista)
        $mtPageTitle = match (true) {
            request()->routeIs('dashboard')                  => 'Dashboard',
            request()->routeIs('admin.pages.*')              => 'Páginas',
            request()->routeIs('admin.proyectos.*')          => 'Portafolio',
            request()->routeIs('admin.internal-projects.stats') => 'Estadísticas',
            request()->routeIs('admin.internal-projects.*')  => 'Mis Proyectos',
            request()->routeIs('admin.seo.*')                => 'SEO',
            request()->routeIs('admin.users.*')              => 'Usuarios',
            request()->routeIs('pipeline.dashboard')         => 'Dashboard comercial',
            request()->routeIs('pipeline.commissions')       => 'Comisiones',
            request()->routeIs('pipeline.pendientes')        => 'Pendientes',
            request()->routeIs('pipeline.perdidos')          => 'Perdidos',
            request()->routeIs('pipeline.meetings.*')        => 'Reuniones',
            request()->routeIs('pipeline.my-results')        => 'Mis resultados',
            request()->routeIs('pipeline.calendar*')         => 'Mi calendario',
            request()->routeIs('pipeline.correos.*')         => 'Correos',
            request()->routeIs('pipeline.*')                 => 'Pipeline',
            default                                          => 'Panel',
        };
    @endphp

    {{-- ===== Overlay móvil ===== --}}
    <div class="mtadmin-overlay" id="mtadminOverlay" onclick="mtadminCloseSidebar()"></div>

    {{-- ===== Sidebar ===== --}}
    <aside class="mtadmin-sidebar" id="mtadminSidebar">
        <a href="{{ route('dashboard') }}" class="mtadmin-brand">
            <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions">
        </a>

        <nav class="mtadmin-nav">
            @role('admin')
                <div class="mtadmin-group-label">Principal</div>
                <a href="{{ route('dashboard') }}" class="mtadmin-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <i class="fas fa-gauge-high"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="mtadmin-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
                    <i class="fas fa-users-gear"></i> Usuarios
                </a>

                <div class="mtadmin-group-label">Contenido del sitio</div>
                <a href="{{ route('admin.pages.index') }}" class="mtadmin-link {{ request()->routeIs('admin.pages.*') ? 'is-active' : '' }}">
                    <i class="fas fa-file-lines"></i> Páginas
                </a>
                <a href="{{ route('admin.proyectos.index') }}" class="mtadmin-link {{ request()->routeIs('admin.proyectos.*') ? 'is-active' : '' }}">
                    <i class="fas fa-diagram-project"></i> Portafolio
                </a>

                <div class="mtadmin-group-label">Mi negocio</div>
                <a href="{{ route('admin.internal-projects.index') }}" class="mtadmin-link {{ request()->routeIs('admin.internal-projects.index') || request()->routeIs('admin.internal-projects.show') || request()->routeIs('admin.internal-projects.create') || request()->routeIs('admin.internal-projects.edit') ? 'is-active' : '' }}">
                    <i class="fas fa-briefcase"></i> Mis Proyectos
                </a>
                <a href="{{ route('admin.internal-projects.stats') }}" class="mtadmin-link {{ request()->routeIs('admin.internal-projects.stats') ? 'is-active' : '' }}">
                    <i class="fas fa-chart-line"></i> Estadísticas
                </a>

                <div class="mtadmin-group-label">Comercial</div>
                <a href="{{ route('pipeline.index') }}" class="mtadmin-link {{ request()->routeIs('pipeline.index') || request()->routeIs('pipeline.leads.*') ? 'is-active' : '' }}">
                    <i class="fas fa-table-columns"></i> Pipeline
                </a>
                <a href="{{ route('pipeline.dashboard') }}" class="mtadmin-link {{ request()->routeIs('pipeline.dashboard') ? 'is-active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard comercial
                </a>
                <a href="{{ route('pipeline.perdidos') }}" class="mtadmin-link {{ request()->routeIs('pipeline.perdidos') ? 'is-active' : '' }}">
                    <i class="fas fa-ban"></i> Perdidos
                </a>
                <a href="{{ route('pipeline.meetings.index') }}" class="mtadmin-link {{ request()->routeIs('pipeline.meetings.*') ? 'is-active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Reuniones
                </a>
                <a href="{{ route('pipeline.commissions') }}" class="mtadmin-link {{ request()->routeIs('pipeline.commissions') ? 'is-active' : '' }}">
                    <i class="fas fa-hand-holding-dollar"></i> Comisiones
                </a>
                <a href="{{ route('pipeline.calendar') }}" class="mtadmin-link {{ request()->routeIs('pipeline.calendar') ? 'is-active' : '' }}">
                    <i class="fas fa-calendar-days"></i> Mi calendario
                </a>
                <a href="{{ route('pipeline.correos.index') }}" class="mtadmin-link {{ request()->routeIs('pipeline.correos.*') ? 'is-active' : '' }}">
                    <i class="fas fa-envelope"></i> Correos
                </a>
            @endrole

            @role('comercial')
                <div class="mtadmin-group-label">Mi pipeline</div>
                <a href="{{ route('pipeline.index') }}" class="mtadmin-link {{ request()->routeIs('pipeline.index') || request()->routeIs('pipeline.leads.*') ? 'is-active' : '' }}">
                    <i class="fas fa-table-columns"></i> Pipeline
                </a>
                <a href="{{ route('pipeline.pendientes') }}" class="mtadmin-link {{ request()->routeIs('pipeline.pendientes') ? 'is-active' : '' }}">
                    <i class="fas fa-list-check"></i> Pendientes
                </a>
                <a href="{{ route('pipeline.perdidos') }}" class="mtadmin-link {{ request()->routeIs('pipeline.perdidos') ? 'is-active' : '' }}">
                    <i class="fas fa-ban"></i> Perdidos
                </a>
                <a href="{{ route('pipeline.meetings.index') }}" class="mtadmin-link {{ request()->routeIs('pipeline.meetings.*') ? 'is-active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Reuniones
                </a>
                <a href="{{ route('pipeline.correos.index') }}" class="mtadmin-link {{ request()->routeIs('pipeline.correos.*') ? 'is-active' : '' }}">
                    <i class="fas fa-envelope"></i> Correos
                </a>

                <div class="mtadmin-group-label">Resultados</div>
                <a href="{{ route('pipeline.my-results') }}" class="mtadmin-link {{ request()->routeIs('pipeline.my-results') ? 'is-active' : '' }}">
                    <i class="fas fa-award"></i> Mis resultados
                </a>
            @endrole
        </nav>

        <div class="mtadmin-sidebar-foot">
            <div class="mtadmin-user">
                <div class="mtadmin-user-avatar">{{ strtoupper($mtInitials ?: 'U') }}</div>
                <div class="mtadmin-user-info">
                    <div class="mtadmin-user-name">{{ $mtUserName }}</div>
                    <div class="mtadmin-user-role">{{ $mtUser?->hasRole('admin') ? 'Administrador' : ($mtUser?->hasRole('comercial') ? 'Comercial' : 'Usuario') }}</div>
                </div>
            </div>
            <div class="mtadmin-foot-actions">
                <a href="{{ route('home') }}" target="_blank" class="mtadmin-foot-btn">
                    <i class="fas fa-arrow-up-right-from-square"></i> Ver sitio
                </a>
                <button type="button" class="mtadmin-foot-btn danger"
                        onclick="document.getElementById('mtadmin-logout-form').submit();">
                    <i class="fas fa-right-from-bracket"></i> Salir
                </button>
            </div>
        </div>
    </aside>

    {{-- ===== Shell ===== --}}
    <div class="mtadmin-shell">
        <header class="mtadmin-topbar">
            <button type="button" class="mtadmin-burger" onclick="mtadminToggleSidebar()" aria-label="Abrir menú">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="mtadmin-topbar-title">@yield('page_title', $mtPageTitle)</h1>
            <div class="mtadmin-topbar-right">
                <a href="{{ route('home') }}" target="_blank" class="mtadmin-ghost-link">
                    <i class="fas fa-globe"></i>
                    <span class="d-none d-sm-inline">Ver sitio</span>
                </a>
            </div>
        </header>

        <main class="mtadmin-main">
            @yield('content')
        </main>
    </div>

    {{-- Formulario oculto para logout --}}
    <form id="mtadmin-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <script>
        function mtadminToggleSidebar() {
            document.getElementById('mtadminSidebar').classList.toggle('is-open');
            document.getElementById('mtadminOverlay').classList.toggle('is-open');
        }
        function mtadminCloseSidebar() {
            document.getElementById('mtadminSidebar').classList.remove('is-open');
            document.getElementById('mtadminOverlay').classList.remove('is-open');
        }
        // Cerrar al navegar (mobile) o al pasar a desktop
        document.addEventListener('keydown', e => { if (e.key === 'Escape') mtadminCloseSidebar(); });
        window.addEventListener('resize', () => { if (window.innerWidth > 991) mtadminCloseSidebar(); });

        // Toast helper (compatibilidad con páginas existentes)
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
            toast.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</body>
</html>
