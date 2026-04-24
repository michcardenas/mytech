<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo }} · Mytech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: {{ $color === 'green' ? 'linear-gradient(135deg, #34d399 0%, #059669 100%)' : 'linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%)' }};
        }
        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.18);
            max-width: 420px;
            width: 100%;
            overflow: hidden;
        }
        .login-head {
            background: {{ $color === 'green' ? 'linear-gradient(135deg, #34d399 0%, #059669 100%)' : 'linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%)' }};
            color: white;
            padding: 2rem 1.75rem 1.5rem;
            text-align: center;
        }
        .login-head .icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(255,255,255,0.22);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1rem;
            border: 2px solid rgba(255,255,255,0.4);
        }
        .login-head h1 { font-size: 1.35rem; font-weight: 800; margin: 0 0 0.35rem; letter-spacing: -0.02em; }
        .login-head p { opacity: 0.92; font-size: 0.85rem; }
        .login-body { padding: 2rem 1.75rem; }
        .login-body label {
            display: block; font-weight: 700; font-size: 0.78rem;
            text-transform: uppercase; letter-spacing: 0.4px; color: #64748b;
            margin-bottom: 0.45rem;
        }
        .phone-wrap { position: relative; margin-bottom: 1rem; }
        .phone-wrap .prefix {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            color: #64748b; font-weight: 600; pointer-events: none; font-size: 0.92rem;
            border-right: 1px solid #e9ecef; padding-right: 0.6rem;
        }
        .phone-wrap input {
            width: 100%; padding: 0.95rem 1rem 0.95rem 4rem;
            border: 2px solid #e9ecef; border-radius: 12px;
            font-size: 1rem; font-weight: 600; color: #0f172a;
            transition: all 0.2s;
        }
        .phone-wrap input:focus {
            outline: none;
            border-color: {{ $color === 'green' ? '#059669' : '#7c3aed' }};
            box-shadow: 0 0 0 4px {{ $color === 'green' ? 'rgba(5,150,105,0.12)' : 'rgba(124,58,237,0.12)' }};
        }
        .hint { font-size: 0.78rem; color: #94a3b8; margin-top: -0.5rem; margin-bottom: 1.25rem; }
        .btn-submit {
            width: 100%; padding: 0.95rem 1rem;
            border: none; border-radius: 12px;
            background: {{ $color === 'green' ? 'linear-gradient(135deg, #34d399 0%, #059669 100%)' : 'linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%)' }};
            color: white; font-weight: 700; font-size: 0.95rem;
            cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 10px 25px {{ $color === 'green' ? 'rgba(5,150,105,0.35)' : 'rgba(124,58,237,0.35)' }}; }
        .error-box {
            background: #fef2f2; color: #c53030; border-left: 4px solid #dc3545;
            padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.85rem;
            margin-bottom: 1rem; font-weight: 500;
        }
        .success-box {
            background: #ecfdf5; color: #065f46; border-left: 4px solid #059669;
            padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.85rem;
            margin-bottom: 1rem; font-weight: 500;
        }
        .login-foot { padding: 1rem 1.75rem; background: #fafbfc; text-align: center; font-size: 0.78rem; color: #94a3b8; border-top: 1px solid #f1f3f5; }
        .login-foot a { color: {{ $color === 'green' ? '#059669' : '#7c3aed' }}; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-head">
            <div class="icon"><i class="fas {{ $icon }}"></i></div>
            <h1>{{ $titulo }}</h1>
            <p>{{ $subtitulo }}</p>
        </div>
        <form method="POST" action="{{ $route_login }}" class="login-body">
            @csrf

            @if(session('success'))
                <div class="success-box"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="error-box"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}</div>
            @endif

            <label for="telefono">Tu número de teléfono</label>
            <div class="phone-wrap">
                <span class="prefix">+57</span>
                <input type="tel" id="telefono" name="telefono"
                       placeholder="3001234567" required autofocus
                       value="{{ old('telefono') }}"
                       inputmode="numeric" autocomplete="tel-national">
            </div>
            <p class="hint"><i class="fas fa-info-circle"></i> Sin el +57, solo los 10 dígitos.</p>

            <button type="submit" class="btn-submit">
                <i class="fas fa-arrow-right"></i> Ingresar
            </button>
        </form>
        <div class="login-foot">
            @if($role === 'developer')
                ¿Eres gestor? <a href="{{ route('portal.vendedor.login.show') }}">Ingresar al portal de gestores</a>
            @else
                ¿Eres desarrollador? <a href="{{ route('portal.developer.login.show') }}">Ingresar al portal de desarrolladores</a>
            @endif
        </div>
    </div>
</body>
</html>
