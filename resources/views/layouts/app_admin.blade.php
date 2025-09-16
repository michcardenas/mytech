<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectraHome</title>

    {{-- Favicon y meta para iconos --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-96x96.png') }}" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('images/site.webmanifest') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS y JS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Navigation Responsive Styles - VERSIÓN PROFESIONAL */
        nav {
            background: linear-gradient(135deg, #101820 0%, #1a252f 100%);
            padding: 12px 20px;
            border-bottom: 3px solid #00A9E0;
            position: relative;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            position: relative;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1000;
        }

        .logo img {
            height: 50px;
            filter: brightness(0) invert(1);
        }

        /* Desktop Navigation */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .nav-links a {
            color: #00A9E0;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #00CFB4;
            transform: translateY(-1px);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #00A9E0;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .nav-icons {
            
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .nav-icons i {
            
            color: #00A9E0;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-icons i:hover {
            color: #00CFB4;
            transform: scale(1.1);
        }

        .nav-icons a {
            color: #00A9E0;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-icons a:hover {
            color: #00CFB4;
            transform: translateY(-1px);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #FCFAF1;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            z-index: 1001;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            color: #00CFB4;
            transform: scale(1.1);
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
            background: #FCFAF1;
            border: 2px solid #00A9E0;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            padding: 15px 0;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 12px 25px;
            font-size: 0.9rem;
            color: #101820;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .dropdown-item:hover {
            background: linear-gradient(90deg, #00A9E0, #00CFB4);
            color: #FCFAF1;
            border-left-color: #101820;
            transform: translateX(5px);
        }

        .dropdown-divider {
            margin: 10px 0;
            border-color: rgba(0, 169, 224, 0.2);
        }

        /* Badge del carrito */
        .badge {
            background: linear-gradient(135deg, #00A9E0, #00CFB4) !important;
            color: #FCFAF1 !important;
            border: 1px solid rgba(252, 250, 241, 0.3);
            font-weight: 600;
            border-radius: 12px;
            padding: 4px 8px;
            font-size: 0.75rem;
            min-width: 20px;
            text-align: center;
        }
        /* Admin Dropdown Fix - Agregar esto a tu CSS */

/* Dropdown del Admin específico */
.nav-links .dropdown .dropdown-toggle {
    color: #FCFAF1 !important;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid rgba(0, 169, 224, 0.3);
    background: rgba(0, 169, 224, 0.1);
}

.nav-links .dropdown .dropdown-toggle:hover {
    color: #101820 !important;
    background: #00A9E0;
    border-color: #00A9E0;
    transform: translateY(-1px);
}

/* Asegurar que los iconos del admin también sean visibles */
.nav-links .dropdown .dropdown-toggle::before {
    margin-right: 5px;
}

/* Efectos adicionales para el dropdown del admin */
.nav-links .dropdown:hover .dropdown-toggle {
    background: rgba(0, 169, 224, 0.2);
    border-color: rgba(0, 169, 224, 0.5);
}

/* Para dispositivos móviles también */
@media (max-width: 768px) {
    .nav-links .dropdown .dropdown-toggle {
        display: inline-block;
        margin: 5px 0;
        padding: 10px 15px;
        font-size: 0.9rem;
    }
}
        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .nav-icons {
                gap: 12px;
            }
            
            .nav-icons a {
                font-size: 1rem;
            }
            
            .logo img {
                height: 40px;
            }
        }

        @media (max-width: 576px) {
            nav {
                padding: 8px 15px;
            }
            
            .logo img {
                height: 35px;
            }
            
            .nav-icons {
                gap: 8px;
            }
            
            .nav-icons a {
                font-size: 0.9rem;
            }
        }
    </style>

    @vite(['resources/js/app.js'])
</head>
<body>
    <nav>
        <div class="navbar-container">
            <div class="logo">
                 <a href="{{ route('home') }}" class="d-inline-block" aria-label="Ir al inicio">
                        <img src="{{ asset('images/logo.png') }}" alt="ElectraHome Logo" style="height: 70px;">
                    </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
                <a href="{{ route('shop.index') }}">Productos</a>
                <a href="{{ route('about') }}">Quiénes Somos</a>
                <a href="{{ route('insiders') }}">Miembros</a>
                <a href="{{ route('chefs') }}">Contacto</a>
                <a href="{{ route('wholesale') }}">Servicios</a>

                @role('admin')
                <div class="dropdown admin-dropdown">
                    <a class="dropdown-toggle admin-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-tools me-2"></i>Panel Admin <i class="fas fa-chevron-down ms-1"></i>
                    </a>
                    <ul class="dropdown-menu admin-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">
                            <i class="fas fa-box me-2"></i>Admin Productos
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('categories.index') }}">
                            <i class="fas fa-folder me-2"></i>Admin Categorías
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.countries.index') }}">
                            <i class="fas fa-globe me-2"></i>Gestionar Paises
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.cities.index') }}">
                            <i class="fas fa-city me-2"></i>Gestionar Ciudades
                        </a></li>
                    </ul>
                </div>
                @endrole
            </div>

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

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars" id="menuIcon"></i>
            </button>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <script>
        function toggleMobileMenu() {
            // Función para menú móvil (puedes implementarla después)
            console.log('Mobile menu toggle');
        }

        function deleteImageAjax(imageId) {
            if (confirm('⚠️ ¿Estás seguro de que quieres eliminar esta imagen? Esta acción no se puede deshacer.')) {
                // Mostrar indicador de carga
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Eliminando...';
                button.disabled = true;

                // Realizar petición AJAX
                fetch(`/admin/products/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Eliminar el elemento del DOM con animación
                        const imageElement = document.getElementById(`image-${imageId}`);
                        imageElement.style.transition = 'opacity 0.3s ease';
                        imageElement.style.opacity = '0';
                        
                        setTimeout(() => {
                            imageElement.remove();
                            // Actualizar contador de imágenes
                            updateImageCounter();
                        }, 300);
                        
                        // Mostrar mensaje de éxito
                        showToast('Imagen eliminada exitosamente', 'success');
                    } else {
                        throw new Error(data.message || 'Error eliminando imagen');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Restaurar botón en caso de error
                    button.innerHTML = originalText;
                    button.disabled = false;
                    showToast('Error eliminando imagen', 'error');
                });
            }
        }

        function updateImageCounter() {
            const container = document.getElementById('images-container');
            const imageCount = container.children.length;
            const counterElement = container.parentElement.querySelector('small');
            
            if (imageCount === 0) {
                counterElement.style.display = 'none';
            } else {
                counterElement.textContent = `Actualmente tiene ${imageCount} imagen${imageCount > 1 ? 'es' : ''}`;
            }
        }

        function showToast(message, type) {
            // Crear toast simple
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