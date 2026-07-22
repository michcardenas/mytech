@php
    $simbolo = fn ($moneda) => $moneda === 'USD' ? 'US$' : ($moneda === 'EUR' ? '€' : '$');
    $fmt = fn ($val) => number_format((float) $val, 0, ',', '.');
    $fmtDec = fn ($val, $dec = 2) => number_format((float) $val, $dec, '.', ',');

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

    $prefijoMoneda = match ($project->moneda) { 'USD' => 'DÓLARES AMERICANOS', 'EUR' => 'EUROS', default => 'PESOS COLOMBIANOS' };
    $montoEntero = (int) round((float) $payment->monto);
    $letras = $numeroEnLetras($montoEntero);
    $numeroRecibo = 'RC-'.$payment->fecha->format('Ym').'-'.str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT);

    // Datos del cliente (empresa si tiene, si no nombre de persona)
    $client = $project->client;
    $nombreEmpresa = $client?->empresa ?: $project->cliente_nombre;
    $contactoPersonal = $client?->empresa ? ($client->nombre ?: $project->cliente_nombre) : null;
    $cargoContacto = $client?->cargo_contacto;
    $direccion = $client?->direccion;
    $ciudad = $client?->ciudad;
    $pais = $client?->pais;
    $webCliente = $client?->web;
    $emailCliente = $client?->email ?: $project->cliente_email;
    $telCliente = $client?->telefono ?: $project->cliente_contacto;
    $identCliente = $client?->identificacion;

    $backUrl = $backUrl ?? route('admin.internal-projects.show', $project);

    // Equivalente de moneda para USD/EUR
    $eurUsd = (float) config('services.eur_usd', env('EUR_USD_RATE', 1.17));
    $equivalente = null;
    if ($project->moneda === 'USD') {
        $equivalente = ['moneda' => 'EUR', 'simbolo' => '€', 'valor' => (float) $payment->monto / $eurUsd];
    } elseif ($project->moneda === 'EUR') {
        $equivalente = ['moneda' => 'USD', 'simbolo' => 'US$', 'valor' => (float) $payment->monto * $eurUsd];
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo {{ $numeroRecibo }} · {{ $nombreEmpresa }}</title>
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

        /* Header 2 columnas */
        .cols {
            display: grid; grid-template-columns: 1fr 1fr; gap: 30px;
            margin-bottom: 1.5rem;
        }
        .col-title {
            font-size: 10px; font-weight: 700; letter-spacing: 1.4px;
            color: #1D4ED8; margin-bottom: 8px;
        }
        .col-body { font-size: 12.5px; line-height: 1.65; color: #333; }
        .col-body .name { font-size: 13px; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; text-transform: uppercase; }
        .col-body .att { color: #555; margin-bottom: 3px; }
        .col-body strong { color: #1a1a1a; font-weight: 600; }
        .col-body .logo-line { display:flex; align-items:center; gap:10px; margin-bottom:8px; }
        .col-body .logo-line img { height: 42px; width: auto; }

        .barra-azul { height: 4px; background: #1D4ED8; margin-bottom: 1.5rem; }

        /* Meta info */
        .meta-row {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
            padding: 12px 0; margin-bottom: 2rem;
            border-bottom: 1px solid #E5E7EB;
        }
        .meta-item .lbl { font-size: 9.5px; letter-spacing: 1.4px; color: #666; font-weight: 700; margin-bottom: 4px; text-transform: uppercase; }
        .meta-item .val { font-size: 12.5px; font-weight: 700; color: #1a1a1a; }
        .meta-item .val.pagado { color: #15803D; }

        /* Título */
        .doc-title-wrap { text-align: center; margin-bottom: 1.2rem; }
        .doc-title-wrap h1 {
            font-size: 30px; font-weight: 800; color: #1a1a1a;
            letter-spacing: 1px; margin-bottom: 4px;
        }
        .doc-title-wrap .subtitle {
            font-size: 12px; color: #555; text-transform: uppercase;
            letter-spacing: 2px; font-weight: 600;
        }
        .doc-num-row {
            text-align: right; margin-bottom: 1.3rem;
            font-size: 12px; color: #555;
        }
        .doc-num-row strong { color: #1a1a1a; font-weight: 700; letter-spacing: .5px; }

        /* Section header azul */
        .sec-head {
            background: #1D4ED8; color: #fff;
            padding: 8px 14px; font-size: 11px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
        }

        /* Tabla concepto */
        table.concepto-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        .concepto-table thead th {
            background: #F5F5F5; color: #555;
            padding: 10px 14px; text-align: left;
            font-size: 10px; font-weight: 700; letter-spacing: 1px;
            border-bottom: 1px solid #E0E0E0;
        }
        .concepto-table thead th.right { text-align: right; }
        .concepto-table tbody td {
            padding: 15px 14px; vertical-align: top;
            border-bottom: 1px solid #F0F0F0;
        }
        .concepto-table .concepto-title { font-weight: 700; color: #1a1a1a; margin-bottom: 8px; font-size: 13px; }
        .concepto-table .concepto-desc { color: #555; font-size: 12px; }
        .concepto-table .fecha-cell { text-align: right; font-size: 12px; color: #555; white-space: nowrap; }
        .concepto-table .valor-cell {
            text-align: right; font-weight: 800; color: #15803D;
            font-size: 14px; white-space: nowrap; font-variant-numeric: tabular-nums;
        }
        .concepto-table .valor-cell small { display:block; font-size: 10px; color: #666; font-weight: 500; margin-top: 3px; }

        /* Totales / resumen del proyecto */
        .totales { margin-top: 5px; }
        .totales .row {
            display: grid; grid-template-columns: 1fr auto;
            padding: 10px 14px; align-items: center;
            border-bottom: 1px solid #F0F0F0;
            font-size: 12.5px;
        }
        .totales .row .k { color: #555; text-align: right; }
        .totales .row .v { font-weight: 700; color: #1a1a1a; text-align: right; min-width: 140px; font-variant-numeric: tabular-nums; }
        .totales .row .v.ok { color: #15803D; }
        .totales .row .v.warn { color: #B45309; }
        .totales .row.total {
            background: #1D4ED8; color: #fff;
            margin-top: 8px; border-radius: 4px; padding: 14px 14px;
        }
        .totales .row.total .k { color: rgba(255,255,255,.9); font-weight: 700; font-size: 13px; }
        .totales .row.total .v { color: #fff; font-size: 20px; font-weight: 800; letter-spacing: .5px; }
        .totales .row.total .v small { display: block; font-size: 10.5px; font-weight: 600; opacity: .9; margin-top: 3px; }

        .letras {
            font-size: 11.5px; color: #555; padding: 10px 14px; font-style: italic;
            border-top: 1px dashed #E0E0E0;
        }
        .letras strong { color: #1a1a1a; font-style: normal; font-weight: 700; }

        /* Detalle del pago */
        .pago-box {
            background: #F0FDF4; border: 1px solid #BBF7D0;
            padding: 15px 18px; border-radius: 4px;
            margin: 1.2rem 0 1.5rem;
        }
        .pago-box h4 { font-size: 12px; font-weight: 800; color: #15803D; margin-bottom: 10px; letter-spacing: .5px; }
        .pago-box p { font-size: 12px; color: #444; margin-bottom: 5px; line-height: 1.6; }
        .pago-box p strong { color: #1a1a1a; font-weight: 700; }

        /* Firmas */
        .firmas {
            display: grid; grid-template-columns: 1fr 1fr; gap: 40px;
            margin-top: 3rem; padding: 0 20px;
        }
        .firma { text-align: center; padding-top: 8px; border-top: 1.5px solid #1a1a1a; }
        .firma .nombre { font-size: 12px; font-weight: 700; color: #1a1a1a; margin-top: 4px; }
        .firma .rol { font-size: 11px; color: #555; margin-top: 2px; }
        .firma .extra { font-size: 10.5px; color: #666; margin-top: 2px; }

        .footer-doc {
            text-align: center; margin-top: 2.5rem; padding-top: 12px;
            border-top: 1px solid #E5E7EB;
            font-size: 10.5px; color: #666; line-height: 1.7;
        }
        .footer-doc strong { color: #1a1a1a; }

        /* Sello PAGADO */
        .sello {
            position: absolute;
            display: inline-block;
            transform: rotate(-8deg);
            border: 3px solid #15803D;
            color: #15803D;
            font-size: 20px; font-weight: 800; letter-spacing: 3px;
            padding: 6px 18px; border-radius: 8px;
            opacity: .85;
        }
        .sello-wrap { position: relative; height: 0; }
        .sello-wrap .sello { right: 40px; top: -10px; }

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
    <a href="{{ $backUrl }}" class="back">← Volver</a>
    <button type="button" class="print" onclick="window.print()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
        Imprimir / Guardar PDF
    </button>
</div>

<div class="page">
    {{-- ============= HEADER: PRESTADOR + CLIENTE ============= --}}
    <div class="cols">
        <div>
            <div class="col-title">PRESTADOR DE SERVICIOS</div>
            <div class="col-body">
                <div class="logo-line">
                    <img src="{{ asset('images/mytech-logo.jpg') }}" alt="MYTECH">
                </div>
                <div class="name">MYTECH SOLUTIONS S.A.S</div>
                MY Tech Solutions<br>
                Bogotá D.C., Colombia<br>
                Tel: +57 302 489 9201<br>
                Email: michcardenas01@hotmail.com<br>
                Web: mytechsolutionsco.com<br>
                <strong>NIT: 901.923.467-5</strong>
            </div>
        </div>
        <div>
            <div class="col-title">RECIBIDO DE</div>
            <div class="col-body">
                <div class="name">{{ $nombreEmpresa ?: '—' }}</div>
                @if($contactoPersonal)
                    <div class="att">Att: {{ $contactoPersonal }}{{ $cargoContacto ? ' ('.$cargoContacto.')' : '' }}</div>
                @endif
                @if($identCliente)
                    <div>NIT/ID: {{ $identCliente }}</div>
                @endif
                @if($direccion)
                    {{ $direccion }}<br>
                @endif
                @if($ciudad || $pais)
                    {{ trim(($ciudad ?? '').(($ciudad && $pais) ? ', ' : '').($pais ?? '')) }}<br>
                @endif
                @if($telCliente)
                    Tel: {{ $telCliente }}<br>
                @endif
                @if($emailCliente)
                    Email: {{ $emailCliente }}<br>
                @endif
                @if($webCliente)
                    Web: {{ $webCliente }}<br>
                @endif
                <strong>Proyecto: {{ $project->nombre }}</strong>
            </div>
        </div>
    </div>

    <div class="barra-azul"></div>

    {{-- ============= META INFO ============= --}}
    <div class="meta-row">
        <div class="meta-item">
            <div class="lbl">Fecha del pago</div>
            <div class="val">{{ $payment->fecha->format('d/m/Y') }}</div>
        </div>
        <div class="meta-item">
            <div class="lbl">Método de pago</div>
            <div class="val">{{ $payment->metodo ?: '—' }}</div>
        </div>
        <div class="meta-item">
            <div class="lbl">Referencia</div>
            <div class="val">{{ $payment->referencia ?: '—' }}</div>
        </div>
        <div class="meta-item">
            <div class="lbl">Estado</div>
            <div class="val pagado">✓ Pagado</div>
        </div>
    </div>

    <div class="sello-wrap"><span class="sello">PAGADO</span></div>

    {{-- ============= TÍTULO ============= --}}
    <div class="doc-title-wrap">
        <h1>RECIBO DE PAGO</h1>
        <div class="subtitle">{{ $project->nombre }}</div>
    </div>

    <div class="doc-num-row">
        <strong>DOCUMENTO NO.</strong> {{ $numeroRecibo }}
    </div>

    {{-- ============= DETALLE DEL PAGO ============= --}}
    <div class="sec-head">DETALLE DEL PAGO RECIBIDO</div>
    <table class="concepto-table">
        <thead>
            <tr>
                <th>CONCEPTO / DESCRIPCIÓN</th>
                <th class="right">FECHA</th>
                <th class="right">VALOR RECIBIDO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="concepto-title">Abono al proyecto «{{ $project->nombre }}»</div>
                    <div class="concepto-desc">
                        {{ $payment->nota ?: 'Pago recibido a satisfacción por los servicios profesionales prestados.' }}
                        @if($payment->referencia)
                            <br><small style="color:#888;">Referencia: {{ $payment->referencia }}</small>
                        @endif
                    </div>
                </td>
                <td class="fecha-cell">{{ $payment->fecha->format('d/m/Y') }}</td>
                <td class="valor-cell">
                    {{ $simbolo($project->moneda) }}{{ $fmt($payment->monto) }}
                    <small>{{ $project->moneda }}</small>
                    @if($equivalente)
                        <small style="color:#888;">≈ {{ $equivalente['simbolo'] }}{{ $fmt($equivalente['valor']) }} {{ $equivalente['moneda'] }}</small>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="totales">
        <div class="row">
            <span class="k">Valor total del proyecto</span>
            <span class="v">{{ $simbolo($project->moneda) }}{{ $fmt($project->precio) }} {{ $project->moneda }}</span>
        </div>
        <div class="row">
            <span class="k">Total pagado a la fecha (incluye este recibo)</span>
            <span class="v ok">{{ $simbolo($project->moneda) }}{{ $fmt($totalPagado) }} {{ $project->moneda }}</span>
        </div>
        <div class="row">
            <span class="k">Saldo pendiente</span>
            <span class="v {{ $saldo <= 0 ? 'ok' : 'warn' }}">
                {{ $saldo <= 0 ? 'PAGADO EN SU TOTALIDAD' : $simbolo($project->moneda).$fmt($saldo).' '.$project->moneda }}
            </span>
        </div>
        <div class="row total">
            <span class="k">VALOR DE ESTE RECIBO</span>
            <span class="v">
                {{ $simbolo($project->moneda) }}{{ $fmt($payment->monto) }} {{ $project->moneda }}
                @if($equivalente)
                    <small>(≈ {{ $equivalente['simbolo'] }}{{ $fmt($equivalente['valor']) }} {{ $equivalente['moneda'] }})</small>
                @endif
            </span>
        </div>
    </div>

    <div class="letras">
        <strong>Son:</strong> {{ $letras }} {{ $prefijoMoneda }}
    </div>

    {{-- ============= CONSTANCIA ============= --}}
    <div class="pago-box">
        <h4>✓ CONSTANCIA DE PAGO</h4>
        <p>
            <strong>MYTECH SOLUTIONS S.A.S</strong> (NIT 901.923.467-5) declara haber recibido de
            <strong>{{ $nombreEmpresa }}</strong> la suma de
            <strong>{{ $simbolo($project->moneda) }}{{ $fmt($payment->monto) }} {{ $project->moneda }}</strong>
            el día <strong>{{ $payment->fecha->translatedFormat('d \d\e F \d\e Y') }}</strong>
            @if($payment->metodo) vía <strong>{{ $payment->metodo }}</strong>@endif,
            como abono al proyecto «{{ $project->nombre }}».
        </p>
        <p style="margin-top:8px; font-style:italic; color:#555;">
            Este recibo constituye constancia del pago recibido. Consérvelo como soporte de su transacción.
        </p>
    </div>

    {{-- ============= FIRMAS ============= --}}
    <div class="firmas">
        <div class="firma">
            <div class="nombre">MYTECH SOLUTIONS S.A.S</div>
            <div class="rol">Emisor</div>
            <div class="extra">NIT: 901.923.467-5</div>
        </div>
        <div class="firma">
            <div class="nombre">{{ strtoupper($nombreEmpresa) }}</div>
            @if($contactoPersonal)
                <div class="rol">{{ $contactoPersonal }}{{ $cargoContacto ? ' — '.$cargoContacto : '' }}</div>
            @else
                <div class="rol">Recibí conforme</div>
            @endif
            @if($ciudad || $pais)
                <div class="extra">{{ trim(($ciudad ?? '').(($ciudad && $pais) ? ', ' : '').($pais ?? '')) }}</div>
            @endif
        </div>
    </div>

    <div class="footer-doc">
        Este documento ha sido generado electrónicamente y es válido sin firma autógrafa.<br>
        <strong>MYTECH SOLUTIONS S.A.S</strong> · NIT: 901.923.467-5 · Bogotá, Colombia<br>
        michcardenas01@hotmail.com · (+57) 302 489 9201 · mytechsolutionsco.com<br>
        Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

</body>
</html>
