@extends('layouts.app')

@section('content')
<style>
.register-page {
    background: linear-gradient(135deg, #f8f9fa 0%, rgba(1, 25, 4, 0.05) 100%);
    min-height: 100vh;
    padding: 40px 0;
    font-family: 'Inter', sans-serif;
}

.register-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
}

.register-image-section {
    background: linear-gradient(135deg, #011904 0%, #022a07 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 40px;
    position: relative;
    overflow: hidden;
}

.register-image-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: url('{{ asset("images/carrusel1.png") }}') center/cover;
    opacity: 0.1;
    transform: rotate(-15deg);
}

.register-brand {
    text-align: center;
    position: relative;
    z-index: 2;
}

.brand-logo {
    height: 60px;
    margin-bottom: 30px;
    filter: brightness(0) invert(1);
}

.brand-title {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 20px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.brand-subtitle {
    font-size: 1.1rem;
    line-height: 1.6;
    opacity: 0.9;
    margin-bottom: 40px;
}

.brand-features {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1rem;
}

.feature-item i {
    color: #c41e3a;
    font-size: 1.2rem;
}

.register-form-section {
    background: white;
    display: flex;
    align-items: center;
}

.register-form-container {
    padding: 60px 50px;
    width: 100%;
}

.form-header {
    text-align: center;
    margin-bottom: 40px;
}

.form-header h3 {
    font-size: 2.2rem;
    font-weight: 800;
    color: #011904;
    margin-bottom: 15px;
}

.form-header p {
    color: #666;
    font-size: 1rem;
    line-height: 1.5;
}

.register-form .form-group {
    margin-bottom: 25px;
}

.register-form .form-label {
    color: #011904;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}

.register-form .form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px 20px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
    width: 100%;
    box-sizing: border-box;
}

.register-form .form-control:focus {
    border-color: #011904;
    box-shadow: 0 0 0 0.2rem rgba(1, 25, 4, 0.25);
    background: white;
    outline: none;
}

.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #011904;
}

.register-btn {
    width: 100%;
    background: linear-gradient(135deg, #011904 0%, #022a07 100%);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(1, 25, 4, 0.3);
    margin-bottom: 25px;
    cursor: pointer;
}

.register-btn:hover {
    background: linear-gradient(135deg, #022a07 0%, #033309 100%);
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(1, 25, 4, 0.4);
}

.login-link {
    text-align: center;
    margin-top: 20px;
}

.login-link p {
    color: #666;
    margin: 0;
}

.login-link a {
    color: #c41e3a;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.login-link a:hover {
    color: #e74c3c;
    text-decoration: underline;
}

.alert-success {
    background: rgba(40, 167, 69, 0.1);
    border: 2px solid rgba(40, 167, 69, 0.3);
    color: #28a745;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 25px;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
    display: block;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Responsive */
@media (max-width: 992px) {
    .register-container {
        grid-template-columns: 1fr;
        max-width: 600px;
    }
    
    .register-image-section {
        padding: 40px 30px;
    }
    
    .brand-title {
        font-size: 2rem;
    }
    
    .brand-features {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
    
    .feature-item {
        font-size: 0.9rem;
    }
    
    .register-form-container {
        padding: 40px 30px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .register-page {
        padding: 20px 0;
    }
    
    .register-container {
        margin: 20px;
        border-radius: 15px;
    }
    
    .register-image-section {
        padding: 30px 20px;
    }
    
    .brand-title {
        font-size: 1.8rem;
    }
    
    .brand-subtitle {
        font-size: 1rem;
    }
    
    .register-form-container {
        padding: 30px 25px;
    }
    
    .form-header h3 {
        font-size: 1.8rem;
    }
}

@media (max-width: 576px) {
    .brand-features {
        flex-direction: column;
        align-items: center;
    }
    
    .register-form-container {
        padding: 25px 20px;
    }
}
</style>

<div class="register-page">
    <div class="register-container">
        <!-- Sección de Imagen/Marca -->
        <div class="register-image-section">
            <div class="register-brand">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo"> --}}
                <h1 class="brand-title">¡Únete a Nosotros!</h1>
                <p class="brand-subtitle">
                    Crea tu cuenta y accede a todas las funcionalidades de nuestra plataforma. 
                    Gestiona tus proyectos de manera eficiente y colabora con tu equipo.
                </p>
                <div class="brand-features">
                    <div class="feature-item">
                        <i class="fas fa-user-plus"></i>
                        <span>Registro rápido y seguro</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Protección de datos garantizada</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-rocket"></i>
                        <span>Acceso inmediato a la plataforma</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <span>Colaboración en tiempo real</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección del Formulario -->
        <div class="register-form-section">
            <div class="register-form-container">
                <div class="form-header">
                    <h3>Crear Cuenta</h3>
                    <p>Completa los siguientes datos para registrarte</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="register-form">
                    @csrf

                    <div class="form-grid">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user" style="margin-right: 8px; color: #c41e3a;"></i>
                                {{ __('Nombre Completo') }}
                            </label>
                            <input id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="name"
                                   placeholder="Ingresa tu nombre completo" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope" style="margin-right: 8px; color: #c41e3a;"></i>
                                {{ __('Correo Electrónico') }}
                            </label>
                            <input id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="username"
                                   placeholder="correo@ejemplo.com" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-grid">
                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock" style="margin-right: 8px; color: #c41e3a;"></i>
                                {{ __('Contraseña') }}
                            </label>
                            <div class="password-input">
                                <input id="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       type="password"
                                       name="password"
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Mínimo 8 caracteres" />
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock" style="margin-right: 8px; color: #c41e3a;"></i>
                                {{ __('Confirmar Contraseña') }}
                            </label>
                            <div class="password-input">
                                <input id="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       type="password"
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Repite tu contraseña" />
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="register-btn">
                        <i class="fas fa-user-plus" style="margin-right: 10px;"></i>
                        {{ __('Crear Cuenta') }}
                    </button>

                    <div class="login-link">
                        <p>
                            ¿Ya tienes una cuenta? 
                            <a href="{{ route('login') }}">{{ __('Inicia sesión aquí') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

// Validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    function validatePasswordMatch() {
        if (confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.classList.add('is-invalid');
        } else {
            confirmPassword.classList.remove('is-invalid');
        }
    }

    password.addEventListener('input', validatePasswordMatch);
    confirmPassword.addEventListener('input', validatePasswordMatch);
});
</script>
@endsection