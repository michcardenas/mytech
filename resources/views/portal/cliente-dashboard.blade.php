<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis proyectos · {{ $nombreVisible }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue: #2563EB;
            --grad: linear-gradient(135deg, #60A5FA 0%, #2563EB 100%);
            --shadow: 0 4px 15px rgba(0,0,0,0.06);
        }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #F6F7F9; color: #0F172A; min-height: 100vh; }
        .wrap { max-width: 1080px; margin: 0 auto; padding: 1.75rem 1.25rem 3rem; }

        .head { background: var(--grad); color: white; border-radius: 18px; padding: 1.5rem 1.75rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .head-info { display: flex; align-items: center; gap: 1rem; min-width: 0; }
        .head-avatar { width: 52px; height: 52px; border-radius: 50%; background: rgba(255,255,255,0.22); border: 2px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; flex-shrink: 0; }
        .head h1 { font-size: 1.25rem; font-weight: 800; margin-bottom: 0.15rem; letter-spacing:-.02em; }
        .head .meta { font-size: 0.82rem; opacity: 0.9; }
        .btn-logout { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.28); color: white; padding: 0.5rem 0.95rem; border-radius: 10px; font-weight: 600; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-logout:hover { background: rgba(255,255,255,0.22); }

        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.85rem; margin-bottom: 1.25rem; }
        .kpi { background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 0.9rem 1.1rem; position: relative; overflow: hidden; }
        .kpi::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: #CBD5E1; }
        .kpi.blue::before { background: var(--blue); }
        .kpi.green::before { background: #16A34A; }
        .kpi.amber::before { background: #F59E0B; }
        .kpi-label { font-size: 0.7rem; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; }
        .kpi-value { font-size: 1.35rem; font-weight: 800; color: #0F172A; margin-top: 0.15rem; letter-spacing:-.02em; }

        .panel { background: white; border: 1px solid #E5E7EB; border-radius: 14px; padding: 1.15rem 1.4rem; margin-bottom: 1.25rem; }
        .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem; }
        .panel-head h3 { font-size: 1rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 0.5rem; }
        .panel-head .muted { font-size: 0.78rem; color: #94A3B8; }

        table { width: 100%; border-collapse: collapse; font-size: 0.87rem; font-variant-numeric: tabular-nums; }
        th { text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.03em; color: #94A3B8; font-weight: 700; padding: 0.55rem 0.75rem; border-bottom: 2px solid #F1F5F9; white-space: nowrap; }
        td { padding: 0.7rem 0.75rem; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #FAFBFC; }
        .row-name { font-weight: 700; color: #0F172A; }
        .estado { display: inline-flex; align-items: center; gap: .3rem; padding: .2rem .55rem; border-radius: 999px; font-size: .7rem; font-weight: 700; }
        .estado .dot { width: 6px; height: 6px; border-radius: 999px; background: currentColor; }
        .estado.cotizado { background: #FEF3C7; color: #B45309; }
        .estado.en_progreso { background: #DBEAFE; color: #1D4ED8; }
        .estado.pausado { background: #F1F5F9; color: #475569; }
        .estado.completado { background: #DCFCE7; color: #166534; }
        .estado.cancelado { background: #FEE2E2; color: #B91C1C; }
        .pct-bar { display: flex; align-items: center; gap: .5rem; min-width: 130px; }
        .pct-bar .bar { flex: 1; height: 6px; border-radius: 999px; background: #F1F5F9; overflow: hidden; }
        .pct-bar .bar > span { display: block; height: 100%; border-radius: 999px; transition: width .3s; }
        .pct-bar .txt { font-size: .75rem; font-weight: 700; color: #0F172A; min-width: 34px; text-align: right; }

        .empty { text-align: center; padding: 2.5rem 1rem; color: #94A3B8; }
        .empty i { font-size: 2rem; opacity: .4; margin-bottom: .5rem; display: block; }

        .fact-info { background: #EFF6FF; border: 1px dashed #93C5FD; color: #1E40AF; border-radius: 12px; padding: 0.85rem 1rem; font-size: 0.85rem; display: flex; gap: 0.6rem; align-items: flex-start; }
        .fact-info i { font-size: 1rem; margin-top: 0.15rem; }

        /* Botón recibo */
        .btn-recibo {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .4rem .8rem; border-radius: 8px;
            background: #2563EB; color: #fff; text-decoration: none;
            font-size: .75rem; font-weight: 700;
            transition: all .15s;
        }
        .btn-recibo:hover { background: #1D4ED8; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(37,99,235,.35); color: #fff; }
        .btn-recibo i { font-size: .7rem; }

        .metodo-pill {
            display: inline-block;
            padding: .18rem .55rem;
            border-radius: 999px;
            background: #F1F5F9;
            color: #475569;
            font-size: .72rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="head">
        <div class="head-info">
            <div class="head-avatar">{{ strtoupper(mb_substr($nombreVisible, 0, 1)) }}</div>
            <div>
                <h1>{{ $nombreVisible }}</h1>
                <div class="meta">{{ $proyectos->count() }} {{ $proyectos->count() === 1 ? 'proyecto' : 'proyectos' }} con nosotros</div>
            </div>
        </div>
        <form method="POST" action="{{ route('portal.cliente.logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Salir</button>
        </form>
    </div>

    @php
        $totalContratado = $proyectos->sum('precio');
        $totalPagado = $proyectos->sum(fn ($p) => (float) ($p->pagado ?? 0));
        $totalSaldo = max($totalContratado - $totalPagado, 0);
    @endphp

    <div class="kpi-grid">
        <div class="kpi blue">
            <div class="kpi-label">Total contratado</div>
            <div class="kpi-value">${{ number_format((float) $totalContratado, 0, ',', '.') }}</div>
        </div>
        <div class="kpi green">
            <div class="kpi-label">Total pagado</div>
            <div class="kpi-value">${{ number_format((float) $totalPagado, 0, ',', '.') }}</div>
        </div>
        <div class="kpi amber">
            <div class="kpi-label">Saldo pendiente</div>
            <div class="kpi-value">${{ number_format((float) $totalSaldo, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-briefcase" style="color:#2563EB;"></i> Mis proyectos</h3>
            <span class="muted">{{ $proyectos->count() }} en total</span>
        </div>
        @if($proyectos->isEmpty())
            <div class="empty">
                <i class="fas fa-folder-open"></i>
                <div>Aún no tienes proyectos registrados.</div>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Estado</th>
                        <th>Precio</th>
                        <th>Pagado</th>
                        <th>Entrega</th>
                        <th>Próxima factura</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proyectos as $p)
                        @php
                            $pagado = (float) ($p->pagado ?? 0);
                            $precio = (float) $p->precio;
                            $pct = $precio > 0 ? min(100, round(($pagado / $precio) * 100)) : 0;
                            $pctColor = $pct >= 100 ? '#16A34A' : ($pct >= 50 ? '#2563EB' : ($pct > 0 ? '#F59E0B' : '#CBD5E1'));
                            $simbolo = $p->moneda === 'USD' ? 'US$' : ($p->moneda === 'EUR' ? '€' : '$');
                        @endphp
                        <tr>
                            <td class="row-name">{{ $p->nombre }}</td>
                            <td><span class="estado {{ $p->estado }}"><span class="dot"></span>{{ $p->estado_label }}</span></td>
                            <td>{{ $simbolo }}{{ number_format($precio, 0, ',', '.') }}</td>
                            <td>
                                <div class="pct-bar">
                                    <div class="bar"><span style="width:{{ $pct }}%; background:{{ $pctColor }};"></span></div>
                                    <span class="txt">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($p->es_recurrente)
                                    <span style="color:#7C3AED; font-weight:600;">Recurrente</span>
                                @elseif($p->fecha_entrega)
                                    {{ $p->fecha_entrega->format('d/m/Y') }}
                                @else
                                    <span style="color:#94A3B8;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($p->fecha_facturacion)
                                    {{ $p->fecha_facturacion->format('d/m/Y') }}
                                @else
                                    <span style="color:#94A3B8;">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if(isset($cuentasCobro) && $cuentasCobro->isNotEmpty())
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-file-invoice" style="color:#B45309;"></i> Cuentas de cobro por pagar</h3>
                <span class="muted">{{ $cuentasCobro->count() }} pendiente{{ $cuentasCobro->count() === 1 ? '' : 's' }}</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Proyecto</th>
                        <th style="text-align:right;">Valor</th>
                        <th style="text-align:right;">Cuenta de cobro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cuentasCobro as $cc)
                        @php $sc = $cc->moneda === 'USD' ? 'US$' : ($cc->moneda === 'EUR' ? '€' : '$'); @endphp
                        <tr>
                            <td class="row-name">{{ $cc->numero_doc }}</td>
                            <td>{{ $cc->project->nombre ?? '—' }}</td>
                            <td style="text-align:right; font-weight:800; color:#B45309;">{{ $sc }}{{ number_format((float) $cc->monto, 0, ',', '.') }} <span style="color:#94A3B8; font-size:.72rem; font-weight:600;">{{ $cc->moneda }}</span></td>
                            <td style="text-align:right;">
                                <a href="{{ route('portal.cliente.cuenta-cobro', $cc) }}" target="_blank" class="btn-recibo" style="background:#B45309;">
                                    <i class="fas fa-file-download"></i> Ver / descargar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-file-invoice-dollar" style="color:#2563EB;"></i> Mis recibos de pago</h3>
            <span class="muted">{{ $pagos->count() }} {{ $pagos->count() === 1 ? 'recibo emitido' : 'recibos emitidos' }}</span>
        </div>

        @if($pagos->isEmpty())
            <div class="empty">
                <i class="fas fa-receipt"></i>
                <div>Aún no tienes pagos registrados.</div>
                <p style="margin-top:.3rem; font-size:.85rem;">Cuando registremos un pago tuyo, aquí aparecerá el recibo con el membrete oficial.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Proyecto</th>
                        <th>Método</th>
                        <th>Referencia</th>
                        <th style="text-align:right;">Monto</th>
                        <th style="text-align:right;">Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                        @php $simbolo = $pago->project->moneda === 'USD' ? 'US$' : ($pago->project->moneda === 'EUR' ? '€' : '$'); @endphp
                        <tr>
                            <td class="row-name">{{ $pago->fecha->format('d/m/Y') }}</td>
                            <td>{{ $pago->project->nombre }}</td>
                            <td>
                                @if($pago->metodo)
                                    <span class="metodo-pill">{{ $pago->metodo }}</span>
                                @else
                                    <span style="color:#94A3B8;">—</span>
                                @endif
                            </td>
                            <td style="color:#64748B; font-size:.82rem;">{{ $pago->referencia ?: '—' }}</td>
                            <td style="text-align:right; font-weight:700; color:#0F172A;">
                                {{ $simbolo }}{{ number_format((float) $pago->monto, 0, ',', '.') }}
                                <span style="color:#94A3B8; font-size:.72rem; font-weight:600;">{{ $pago->project->moneda }}</span>
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('portal.cliente.receipt', $pago) }}" target="_blank" class="btn-recibo" title="Descargar recibo con membrete">
                                    <i class="fas fa-file-download"></i> Descargar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="fact-info" style="margin-top:1rem;">
                <i class="fas fa-info-circle"></i>
                <div>
                    Cada recibo se genera con el membrete oficial de <strong>MYTECH SOLUTIONS S.A.S · NIT 901.923.467-5</strong>. Al abrirlo puedes imprimirlo o guardarlo como PDF con <kbd style="background:#DBEAFE; padding:.1rem .35rem; border-radius:4px; font-family:monospace;">Ctrl+P</kbd>.
                </div>
            </div>
        @endif
    </div>
</div>
</body>
</html>
