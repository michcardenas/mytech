<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis tareas · {{ $developer->nombre }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --purple: #7c3aed; --grad: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; color: #0f172a; min-height: 100vh; }
        .wrap { max-width: 1500px; margin: 0 auto; padding: 1.25rem 1rem 3rem; }
        .head-bar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; background: #fff; border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); margin-bottom: 1rem; gap: 1rem; flex-wrap: wrap; }
        .head-info { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
        .head-avatar { width: 44px; height: 44px; border-radius: 12px; background: var(--grad); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
        .head-name { font-size: 1.02rem; font-weight: 800; line-height: 1.15; }
        .head-sub { font-size: 0.78rem; color: #94a3b8; margin-top: 0.15rem; }
        .head-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .h-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 700; font-size: 0.8rem; text-decoration: none; border: none; cursor: pointer; background: #f1f5f9; color: #475569; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="head-bar">
            <div class="head-info">
                <div class="head-avatar"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="head-name">Mis tareas</div>
                    <div class="head-sub">Todas tus tareas en todos los proyectos · {{ $tasks->count() }}</div>
                </div>
            </div>
            <div class="head-actions">
                <a href="{{ route('portal.developer.dashboard') }}" class="h-btn"><i class="fas fa-arrow-left"></i> Mis proyectos</a>
                <form action="{{ route('portal.developer.logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="h-btn"><i class="fas fa-sign-out-alt"></i> Salir</button>
                </form>
            </div>
        </div>

        @include('partials.board.global')
    </div>
</body>
</html>
