@php
    $fmtCop = fn ($v) => '$'.number_format((float) $v, 0, ',', '.');

    $numeroEnLetras = function (int $num): string {
        if ($num === 0) return 'CERO';
        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decenas10 = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $decenas = ['', '', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];
        $convertirCentena = function (int $n) use ($unidades, $decenas10, $decenas, $centenas): string {
            if ($n === 0) return '';
            if ($n === 100) return 'CIEN';
            $c = intdiv($n, 100);
            $r = $n % 100;
            $out = $centenas[$c];
            if ($r === 0) return trim($out);
            if ($r < 10) return trim($out.' '.$unidades[$r]);
            if ($r < 20) return trim($out.' '.$decenas10[$r - 10]);
            $d = intdiv($r, 10);
            $u = $r % 10;
            if ($d === 2 && $u > 0) return trim($out.' VEINTI'.strtolower($unidades[$u]));
            $tail = $decenas[$d];
            if ($u > 0) $tail .= ' Y '.$unidades[$u];
            return trim($out.' '.$tail);
        };
        $millones = intdiv($num, 1000000);
        $miles = intdiv($num % 1000000, 1000);
        $resto = $num % 1000;
        $partes = [];
        if ($millones > 0) $partes[] = ($millones === 1 ? 'UN MILLON' : $convertirCentena($millones).' MILLONES');
        if ($miles > 0) $partes[] = ($miles === 1 ? 'MIL' : $convertirCentena($miles).' MIL');
        if ($resto > 0) $partes[] = $convertirCentena($resto);
        return strtoupper(implode(' ', $partes));
    };

    $numeroDoc = 'LIQ-'.$mesCorte->format('Ym').'-'.str_pad((string) $vendedor->id, 3, '0', STR_PAD_LEFT);

    $firmaPath = resource_path('images/firma-michael.png');
    $firmaB64 = is_file($firmaPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($firmaPath)) : null;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Liquidación {{ $numeroDoc }} · {{ $vendedor->nombre }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { background: #F5F5F5; font-family: 'Segoe UI', Roboto, Arial, sans-serif; color: #333; font-size: 13px; line-height: 1.5; }

        .page {
            width: 21cm; min-height: 29.7cm;
            margin: 20px auto;
            background: #fff;
            padding: 1.8cm 1.6cm 1.4cm;
            box-shadow: 0 8px 40px rgba(0,0,0,.08);
        }

        .actions {
            position: fixed; top: 16px; right: 16px; z-index: 100;
            display: flex; gap: .5rem;
        }
        .actions button, .actions a {
            padding: .55rem 1rem; border-radius: 10px; border: none;
            font-weight: 600; font-size: 13px; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
        }
        .actions .print { background: #2563EB; color: #fff; }
        .actions .print:hover { background: #1D4ED8; }
        .actions .back { background: #fff; color: #333; border: 1px solid #E2E8F0; }
        .actions .back:hover { background: #F1F5F9; }

        .cols { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 1.5rem; }
        .col-title { font-size: 10px; font-weight: 700; letter-spacing: 1.4px; color: #2563EB; margin-bottom: 8px; }
        .col-body { font-size: 12.5px; line-height: 1.65; color: #333; }
        .col-body .name { font-size: 13px; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; text-transform: uppercase; }
        .col-body strong { color: #1a1a1a; font-weight: 600; }
        .col-body .logo-line img { height: 42px; width: auto; margin-bottom: 8px; }

        .barra-verde { height: 4px; background: #0F172A; margin-bottom: 1.5rem; }

        .meta-row {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
            padding: 12px 0; margin-bottom: 2rem;
            border-bottom: 1px solid #E5E7EB;
        }
        .meta-item .lbl { font-size: 9.5px; letter-spacing: 1.4px; color: #666; font-weight: 700; margin-bottom: 4px; text-transform: uppercase; }
        .meta-item .val { font-size: 12.5px; font-weight: 700; color: #1a1a1a; }

        .doc-title-wrap { text-align: center; margin-bottom: 1.2rem; }
        .doc-title-wrap h1 { font-size: 27px; font-weight: 800; color: #1a1a1a; letter-spacing: 1px; margin-bottom: 4px; }
        .doc-title-wrap .subtitle { font-size: 12px; color: #555; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; }
        .doc-num-row { text-align: right; margin-bottom: 1.3rem; font-size: 12px; color: #555; }
        .doc-num-row strong { color: #1a1a1a; font-weight: 700; letter-spacing: .5px; }

        .sec-head {
            background: #0F172A; color: #fff;
            padding: 8px 14px; font-size: 11px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
        }

        table.det { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        .det thead th {
            background: #F5F5F5; color: #555;
            padding: 9px 14px; text-align: left;
            font-size: 10px; font-weight: 700; letter-spacing: 1px;
            border-bottom: 1px solid #E0E0E0;
        }
        .det thead th.right { text-align: right; }
        .det tbody td { padding: 11px 14px; border-bottom: 1px solid #F0F0F0; vertical-align: top; }
        .det tbody td.right { text-align: right; font-variant-numeric: tabular-nums; }
        .det .proy { font-weight: 700; color: #1a1a1a; }
        .det .proy small { display: block; color: #888; font-weight: 500; font-size: 11px; }
        .det .pct { display: inline-block; padding: 2px 8px; border-radius: 999px; background: #DBEAFE; color: #1D4ED8; font-size: 11px; font-weight: 700; }
        .det .pct.monto { background: #EDE9FE; color: #6D28D9; }
        .det .com { font-weight: 800; color: #1D4ED8; }
        .empty-det { padding: 14px; color: #888; font-style: italic; font-size: 12px; border-bottom: 1px solid #F0F0F0; }

        .totales { margin-top: 5px; }
        .totales .row {
            display: grid; grid-template-columns: 1fr auto;
            padding: 10px 14px; align-items: center;
            border-bottom: 1px solid #F0F0F0; font-size: 12.5px;
        }
        .totales .row .k { color: #555; text-align: right; }
        .totales .row .v { font-weight: 700; color: #1a1a1a; text-align: right; min-width: 150px; font-variant-numeric: tabular-nums; }
        .totales .row.total { background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); color: #fff; margin-top: 8px; border-radius: 4px; padding: 14px; }
        .totales .row.total .k { color: rgba(255,255,255,.9); font-weight: 700; font-size: 13px; }
        .totales .row.total .v { color: #fff; font-size: 20px; font-weight: 800; letter-spacing: .5px; }

        .letras {
            font-size: 11.5px; color: #555; padding: 10px 14px; font-style: italic;
            border-top: 1px dashed #E0E0E0; margin-bottom: 1.2rem;
        }
        .letras strong { color: #1a1a1a; font-style: normal; font-weight: 700; }

        .nota-box {
            background: #EFF6FF; border: 1px solid #BFDBFE;
            padding: 12px 16px; border-radius: 4px; margin-bottom: 1.5rem;
            font-size: 11.5px; color: #1E3A8A; line-height: 1.6;
        }
        .nota-box strong { font-weight: 700; }

        .firmas { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 2.6rem; padding: 0 20px; }
        .firma { text-align: center; }
        .firma .sig-space { height: 58px; display: flex; align-items: flex-end; justify-content: center; }
        .firma .sig-space img { height: 62px; width: auto; margin-bottom: -10px; }
        .firma .firma-line { padding-top: 8px; border-top: 1.5px solid #1a1a1a; }
        .firma .nombre { font-size: 12px; font-weight: 700; color: #1a1a1a; margin-top: 4px; }
        .firma .rol { font-size: 11px; color: #555; margin-top: 2px; }
        .firma .extra { font-size: 10.5px; color: #666; margin-top: 2px; }

        .footer-doc {
            text-align: center; margin-top: 2.2rem; padding-top: 12px;
            border-top: 1px solid #E5E7EB;
            font-size: 10.5px; color: #666; line-height: 1.7;
        }
        .footer-doc strong { color: #1a1a1a; }

        @media print {
            html, body { background: #fff; }
            .actions { display: none !important; }
            .page { box-shadow: none; margin: 0; width: auto; min-height: 0; padding: 1.3cm 1.3cm 1cm; }
            @page { size: A4 portrait; margin: 0; }
        }
    </style>
</head>
<body>

<div class="actions">
    <a href="{{ route('admin.internal-projects.liquidacion', ['mes' => $mesCorte->format('Y-m')]) }}" class="back">← Volver a liquidación</a>
    <button type="button" class="print" onclick="window.print()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
        Imprimir / Guardar PDF
    </button>
</div>

<div class="page">
    <div class="cols">
        <div>
            <div class="col-title">CONTRATANTE</div>
            <div class="col-body">
                <div class="logo-line"><img src="{{ asset('images/mytech-logo.jpg') }}" alt="MYTECH"></div>
                <div class="name">MYTECH SOLUTIONS S.A.S</div>
                MY Tech Solutions<br>
                Bogotá D.C., Colombia<br>
                Tel: +57 302 489 9201<br>
                Web: mytechsolutionsco.com<br>
                <strong>NIT: 901.923.467-5</strong>
            </div>
        </div>
        <div>
            <div class="col-title">CONTRATISTA / COMERCIAL</div>
            <div class="col-body">
                <div class="name">{{ $vendedor->nombre }}</div>
                @if($vendedor->email)
                    Email: {{ $vendedor->email }}<br>
                @endif
                @if($vendedor->telefono)
                    Tel: {{ $vendedor->telefono }}<br>
                @endif
                <strong>Cargo: Ejecutivo(a) comercial</strong>
            </div>
        </div>
    </div>

    <div class="barra-verde"></div>

    <div class="meta-row">
        <div class="meta-item">
            <div class="lbl">Ciclo liquidado</div>
            <div class="val">{{ $cicloInicio->format('d/m/Y') }} — {{ $cicloFin->format('d/m/Y') }}</div>
        </div>
        <div class="meta-item">
            <div class="lbl">Mes de pago</div>
            <div class="val">{{ ucfirst($mesCorte->translatedFormat('F Y')) }} (20–25)</div>
        </div>
        <div class="meta-item">
            <div class="lbl">Proyectos con comisión</div>
            <div class="val">{{ $proyectos->count() }}</div>
        </div>
        <div class="meta-item">
            <div class="lbl">Fecha de emisión</div>
            <div class="val">{{ now()->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="doc-title-wrap">
        <h1>LIQUIDACIÓN DE HONORARIOS Y COMISIONES</h1>
        <div class="subtitle">{{ $vendedor->nombre }} · Ciclo {{ $cicloInicio->format('d/m') }} — {{ $cicloFin->format('d/m/Y') }}</div>
    </div>

    <div class="doc-num-row"><strong>DOCUMENTO NO.</strong> {{ $numeroDoc }}</div>

    <div class="sec-head">DETALLE DE COMISIONES — PROYECTOS CERRADOS DEL {{ $cicloInicio->format('d/m/Y') }} AL {{ $cicloFin->format('d/m/Y') }}</div>
    <table class="det">
        <thead>
            <tr>
                <th>PROYECTO / CLIENTE</th>
                <th>CIERRE</th>
                <th>% / TIPO</th>
                <th class="right">VALOR PROYECTO</th>
                <th class="right">COMISIÓN</th>
                <th class="right">COMISIÓN COP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proyectos as $p)
                @php $simb = $p['moneda'] === 'USD' ? 'US$' : ($p['moneda'] === 'EUR' ? '€' : '$'); @endphp
                <tr>
                    <td class="proy">{{ $p['nombre'] }}<small>{{ $p['cliente'] ?: 'Sin cliente' }}</small></td>
                    <td>{{ $p['cierre'] }}</td>
                    <td>
                        @if($p['comision_tipo'] === 'porcentaje')
                            <span class="pct">{{ rtrim(rtrim(number_format($p['comision_valor'], 2, ',', '.'), '0'), ',') }}%</span>
                        @elseif($p['comision_tipo'] === 'monto')
                            <span class="pct monto">Monto fijo</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="right">{{ $simb }}{{ number_format($p['precio'], 0, ',', '.') }} {{ $p['moneda'] }}</td>
                    <td class="right com">{{ $simb }}{{ number_format($p['comision'], 0, ',', '.') }}</td>
                    <td class="right com">{{ $fmtCop($p['comision_cop']) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-det">Sin proyectos cerrados con comisión durante este periodo. La liquidación corresponde únicamente a los honorarios fijos.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="totales">
        <div class="row">
            <span class="k">Honorarios fijos mensuales</span>
            <span class="v">{{ $fmtCop($sueldoCop) }} COP</span>
        </div>
        <div class="row">
            <span class="k">Total comisiones del ciclo</span>
            <span class="v">{{ $fmtCop($comisionesCop) }} COP</span>
        </div>
        <div class="row total">
            <span class="k">TOTAL A PAGAR AL COMERCIAL</span>
            <span class="v">{{ $fmtCop($totalCop) }} COP</span>
        </div>
    </div>

    <div class="letras">
        <strong>Son:</strong> {{ $numeroEnLetras((int) round($totalCop)) }} PESOS COLOMBIANOS
    </div>

    <div class="nota-box">
        <strong>Condiciones de pago:</strong> conforme al contrato de prestación de servicios, el pago se efectúa entre el día 20 y el día 25 de {{ ucfirst($mesCorte->translatedFormat('F Y')) }}, previa presentación de la cuenta de cobro y la planilla de seguridad social (PILA) como trabajador independiente. Las comisiones corresponden a proyectos efectivamente cerrados en el ciclo del {{ $cicloInicio->format('d/m/Y') }} al {{ $cicloFin->format('d/m/Y') }} como resultado de la gestión comercial del contratista. Los pagos están sujetos a las retenciones de ley sobre honorarios.
    </div>

    <div class="firmas">
        <div class="firma">
            <div class="sig-space">
                @if($firmaB64)
                    <img src="{{ $firmaB64 }}" alt="Firma">
                @endif
            </div>
            <div class="firma-line">
                <div class="nombre">MYTECH SOLUTIONS S.A.S</div>
                <div class="rol">Michael Daniel Cárdenas Ríos — Representante Legal</div>
                <div class="extra">NIT: 901.923.467-5</div>
            </div>
        </div>
        <div class="firma">
            <div class="sig-space"></div>
            <div class="firma-line">
                <div class="nombre">{{ strtoupper($vendedor->nombre) }}</div>
                <div class="rol">Contratista — Recibí conforme</div>
            </div>
        </div>
    </div>

    <div class="footer-doc">
        Este documento ha sido generado electrónicamente y es válido sin firma autógrafa.<br>
        <strong>MYTECH SOLUTIONS S.A.S</strong> · NIT: 901.923.467-5 · Bogotá, Colombia · mytechsolutionsco.com<br>
        Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

</body>
</html>
