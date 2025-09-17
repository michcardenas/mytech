<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MY Tech Solutions - Admin</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS y JS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
            --navbar-height: 80px;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--dark-text);
            background: var(--white);
        }

        /* Navigation Responsive Styles - MY TECH SOLUTIONS */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 20px rgba(0, 123, 255, 0.1);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: var(--transition);
            height: var(--navbar-height);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            z-index: 1001;
        }

        .logo-img {
            height: 50px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
        }

        /* Desktop Navigation */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
            margin: 0;
        }

        .nav-menu li {
            position: relative;
        }

        .nav-link {
            color: var(--dark-text);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: var(--transition);
            padding: 0.5rem 0;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--primary-blue);
        }

        .nav-link.active {
            color: var(--primary-blue);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-blue);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .btn-contact {
            background: var(--gradient-blue);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            color: white;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-icons i {
            color: var(--dark-text);
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .nav-icons i:hover {
            color: var(--primary-blue);
            transform: scale(1.1);
        }

        .nav-icons a {
            color: var(--dark-text);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
        }

        .nav-icons a:hover {
            color: var(--primary-blue);
            transform: translateY(-2px);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--dark-text);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
            z-index: 1001;
        }

        .mobile-menu-toggle:hover {
            background: rgba(0, 123, 255, 0.1);
            color: var(--primary-blue);
        }

        /* Dropdown Styles */
        .dropdown-toggle {
            color: inherit;
            text-decoration: none;
            position: relative;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .dropdown-menu {
            background: var(--white);
            border: 2px solid rgba(0, 123, 255, 0.1);
            border-radius: 12px;
            box-shadow: var(--shadow-soft);
            min-width: 200px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            color: var(--dark-text);
            font-weight: 500;
            transition: var(--transition);
            border: none;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background: rgba(0, 123, 255, 0.05);
            color: var(--primary-blue);
            transform: translateX(5px);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(0, 123, 255, 0.1);
        }

        /* Admin Dropdown Específico */
        .nav-menu .dropdown .dropdown-toggle {
            color: var(--white) !important;
            background: var(--gradient-blue);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-menu .dropdown .dropdown-toggle:hover {
            color: var(--white) !important;
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        /* Main Content */
        .main-content {
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
        }
        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --navbar-height: 70px;
            }

            .nav-menu {
                display: none;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .navbar-container {
                padding: 0 1rem;
            }

            .logo-img {
                height: 40px;
            }

            .nav-icons {
                gap: 1rem;
            }

            .main-content {
                margin-top: var(--navbar-height);
            }
        }

        @media (max-width: 480px) {
            :root {
                --navbar-height: 65px;
            }

            .navbar-container {
                padding: 0 0.8rem;
            }

            .logo-img {
                height: 35px;
            }

            .nav-icons {
                gap: 0.8rem;
            }
        }
    </style>

    @vite(['resources/js/app.js'])
</head>
<body>
    <nav class="navbar-custom" id="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions" class="logo-img">
            </a>

            <!-- Desktop Navigation -->
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a></li>
                <li><a href="{{ route('servicios.index') }}" class="nav-link">Servicios</a></li>
                <li><a href="{{ route('proyectos.index') }}" class="nav-link">Proyectos</a></li>
                <li><a href="{{ route('sobre_nosotros.index') }}" class="nav-link">Sobre Nosotros</a></li>
                <li><a href="{{ route('contacto.index') }}" class="nav-link">Contacto</a></li>

                @role('admin')
                <li class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-2"></i>Gestión
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.pages.index') }}">
                            <i class="fas fa-file-alt me-2"></i>Gestión de Páginas
                        </a></li>
                    </ul>
                </li>
                @endrole
            </ul>

            <!-- Desktop Icons -->
            <div class="nav-icons">
      

                @auth
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" title="Mi cuenta">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Formulario oculto para logout -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
                @else
                <a href="{{ route('login') }}" title="Iniciar sesión">
                    <i class="fas fa-user"></i>
                </a>
                @endauth

            </div>

            <!-- Mobile Menu Button -->
            <button class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Abrir menú de navegación">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="main-content">
        @yield('content')
    </main>

    <script>
        function toggleMobileMenu() {
            // Función para menú móvil (puedes implementarla después)
            console.log('Mobile menu toggle');
        }

        // Funciones para gestión de páginas
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.textContent = message;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
</body>
</html>