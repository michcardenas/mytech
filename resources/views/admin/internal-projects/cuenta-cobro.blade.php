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

    $numeroDoc = $esRecurrente
        ? 'CC-'.$periodo->format('Ym').'-'.str_pad((string) $project->id, 4, '0', STR_PAD_LEFT)
        : 'CC-'.now()->format('Ymd').'-'.str_pad((string) $project->id, 4, '0', STR_PAD_LEFT);

    $fechaEmision = now();
    $fechaVence = $esRecurrente ? $periodo->copy()->endOfMonth() : now()->addDays(15);

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

    // Tipo de cambio para mostrar equivalente en EUR/USD/COP
    $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
    $eurUsd = (float) config('services.eur_usd', env('EUR_USD_RATE', 1.17));
    $equivalente = null;
    if ($project->moneda === 'USD') {
        $equivalente = ['moneda' => 'EUR', 'simbolo' => '€', 'valor' => $monto / $eurUsd, 'tasa' => '1 USD = €'.$fmtDec(1 / $eurUsd, 3)];
    } elseif ($project->moneda === 'EUR') {
        $equivalente = ['moneda' => 'USD', 'simbolo' => 'US$', 'valor' => $monto * $eurUsd, 'tasa' => '1 EUR = US$'.$fmtDec($eurUsd, 3)];
    }

    // Firma del representante legal, incrustada en base64 (no se sirve por URL pública)
    $firmaPath = resource_path('images/firma-michael.png');
    $firmaB64 = is_file($firmaPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($firmaPath)) : null;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta de cobro {{ $numeroDoc }} · {{ $nombreEmpresa }}</title>
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

        /* Header 2 columnas: PRESTADOR / CLIENTE */
        .cols {
            display: grid; grid-template-columns: 1fr 1fr; gap: 30px;
            margin-bottom: 1.5rem;
        }
        .col-title {
            font-size: 10px; font-weight: 700; letter-spacing: 1.4px;
            color: #2563EB; margin-bottom: 8px;
        }
        .col-body { font-size: 12.5px; line-height: 1.65; color: #333; }
        .col-body .name { font-size: 13px; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; text-transform: uppercase; }
        .col-body .att { color: #555; margin-bottom: 3px; }
        .col-body strong { color: #1a1a1a; font-weight: 600; }
        .col-body .logo-line { display:flex; align-items:center; gap:10px; margin-bottom:8px; }
        .col-body .logo-line img { height: 42px; width: auto; }

        /* Barra verde divisora */
        .barra-verde { height: 4px; background: #0F172A; margin-bottom: 1.5rem; }

        /* Meta info (fechas + estado) */
        .meta-row {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
            padding: 12px 0; margin-bottom: 2rem;
            border-bottom: 1px solid #E5E7EB;
        }
        .meta-item .lbl { font-size: 9.5px; letter-spacing: 1.4px; color: #666; font-weight: 700; margin-bottom: 4px; text-transform: uppercase; }
        .meta-item .val { font-size: 12.5px; font-weight: 700; color: #1a1a1a; }

        /* Título grande */
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

        /* Section header verde */
        .sec-head {
            background: #0F172A; color: #fff;
            padding: 8px 14px; font-size: 11px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            margin-bottom: 0;
        }

        /* Tabla concepto */
        table.concepto-table {
            width: 100%; border-collapse: collapse;
            font-size: 12.5px;
        }
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
        .concepto-table .concepto-desc { color: #555; margin-bottom: 8px; }
        .concepto-table ul { list-style: none; margin-top: 4px; }
        .concepto-table ul li { padding-left: 15px; position: relative; margin-bottom: 3px; color: #555; font-size: 12px; }
        .concepto-table ul li::before {
            content: '•'; position: absolute; left: 0; color: #2563EB; font-weight: bold;
        }
        .concepto-table .periodo-cell { text-align: right; font-size: 12px; color: #555; white-space: nowrap; }
        .concepto-table .valor-cell {
            text-align: right; font-weight: 800; color: #1a1a1a;
            font-size: 14px; white-space: nowrap; font-variant-numeric: tabular-nums;
        }
        .concepto-table .valor-cell small { display:block; font-size: 10px; color: #666; font-weight: 500; margin-top: 3px; }
        .concepto-table .valor-cell .simbolo { font-size: 11px; color: #666; font-weight: 700; }

        /* Aviso tipo de cambio */
        .aviso-tc {
            background: #EFF6FF; border: 1px solid #BFDBFE;
            padding: 10px 14px; margin: 15px 0;
            font-size: 11.5px; color: #1E3A8A; line-height: 1.5;
            border-radius: 4px;
        }
        .aviso-tc strong { font-weight: 700; }

        /* Totales */
        .totales { margin-top: 5px; }
        .totales .row {
            display: grid; grid-template-columns: 1fr auto;
            padding: 10px 14px; align-items: center;
            border-bottom: 1px solid #F0F0F0;
            font-size: 12.5px;
        }
        .totales .row .k { color: #555; text-align: right; }
        .totales .row .v { font-weight: 700; color: #1a1a1a; text-align: right; min-width: 130px; font-variant-numeric: tabular-nums; }
        .totales .row.total {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); color: #fff;
            margin-top: 8px; border-radius: 4px; padding: 14px 14px;
        }
        .totales .row.total .k { color: rgba(255,255,255,.9); font-weight: 700; font-size: 13px; }
        .totales .row.total .v {
            color: #fff; font-size: 20px; font-weight: 800;
            letter-spacing: .5px;
        }
        .totales .row.total .v small { display: block; font-size: 10.5px; font-weight: 600; opacity: .9; margin-top: 3px; }

        /* Letras */
        .letras {
            font-size: 11.5px; color: #555; padding: 10px 14px; font-style: italic;
            border-top: 1px dashed #E0E0E0;
        }
        .letras strong { color: #1a1a1a; font-style: normal; font-weight: 700; }

        /* Pago */
        .pago-box {
            background: #F0F7FF; border: 1px solid #DBEAFE;
            padding: 15px 18px; border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .pago-box h4 {
            font-size: 12px; font-weight: 800; color: #1D4ED8;
            margin-bottom: 10px; letter-spacing: .5px;
        }
        .pago-box p { font-size: 12px; color: #444; margin-bottom: 5px; line-height: 1.6; }
        .pago-box p strong { color: #1a1a1a; font-weight: 700; }
        .pago-box .highlight {
            display: inline-block; background: #DBEAFE;
            padding: 4px 10px; border-radius: 4px;
            font-weight: 700; color: #1E40AF; font-size: 12px;
            margin: 4px 0;
        }
        .pago-box .conf { margin-top: 10px; padding-top: 10px; border-top: 1px dashed #BFDBFE; font-style: italic; color: #555; font-size: 11.5px; }

        /* Términos */
        .terminos ol {
            padding-left: 20px; font-size: 11px; color: #555; line-height: 1.6;
        }
        .terminos ol li { margin-bottom: 6px; }
        .terminos ol li strong { color: #1a1a1a; }

        /* Firmas */
        .firmas {
            display: grid; grid-template-columns: 1fr 1fr; gap: 40px;
            margin-top: 3rem; padding: 0 20px;
        }
        .firma { text-align: center; }
        .firma .sig-space { height: 58px; display: flex; align-items: flex-end; justify-content: center; }
        .firma .sig-space img { height: 62px; width: auto; margin-bottom: -10px; }
        .firma .firma-line { padding-top: 8px; border-top: 1.5px solid #1a1a1a; }
        .firma .nombre { font-size: 12px; font-weight: 700; color: #1a1a1a; margin-top: 4px; }
        .firma .rol { font-size: 11px; color: #555; margin-top: 2px; }
        .firma .extra { font-size: 10.5px; color: #666; margin-top: 2px; }

        /* Footer */
        .footer-doc {
            text-align: center; margin-top: 2.5rem; padding-top: 12px;
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
    <a href="{{ $backUrl ?? route('admin.internal-projects.show', $project) }}" class="back">← Volver</a>
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
            <div class="col-title">CLIENTE</div>
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
                <strong>Servicio: {{ $project->nombre }}</strong>
            </div>
        </div>
    </div>

    <div class="barra-verde"></div>

    {{-- ============= META INFO ============= --}}
    <div class="meta-row">
        <div class="meta-item">
            <div class="lbl">Fecha de emisión</div>
            <div class="val">{{ $fechaEmision->format('d/m/Y') }}</div>
        </div>
        <div class="meta-item">
            <div class="lbl">Fecha vencimiento</div>
            <div class="val">{{ $fechaVence->format('d/m/Y') }}</div>
        </div>
        <div class="meta-item">
            <div class="lbl">Periodo facturado</div>
            <div class="val">
                @if($esRecurrente)
                    {{ ucfirst($periodo->translatedFormat('F Y')) }}
                @else
                    {{ $fechaEmision->format('d/m/Y') }}
                @endif
            </div>
        </div>
        <div class="meta-item">
            <div class="lbl">Estado</div>
            <div class="val" style="color:#C2410C;">Pendiente</div>
        </div>
    </div>

    {{-- ============= TÍTULO ============= --}}
    <div class="doc-title-wrap">
        <h1>{{ $esRecurrente ? 'CUENTA DE COBRO MENSUAL' : 'CUENTA DE COBRO' }}</h1>
        <div class="subtitle">{{ $project->nombre }}</div>
    </div>

    <div class="doc-num-row">
        <strong>DOCUMENTO NO.</strong> {{ $numeroDoc }}
    </div>

    {{-- ============= DESCRIPCIÓN DEL SERVICIO ============= --}}
    <div class="sec-head">DESCRIPCIÓN DEL SERVICIO {{ $esRecurrente ? 'MENSUAL' : '' }}</div>
    <table class="concepto-table">
        <thead>
            <tr>
                <th>CONCEPTO / DESCRIPCIÓN</th>
                <th class="right">PERIODO</th>
                <th class="right">VALOR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="concepto-title">
                        @if($esRecurrente)
                            Gestión Mensual — {{ $project->nombre }}
                        @else
                            {{ $project->nombre }}
                        @endif
                    </div>
                    <div class="concepto-desc">
                        @if($project->descripcion)
                            {{ \Illuminate\Support\Str::limit($project->descripcion, 400) }}
                        @else
                            Servicio profesional de {{ $esRecurrente ? 'gestión, optimización y monitoreo continuo' : 'desarrollo y entrega' }} correspondiente al proyecto <strong>«{{ $project->nombre }}»</strong>{{ $esRecurrente ? ' para '.$nombreEmpresa : '' }}.
                        @endif
                    </div>
                </td>
                <td class="periodo-cell">
                    @if($esRecurrente)
                        1 mes<br>
                        <small style="color:#888; font-size:10.5px;">({{ $periodo->format('d/m') }} — {{ $periodo->copy()->endOfMonth()->format('d/m') }})</small>
                    @else
                        Único
                    @endif
                </td>
                <td class="valor-cell">
                    {{ $simbolo($project->moneda) }}{{ $fmt($monto) }}
                    <small><span class="simbolo">{{ $project->moneda }}</span></small>
                    @if($equivalente)
                        <small style="color:#888;">≈ {{ $equivalente['simbolo'] }}{{ $fmt($equivalente['valor']) }} {{ $equivalente['moneda'] }}</small>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    @if($equivalente)
        <div class="aviso-tc">
            📌 <strong>Tipo de cambio aplicado:</strong> {{ $equivalente['tasa'] }} (tasa de referencia de mercado al {{ $fechaEmision->format('d/m/Y') }}). El monto se factura en {{ $project->moneda }} y puede pagarse en su equivalente en {{ $equivalente['moneda'] }}. El tipo de cambio puede variar según la tasa vigente al momento del pago.
        </div>
    @endif

    <div class="totales">
        <div class="row">
            <span class="k">Subtotal</span>
            <span class="v">{{ $simbolo($project->moneda) }}{{ $fmt($monto) }} {{ $project->moneda }}</span>
        </div>
        @if($equivalente)
            <div class="row">
                <span class="k">Equivalente en {{ $equivalente['moneda'] }}</span>
                <span class="v">{{ $equivalente['simbolo'] }}{{ $fmt($equivalente['valor']) }} {{ $equivalente['moneda'] }}</span>
            </div>
        @endif
        <div class="row">
            <span class="k">IVA / Impuestos</span>
            <span class="v">No aplica</span>
        </div>
        <div class="row total">
            <span class="k">TOTAL A PAGAR</span>
            <span class="v">
                {{ $simbolo($project->moneda) }}{{ $fmt($monto) }} {{ $project->moneda }}
                @if($equivalente)
                    <small>({{ $equivalente['simbolo'] }}{{ $fmt($equivalente['valor']) }} {{ $equivalente['moneda'] }})</small>
                @endif
            </span>
        </div>
    </div>

    <div class="letras">
        <strong>Son:</strong> {{ $numeroEnLetras((int) round($monto)) }} {{ $prefijoMoneda }}
    </div>

    <div style="height: 20px;"></div>

    {{-- ============= INFO DE PAGO ============= --}}
    <div class="sec-head">INFORMACIÓN DE PAGO</div>
    <div style="height: 12px;"></div>
    <div class="pago-box">
        <h4>💳 MEDIOS DE PAGO DISPONIBLES</h4>
        <p><strong>Beneficiario:</strong> MYTECH SOLUTIONS S.A.S · NIT 901.923.467-5</p>
        <p><strong>PayPal:</strong> <span class="highlight">michcardenas001@gmail.com</span> (para clientes internacionales)</p>
        <p><strong>Davivienda</strong> (cuenta empresarial MYTECH SOLUTIONS S.A.S): <span class="highlight">1089 0082 4930</span></p>
        <p><strong>Bancolombia</strong> (Michael Cárdenas): <span class="highlight">912 06569 609</span></p>
        <p><strong>Llave Bre-B:</strong> <span class="highlight">1032455582</span> — transferencia inmediata desde cualquier banco.</p>
        <p><strong>Concepto del pago:</strong> {{ $numeroDoc }} — {{ $project->nombre }}{{ $esRecurrente ? ' — '.ucfirst($periodo->translatedFormat('F Y')) : '' }}</p>
        <p><strong>Monto:</strong> {{ $simbolo($project->moneda) }}{{ $fmt($monto) }} {{ $project->moneda }} @if($equivalente)<em>(o equivalente en {{ $equivalente['moneda'] }} según tasa del día)</em>@endif</p>
        <div class="conf">
            <strong>Confirmación:</strong> Al recibir el pago le emitiremos el recibo de caja. Por favor reenviar el comprobante por WhatsApp para confirmar recepción{{ $esRecurrente ? ' y continuar la gestión del siguiente periodo' : '' }}.
        </div>
    </div>

    {{-- ============= TÉRMINOS ============= --}}
    <div class="sec-head">TÉRMINOS Y CONDICIONES</div>
    <div style="padding: 12px 4px 0;" class="terminos">
        <ol>
            @if($esRecurrente)
                <li>El presente documento constituye una cuenta de cobro mensual por los servicios profesionales prestados en el proyecto <strong>«{{ $project->nombre }}»</strong>.</li>
                <li>El pago se realiza de forma <strong>recurrente cada mes</strong>, correspondiente al servicio prestado durante el ciclo indicado ({{ $periodo->format('d/m') }} — {{ $periodo->copy()->endOfMonth()->format('d/m') }}).</li>
                <li>El monto facturado es de <strong>{{ $simbolo($project->moneda) }}{{ $fmt($monto) }} {{ $project->moneda }}</strong> mensuales{{ $equivalente ? ', pagaderos en su equivalente según el tipo de cambio del mercado vigente al momento del pago' : '' }}.</li>
                <li>Los presupuestos publicitarios o costos de plataformas (Google Ads, Meta Ads, hosting, dominios, etc.) <strong>NO están incluidos</strong> en esta cuenta de cobro y son gestionados y pagados directamente por el cliente desde sus propias cuentas.</li>
                <li>El servicio se renueva automáticamente cada mes salvo notificación de cancelación con al menos <strong>7 días de anticipación</strong>.</li>
                <li>El no pago dentro de los <strong>5 días posteriores</strong> al vencimiento podrá implicar la suspensión temporal del servicio.</li>
            @else
                <li>El presente documento constituye una cuenta de cobro por el saldo pendiente del proyecto <strong>«{{ $project->nombre }}»</strong>.</li>
                <li>El pago debe realizarse antes de la fecha de vencimiento indicada ({{ $fechaVence->format('d/m/Y') }}).</li>
                <li>El monto a pagar es de <strong>{{ $simbolo($project->moneda) }}{{ $fmt($monto) }} {{ $project->moneda }}</strong>{{ $equivalente ? ', pagaderos en su equivalente según el tipo de cambio del mercado vigente al momento del pago' : '' }}.</li>
                <li>Al recibir el pago se emitirá el recibo de caja con el correspondiente número de comprobante.</li>
            @endif
            <li>Las comisiones de PayPal u otras pasarelas (si aplican) corren por cuenta del prestador del servicio.</li>
            <li>Este documento no genera IVA. MYTECH SOLUTIONS S.A.S es responsable del régimen ordinario de renta.</li>
        </ol>
    </div>

    {{-- ============= FIRMAS ============= --}}
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
                <div class="nombre">{{ strtoupper($nombreEmpresa) }}</div>
                @if($contactoPersonal)
                    <div class="rol">{{ $contactoPersonal }}{{ $cargoContacto ? ' — '.$cargoContacto : '' }}</div>
                @endif
                @if($ciudad || $pais)
                    <div class="extra">{{ trim(($ciudad ?? '').(($ciudad && $pais) ? ', ' : '').($pais ?? '')) }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="footer-doc">
        Este documento ha sido generado electrónicamente y es válido sin firma autógrafa.<br>
        <strong>MYTECH SOLUTIONS S.A.S</strong> · NIT: 901.923.467-5 · Bogotá, Colombia<br>
        michcardenas01@hotmail.com · (+57) 302 489 9201 · mytechsolutionsco.com
    </div>
</div>

</body>
</html>
