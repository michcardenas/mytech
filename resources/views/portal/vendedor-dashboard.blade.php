<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard · {{ $vendedor->nombre }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green: #059669;
            --grad: linear-gradient(135deg, #34d399 0%, #059669 100%);
            --shadow: 0 4px 15px rgba(0,0,0,0.06);
        }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; color: #0f172a; min-height: 100vh; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem; }

        .head { background: var(--grad); color: white; border-radius: 18px; padding: 1.75rem 2rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .head-info { display: flex; align-items: center; gap: 1rem; min-width: 0; }
        .head-avatar { width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,0.22); border: 2px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 800; flex-shrink: 0; }
        .head h1 { font-size: 1.35rem; font-weight: 800; margin-bottom: 0.2rem; }
        .head .meta { font-size: 0.82rem; opacity: 0.92; display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn-logout { background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.4); color: white; padding: 0.55rem 1rem; border-radius: 10px; font-weight: 600; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-logout:hover { background: rgba(255,255,255,0.35); }

        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
        .kpi { background: white; border-radius: 14px; padding: 1.1rem 1.25rem; box-shadow: var(--shadow); }
        .kpi.comision { border-left: 4px solid #7c3aed; }
        .kpi.pagado { border-left: 4px solid var(--green); }
        .kpi.saldo { border-left: 4px solid #dc3545; }
        .kpi-label { font-size: 0.7rem; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem; }
        .kpi-value { font-size: 1.5rem; font-weight: 800; line-height: 1.1; }
        .kpi.comision .kpi-value { color: #7c3aed; }
        .kpi.pagado .kpi-value { color: var(--green); }
        .kpi.saldo .kpi-value { color: #dc3545; }
        .kpi-sub { font-size: 0.75rem; color: #94a3b8; margin-top: 0.3rem; }

        .progress-card { background: white; border-radius: 14px; padding: 1.25rem 1.5rem; box-shadow: var(--shadow); margin-bottom: 1.25rem; }
        .progress-label { display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem; }
        .progress-bar { width: 100%; height: 10px; background: #e2e8f0; border-radius: 5px; overflow: hidden; }
        .progress-fill { height: 100%; background: var(--grad); border-radius: 5px; transition: width 0.6s ease; }

        .panel { background: white; border-radius: 14px; padding: 1.25rem 1.5rem; box-shadow: var(--shadow); margin-bottom: 1.25rem; }
        .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem; }
        .panel-head h3 { font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .panel-head .muted { font-size: 0.78rem; color: #888; }

        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        th { text-align: left; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.3px; color: #888; font-weight: 700; padding: 0.55rem 0.75rem; border-bottom: 2px solid #f1f3f5; white-space: nowrap; }
        td { padding: 0.7rem 0.75rem; border-bottom: 1px solid #f1f3f5; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafbfc; }
        .est-pill { padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2px; display: inline-block; }
        .mono { font-weight: 700; text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
        .mono.com { color: #7c3aed; }
        .mono.pag { color: var(--green); }
        .mono.sal { color: #dc3545; }
        .mono.mute { color: #bbb; }
        .row-name { font-weight: 600; }
        .row-sub { font-size: 0.76rem; color: #888; margin-top: 0.15rem; }
        .empty { text-align: center; color: #94a3b8; padding: 2.5rem 1rem; font-size: 0.9rem; }
        .empty i { display: block; font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem; }

        @media (max-width: 768px) {
            .wrap { padding: 1rem; }
            .head { flex-direction: column; text-align: center; }
            .head-info { flex-direction: column; }
            table th:nth-child(3), table td:nth-child(3) { display: none; }
            table th:nth-child(4), table td:nth-child(4) { display: none; }
        }
    </style>
</head>
<body>
@php
    $fmtCop = fn ($v) => '$' . number_format((float) $v, 0, ',', '.');
    $initials = collect(explode(' ', trim($vendedor->nombre)))->map(fn ($n) => mb_substr($n, 0, 1))->take(2)->implode('');
    $initials = $initials ? mb_strtoupper($initials) : '·';
@endphp
<div class="wrap">
    <div class="head">
        <div class="head-info">
            <div class="head-avatar">{{ $initials }}</div>
            <div>
                <h1><i class="fas fa-handshake"></i> {{ $vendedor->nombre }}</h1>
                <div class="meta">
                    @if($kpis['desde'])<span><i class="fas fa-calendar-day"></i> Desde {{ $kpis['desde']->locale('es')->isoFormat('DD MMM YYYY') }}</span>@endif
                    @if($kpis['ultimo_pago'])<span><i class="fas fa-clock"></i> Último pago {{ $kpis['ultimo_pago']->locale('es')->isoFormat('DD MMM YYYY') }}</span>@endif
                    <span><i class="fas fa-briefcase"></i> {{ $kpis['proyectos_total'] }} proyectos ({{ $kpis['proyectos_activos'] }} activos)</span>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('portal.vendedor.logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Salir</button>
        </form>
    </div>

    <div class="kpi-grid">
        <div class="kpi comision">
            <div class="kpi-label"><i class="fas fa-percent"></i> Comisión total</div>
            <div class="kpi-value">{{ $fmtCop($kpis['comision_cop']) }}</div>
            <div class="kpi-sub">suma de comisiones pactadas</div>
        </div>
        <div class="kpi pagado">
            <div class="kpi-label"><i class="fas fa-check-circle"></i> Pagado a ti</div>
            <div class="kpi-value">{{ $fmtCop($kpis['abonado_cop']) }}</div>
            <div class="kpi-sub">{{ $kpis['porcentaje'] }}% del total · {{ $payments->count() }} pagos</div>
        </div>
        <div class="kpi saldo">
            <div class="kpi-label"><i class="fas fa-hourglass-half"></i> Pendiente</div>
            <div class="kpi-value">{{ $fmtCop($kpis['saldo_cop']) }}</div>
            <div class="kpi-sub">saldo a tu favor</div>
        </div>
    </div>

    @if($kpis['comision_cop'] > 0)
        <div class="progress-card">
            <div class="progress-label">
                <span>Progreso de pago global</span>
                <span>{{ $kpis['porcentaje'] }}%</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: {{ min($kpis['porcentaje'], 100) }}%;"></div></div>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-briefcase"></i> Proyectos gestionados</h3>
            <span class="muted">{{ $proyectosResumen->count() }} proyectos</span>
        </div>
        @if($proyectosResumen->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Proyecto</th>
                            <th>Estado</th>
                            <th>Inicio</th>
                            <th>Tipo</th>
                            <th style="text-align:right;">Comisión</th>
                            <th style="text-align:right;">Pagado</th>
                            <th style="text-align:right;">Pendiente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proyectosResumen as $proj)
                            <tr>
                                <td>
                                    <div class="row-name">{{ $proj['nombre'] }}</div>
                                    <div class="row-sub">{{ $proj['cliente'] }}</div>
                                </td>
                                <td><span class="est-pill" style="background: {{ $proj['estado_color'] }}15; color: {{ $proj['estado_color'] }};">{{ $proj['estado_label'] }}</span></td>
                                <td style="color:#64748b; font-size:0.8rem;">{{ $proj['fecha_inicio'] ? $proj['fecha_inicio']->format('d/m/Y') : '—' }}</td>
                                <td style="font-size:0.78rem; color:#64748b;">
                                    @if($proj['comision_tipo'] === 'porcentaje')
                                        {{ $proj['comision_valor'] }}% sobre margen
                                    @elseif($proj['comision_tipo'] === 'monto')
                                        Monto fijo
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="mono com">{{ $fmtCop($proj['comision']) }}</td>
                                <td class="mono pag">{{ $fmtCop($proj['abonado']) }}</td>
                                <td class="mono {{ $proj['saldo'] > 0 ? 'sal' : 'mute' }}">{{ $fmtCop($proj['saldo']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty"><i class="fas fa-folder-open"></i><p>Aún no tienes proyectos asignados.</p></div>
        @endif
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-paper-plane"></i> Historial de pagos</h3>
            <span class="muted">{{ $payments->count() }} pagos</span>
        </div>
        @if($payments->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Proyecto / Cliente</th>
                            <th>Método</th>
                            <th>Referencia</th>
                            <th>Nota</th>
                            <th style="text-align:right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $pay)
                            <tr>
                                <td style="white-space:nowrap;">{{ $pay->fecha->format('d/m/Y') }}</td>
                                <td>
                                    <div class="row-name">{{ optional($pay->project)->nombre ?? '—' }}</div>
                                    <div class="row-sub">{{ optional($pay->project)->cliente_nombre ?? '' }}</div>
                                </td>
                                <td style="font-size:0.8rem; color:#64748b;">{{ $pay->metodo ?: '—' }}</td>
                                <td style="font-family: ui-monospace, Menlo, monospace; font-size:0.78rem; color:#64748b;">{{ $pay->referencia ?: '—' }}</td>
                                <td style="font-size:0.78rem; color:#94a3b8; font-style:italic;">{{ $pay->nota ?: '—' }}</td>
                                <td class="mono pag">+{{ $fmtCop($pay->monto) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty"><i class="fas fa-inbox"></i><p>No tienes pagos registrados todavía.</p></div>
        @endif
    </div>
</div>
</body>
</html>
