@extends('layouts.app_admin')

@section('content')
<style>
    .lq-wrap { max-width:1320px; margin:0 auto; padding:1.5rem 1.75rem 3rem; background:#F6F7F9; }

    .lq-hero {
        background: linear-gradient(135deg,#1E293B 0%,#0F172A 100%);
        color:#fff; border-radius:16px; padding:1.5rem 1.75rem; margin-bottom:1.25rem;
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;
    }
    .lq-hero h1 { font-size:1.35rem; font-weight:800; margin:0; display:flex; align-items:center; gap:.55rem; color:#fff; letter-spacing:-.02em; }
    .lq-hero p  { font-size:.82rem; opacity:.75; margin:.2rem 0 0; }
    .lq-hero .icon { display:inline-flex; width:36px; height:36px; border-radius:10px; background:rgba(59,130,246,.25); align-items:center; justify-content:center; color:#93C5FD; }
    .lq-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.55rem 1rem; border-radius:10px; font-weight:600; font-size:.83rem; text-decoration:none; border:1px solid rgba(255,255,255,.14); background:rgba(255,255,255,.08); color:#E2E8F0; transition:all .15s; cursor:pointer; }
    .lq-btn:hover { background:rgba(255,255,255,.14); color:#fff; }

    /* Selector de mes */
    .lq-mes {
        background:#fff; border:1px solid #E5E7EB; border-radius:14px;
        padding:1rem 1.25rem; margin-bottom:1.25rem;
        display:flex; align-items:center; gap:1rem; flex-wrap:wrap;
    }
    .lq-mes .lbl { font-size:.85rem; font-weight:700; color:#0F172A; }
    .lq-mes form { display:flex; gap:.5rem; align-items:center; }
    .lq-mes input[type=month] { padding:.5rem .75rem; border:1px solid #E2E8F0; border-radius:8px; font-size:.88rem; }
    .lq-mes button { padding:.5rem 1rem; border:none; background:#0F172A; color:#fff; border-radius:8px; font-weight:600; font-size:.83rem; cursor:pointer; }
    .lq-mes .pago-info {
        margin-left:auto; font-size:.85rem; color:#065F46;
        background:#ECFDF5; border:1px solid #A7F3D0; border-radius:999px;
        padding:.4rem .9rem; font-weight:600;
    }

    /* Card por comercial */
    .lq-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 2px rgba(15,23,42,.03); }
    .lq-head {
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;
        padding:1rem 1.25rem; border-bottom:1px solid #F1F5F9; background:#FAFBFC;
    }
    .lq-ident { display:flex; align-items:center; gap:.75rem; }
    .lq-avatar { width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg,#2563EB,#1D4ED8); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.05rem; flex-shrink:0; }
    .lq-nombre { font-size:1.05rem; font-weight:700; color:#0F172A; margin:0; }
    .lq-sub { font-size:.78rem; color:#64748B; margin-top:.15rem; }

    /* Sueldo editable */
    .lq-sueldo-form { display:flex; gap:.4rem; align-items:center; }
    .lq-sueldo-form .lbl { font-size:.68rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; margin-right:.2rem; }
    .lq-sueldo-form input { width:130px; padding:.4rem .6rem; border:1px solid #E2E8F0; border-radius:8px; font-size:.85rem; font-weight:600; text-align:right; }
    .lq-sueldo-form select { padding:.4rem .4rem; border:1px solid #E2E8F0; border-radius:8px; font-size:.8rem; }
    .lq-sueldo-form button { padding:.4rem .7rem; border:none; background:#2563EB; color:#fff; border-radius:8px; font-size:.75rem; font-weight:700; cursor:pointer; }
    .lq-sueldo-form button:hover { background:#1D4ED8; }

    /* Tabla proyectos */
    .lq-table { width:100%; border-collapse:collapse; font-size:.86rem; font-variant-numeric:tabular-nums; }
    .lq-table th { font-size:.7rem; text-transform:uppercase; color:#94A3B8; font-weight:700; padding:.6rem 1.25rem; text-align:left; border-bottom:2px solid #F1F5F9; }
    .lq-table th.right, .lq-table td.right { text-align:right; }
    .lq-table td { padding:.65rem 1.25rem; border-bottom:1px solid #F8FAFC; color:#334155; }
    .lq-table td.name { font-weight:600; color:#0F172A; }
    .lq-table td.name small { display:block; color:#94A3B8; font-weight:500; font-size:.75rem; }
    .lq-pct { display:inline-block; padding:.15rem .5rem; border-radius:999px; background:#DBEAFE; color:#1D4ED8; font-size:.72rem; font-weight:700; }
    .lq-pct.monto { background:#EDE9FE; color:#6D28D9; }
    .lq-empty-row { color:#94A3B8; font-style:italic; padding:1rem 1.25rem; font-size:.85rem; }

    /* Resumen liquidación */
    .lq-resumen {
        display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:0;
        border-top:2px solid #F1F5F9;
    }
    .lq-res-item { padding:.9rem 1.25rem; border-right:1px solid #F1F5F9; }
    .lq-res-item:last-child { border-right:none; }
    .lq-res-lbl { font-size:.68rem; text-transform:uppercase; letter-spacing:.04em; color:#94A3B8; font-weight:700; }
    .lq-res-val { font-size:1.05rem; font-weight:800; color:#0F172A; margin-top:.15rem; font-variant-numeric:tabular-nums; }
    .lq-res-item.sueldo .lq-res-val { color:#0369A1; }
    .lq-res-item.comis .lq-res-val { color:#6D28D9; }
    .lq-res-item.total { background:#0F172A; }
    .lq-res-item.total .lq-res-lbl { color:rgba(255,255,255,.7); }
    .lq-res-item.total .lq-res-val { color:#93C5FD; font-size:1.2rem; }
    .lq-res-item.abonado .lq-res-val { color:#16A34A; }
    .lq-res-item.pendiente .lq-res-val { color:#C2410C; }

    /* Estado de pago de la liquidación */
    .lq-estado-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .7rem; border-radius:999px; font-size:.72rem; font-weight:800; letter-spacing:.03em; }
    .lq-estado-badge.pagado { background:#DCFCE7; color:#166534; }
    .lq-estado-badge.parcial { background:#FEF3C7; color:#B45309; }
    .lq-estado-badge.pendiente { background:#FEE2E2; color:#B91C1C; }

    /* Pagos registrados */
    .lq-pagos { border-top:1px solid #F1F5F9; background:#FAFBFC; padding:1rem 1.25rem; }
    .lq-pagos-title { font-size:.75rem; text-transform:uppercase; letter-spacing:.04em; color:#64748B; font-weight:700; margin-bottom:.6rem; display:flex; align-items:center; gap:.5rem; }
    .lq-pago-row { display:flex; align-items:center; gap:1rem; padding:.5rem .75rem; background:#fff; border:1px solid #E5E7EB; border-radius:10px; margin-bottom:.4rem; font-size:.83rem; flex-wrap:wrap; }
    .lq-pago-row .fecha { font-weight:700; color:#0F172A; }
    .lq-pago-row .monto { font-weight:800; color:#16A34A; font-variant-numeric:tabular-nums; }
    .lq-pago-row .metodo { color:#64748B; }
    .lq-pago-row .comprobante-link { display:inline-flex; align-items:center; gap:.3rem; color:#2563EB; text-decoration:none; font-weight:600; font-size:.78rem; }
    .lq-pago-row .comprobante-link:hover { text-decoration:underline; }
    .lq-pago-row form { margin-left:auto; }
    .lq-pago-row .del-btn { width:26px; height:26px; border:none; border-radius:7px; background:rgba(220,53,69,.08); color:#DC2626; cursor:pointer; font-size:.7rem; }
    .lq-pago-row .del-btn:hover { background:#DC2626; color:#fff; }

    /* Form registrar pago */
    .lq-pago-form { display:flex; gap:.5rem; align-items:flex-end; flex-wrap:wrap; margin-top:.6rem; padding:.85rem; background:#fff; border:1px dashed #CBD5E1; border-radius:10px; }
    .lq-pago-form .fld { display:flex; flex-direction:column; gap:.2rem; }
    .lq-pago-form .fld label { font-size:.65rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; }
    .lq-pago-form input, .lq-pago-form select { padding:.45rem .6rem; border:1px solid #E2E8F0; border-radius:8px; font-size:.82rem; }
    .lq-pago-form input[type=file] { padding:.3rem; font-size:.75rem; max-width:210px; }
    .lq-pago-form button { padding:.5rem 1rem; border:none; background:#16A34A; color:#fff; border-radius:8px; font-size:.8rem; font-weight:700; cursor:pointer; }
    .lq-pago-form button:hover { background:#15803D; }

    @media print { .lq-pagos .lq-pago-form { display:none !important; } }

    @media print {
        .lq-hero .lq-btn, .lq-mes, .lq-sueldo-form button, .mtadmin-sidebar, .mtadmin-topbar { display:none !important; }
        .lq-wrap { background:#fff; padding:0; }
        .lq-card { break-inside:avoid; }
    }
</style>

@php $fmtCop = fn ($v) => '$'.number_format((float) $v, 0, ',', '.'); @endphp

<div class="lq-wrap">
    <div class="lq-hero">
        <div>
            <h1><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Liquidación de comerciales</h1>
            <p>Sueldo básico + comisiones a mes vencido. Lo trabajado en {{ ucfirst($mesTrabajado->translatedFormat('F Y')) }} se paga en {{ ucfirst($mesPago->translatedFormat('F Y')) }}.</p>
        </div>
        <div style="display:flex; gap:.5rem;">
            <button type="button" class="lq-btn" onclick="window.print()"><i class="fas fa-print"></i> Imprimir</button>
            <a href="{{ route('admin.internal-projects.index') }}" class="lq-btn"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="lq-mes">
        <span class="lbl"><i class="fas fa-calendar-alt" style="color:#2563EB;"></i> Mes trabajado:</span>
        <form method="GET">
            <input type="month" name="mes" value="{{ $mesTrabajado->format('Y-m') }}">
            <button type="submit">Ver liquidación</button>
        </form>
        <span class="pago-info"><i class="fas fa-hand-holding-usd"></i> Se paga en {{ ucfirst($mesPago->translatedFormat('F Y')) }}</span>
    </div>

    @forelse($liquidaciones as $liq)
        @php $v = $liq['vendedor']; @endphp
        <div class="lq-card">
            <div class="lq-head">
                <div class="lq-ident">
                    <div class="lq-avatar">{{ strtoupper(mb_substr($v->nombre, 0, 1)) }}</div>
                    <div>
                        <h3 class="lq-nombre">
                            {{ $v->nombre }}
                            @if($liq['estado_pago'] === 'pagado')
                                <span class="lq-estado-badge pagado"><i class="fas fa-check-circle"></i> PAGADO</span>
                            @elseif($liq['estado_pago'] === 'parcial')
                                <span class="lq-estado-badge parcial"><i class="fas fa-hourglass-half"></i> PARCIAL</span>
                            @elseif($liq['estado_pago'] === 'pendiente')
                                <span class="lq-estado-badge pendiente"><i class="fas fa-clock"></i> PENDIENTE</span>
                            @endif
                        </h3>
                        <p class="lq-sub">
                            {{ $liq['proyectos']->count() }} {{ $liq['proyectos']->count() === 1 ? 'proyecto cerrado' : 'proyectos cerrados' }} en {{ ucfirst($mesTrabajado->translatedFormat('F')) }}
                            @if($v->email) · {{ $v->email }} @endif
                        </p>
                    </div>
                </div>
                <div style="display:flex; gap:.75rem; align-items:center; flex-wrap:wrap;">
                    <form method="POST" action="{{ route('admin.internal-projects.vendedores.sueldo', $v) }}" class="lq-sueldo-form">
                        @csrf @method('PUT')
                        <span class="lbl">Sueldo básico</span>
                        <input type="number" step="0.01" min="0" name="sueldo_basico" value="{{ $v->sueldo_basico }}" placeholder="0">
                        <select name="sueldo_moneda">
                            <option value="COP" {{ ($v->sueldo_moneda ?? 'COP') === 'COP' ? 'selected' : '' }}>COP</option>
                            <option value="USD" {{ ($v->sueldo_moneda ?? '') === 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ ($v->sueldo_moneda ?? '') === 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                        <button type="submit"><i class="fas fa-save"></i></button>
                    </form>
                    <a href="{{ route('admin.internal-projects.liquidacion.documento', ['vendedor' => $v, 'mes' => $mesTrabajado->format('Y-m')]) }}"
                       target="_blank"
                       style="display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .9rem; border-radius:8px; background:#2563EB; color:#fff; text-decoration:none; font-size:.78rem; font-weight:700;"
                       title="Documento de liquidación con membrete para imprimir/PDF">
                        <i class="fas fa-file-pdf"></i> Documento PDF
                    </a>
                </div>
            </div>

            @if($liq['proyectos']->isEmpty())
                <div class="lq-empty-row">Sin proyectos cerrados con comisión en {{ ucfirst($mesTrabajado->translatedFormat('F Y')) }}. Solo aplica sueldo básico.</div>
            @else
                <table class="lq-table">
                    <thead>
                        <tr>
                            <th>Proyecto</th>
                            <th>Cerrado</th>
                            <th>% / Tipo</th>
                            <th class="right">Valor proyecto</th>
                            <th class="right">Comisión</th>
                            <th class="right">Comisión COP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($liq['proyectos'] as $p)
                            @php $simb = $p['moneda'] === 'USD' ? 'US$' : ($p['moneda'] === 'EUR' ? '€' : '$'); @endphp
                            <tr>
                                <td class="name">
                                    <a href="{{ route('admin.internal-projects.show', $p['id']) }}" style="color:inherit; text-decoration:none;">{{ $p['nombre'] }}</a>
                                    <small>{{ $p['cliente'] ?: 'Sin cliente' }}</small>
                                </td>
                                <td>{{ $p['cierre'] }}</td>
                                <td>
                                    @if($p['comision_tipo'] === 'porcentaje')
                                        <span class="lq-pct">{{ rtrim(rtrim(number_format($p['comision_valor'], 2, ',', '.'), '0'), ',') }}%</span>
                                    @elseif($p['comision_tipo'] === 'monto')
                                        <span class="lq-pct monto">Monto fijo</span>
                                    @else
                                        <span style="color:#94A3B8;">—</span>
                                    @endif
                                </td>
                                <td class="right">{{ $simb }}{{ number_format($p['precio'], 0, ',', '.') }} <small style="color:#94A3B8;">{{ $p['moneda'] }}</small></td>
                                <td class="right" style="font-weight:700;">{{ $simb }}{{ number_format($p['comision'], 0, ',', '.') }}</td>
                                <td class="right" style="font-weight:700; color:#6D28D9;">{{ $fmtCop($p['comision_cop']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="lq-resumen">
                <div class="lq-res-item sueldo">
                    <div class="lq-res-lbl">Sueldo básico</div>
                    <div class="lq-res-val">{{ $fmtCop($liq['sueldo_cop']) }}</div>
                </div>
                <div class="lq-res-item comis">
                    <div class="lq-res-lbl">Comisiones {{ ucfirst($mesTrabajado->translatedFormat('F')) }}</div>
                    <div class="lq-res-val">{{ $fmtCop($liq['comisiones_cop']) }}</div>
                </div>
                <div class="lq-res-item abonado">
                    <div class="lq-res-lbl">Ya abonado (gestión)</div>
                    <div class="lq-res-val">{{ $fmtCop($liq['abonado_cop']) }}</div>
                </div>
                <div class="lq-res-item total">
                    <div class="lq-res-lbl">Total a pagar en {{ ucfirst($mesPago->translatedFormat('F')) }}</div>
                    <div class="lq-res-val">{{ $fmtCop($liq['total_cop']) }}</div>
                </div>
            </div>

            {{-- Pagos de esta liquidación --}}
            <div class="lq-pagos">
                <div class="lq-pagos-title">
                    <i class="fas fa-money-check-alt" style="color:#16A34A;"></i>
                    Pagos de esta liquidación
                    @if($liq['pagado_cop'] > 0)
                        · pagado {{ $fmtCop($liq['pagado_cop']) }}
                        @if($liq['saldo_liquidacion'] > 0)
                            · <span style="color:#C2410C;">falta {{ $fmtCop($liq['saldo_liquidacion']) }}</span>
                        @endif
                    @endif
                </div>

                @foreach($liq['pagos'] as $pago)
                    <div class="lq-pago-row">
                        <span class="fecha"><i class="far fa-calendar-check" style="color:#94A3B8;"></i> {{ $pago->fecha_pago->format('d/m/Y') }}</span>
                        <span class="monto">{{ $fmtCop($pago->monto) }}</span>
                        @if($pago->metodo)
                            <span class="metodo">{{ $pago->metodo }}</span>
                        @endif
                        @if($pago->referencia)
                            <span class="metodo">Ref: {{ $pago->referencia }}</span>
                        @endif
                        @if($pago->comprobante)
                            <a href="{{ asset('storage/'.$pago->comprobante) }}" target="_blank" class="comprobante-link">
                                <i class="fas fa-paperclip"></i> Ver comprobante
                            </a>
                        @endif
                        <form method="POST" action="{{ route('admin.internal-projects.liquidacion.pagos.destroy', $pago) }}" onsubmit="return confirm('¿Eliminar este pago de liquidación?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="del-btn" title="Eliminar pago"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                @endforeach

                @if($liq['estado_pago'] !== 'pagado' && $liq['total_cop'] > 0)
                    <form method="POST" action="{{ route('admin.internal-projects.liquidacion.pagos.store', $liq['vendedor']) }}" enctype="multipart/form-data" class="lq-pago-form">
                        @csrf
                        <input type="hidden" name="periodo" value="{{ $mesTrabajado->format('Y-m') }}">
                        <div class="fld">
                            <label>Fecha de pago</label>
                            <input type="date" name="fecha_pago" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="fld">
                            <label>Monto (COP)</label>
                            <input type="number" step="0.01" min="0.01" name="monto" value="{{ round($liq['saldo_liquidacion'] > 0 ? $liq['saldo_liquidacion'] : $liq['total_cop']) }}" required style="width:140px; text-align:right; font-weight:700;">
                        </div>
                        <div class="fld">
                            <label>Método</label>
                            <input type="text" name="metodo" placeholder="Nequi, Bancolombia..." style="width:150px;">
                        </div>
                        <div class="fld">
                            <label>Comprobante (PDF/imagen)</label>
                            <input type="file" name="comprobante" accept=".pdf,.jpg,.jpeg,.png,.webp">
                        </div>
                        <button type="submit"><i class="fas fa-check"></i> Registrar pago</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div style="text-align:center; padding:3rem; color:#94A3B8; background:#fff; border-radius:14px; border:1px solid #E5E7EB;">
            <i class="fas fa-users" style="font-size:2rem; opacity:.4;"></i>
            <p style="margin-top:.5rem;">No hay comerciales registrados.</p>
        </div>
    @endforelse
</div>
@endsection
