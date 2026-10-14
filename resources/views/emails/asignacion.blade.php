@php
    $esTarea = in_array($tipo, ['tarea', 'tareas']);
    $kicker = match ($tipo) {
        'tarea' => 'Nueva tarea asignada',
        'tareas' => 'Nuevas tareas asignadas',
        default => 'Nuevo proyecto asignado',
    };
    $intro = match ($tipo) {
        'tarea' => 'Se te ha asignado una nueva tarea en el tablero. Estos son los detalles:',
        'tareas' => 'Se te han asignado nuevas tareas en el tablero. Este es el resumen:',
        default => 'Se te ha asignado un nuevo proyecto. Estos son los detalles:',
    };
    $etiquetaItem = $tipo === 'tareas' ? 'Tareas' : ($esTarea ? 'Tarea' : 'Proyecto');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kicker }}</title>
</head>
<body style="margin:0; padding:0; background:#F1F5F9; font-family:Arial, Helvetica, sans-serif; color:#334155;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9; padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:14px; overflow:hidden; border:1px solid #E2E8F0;">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#1E293B 0%,#0F172A 100%); padding:22px 28px;">
                            <div style="color:#ffffff; font-size:18px; font-weight:bold; letter-spacing:-0.02em;">MY Tech Solutions</div>
                            <div style="color:#93C5FD; font-size:12px; margin-top:2px;">{{ $kicker }}</div>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 4px; font-size:15px; color:#0F172A;">Hola <strong>{{ $devNombre }}</strong>,</p>
                            <p style="margin:0 0 18px; font-size:14px; line-height:1.6; color:#475569;">{{ $intro }}</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.05em; color:#94A3B8; font-weight:bold;">{{ $etiquetaItem }}</div>
                                        <div style="font-size:16px; font-weight:bold; color:#0F172A; margin-top:3px;">{{ $titulo }}</div>
                                    </td>
                                </tr>
                                @foreach($detalles as $label => $valor)
                                    <tr>
                                        <td style="padding:0 18px 12px;">
                                            <span style="display:inline-block; min-width:110px; font-size:12px; color:#94A3B8; font-weight:bold;">{{ $label }}:</span>
                                            <span style="font-size:13px; color:#334155;">{{ $valor }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            @if($url)
                                <table role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="background:#2563EB; border-radius:9px;">
                                            <a href="{{ $url }}" style="display:inline-block; padding:12px 24px; color:#ffffff; font-size:14px; font-weight:bold; text-decoration:none;">Entrar al portal</a>
                                        </td>
                                    </tr>
                                </table>
                                <p style="margin:14px 0 0; font-size:12px; color:#94A3B8;">Si el botón no funciona, copia este enlace: {{ $url }}</p>
                            @endif
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="border-top:1px solid #F1F5F9; padding:16px 28px; background:#FAFBFC;">
                            <p style="margin:0; font-size:11px; color:#94A3B8;">Este es un aviso automático de MyTech Solutions. No respondas a este correo.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
