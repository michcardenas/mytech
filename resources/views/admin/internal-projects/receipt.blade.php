@php
    $simbolo = fn ($moneda) => $moneda === 'USD' ? 'US$' : ($moneda === 'EUR' ? '€' : '$');
    $fmt = fn ($val) => number_format((float) $val, 0, ',', '.');

    /** Convierte un entero a letras en español (para hasta 999 millones). */
    $numeroEnLetras = function (int $num): string {
        if ($num === 0) {
            return 'CERO';
        }
        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decenas10 = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $decenas = ['', '', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        $convertirCentena = function (int $n) use (&$convertirCentena, $unidades, $decenas10, $decenas, $centenas): string {
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
            if ($d === 2 && $u > 0) {
                return trim($out.' VEINTI'.strtolower($unidades[$u]));
            }
            $tail = $decenas[$d];
            if ($u > 0) $tail .= ' Y '.$unidades[$u];
            return trim($out.' '.$tail);
        };

        $millones = intdiv($num, 1000000);
        $miles = intdiv($num % 1000000, 1000);
        $resto = $num % 1000;

        $partes = [];
        if ($millones > 0) {
            $partes[] = ($millones === 1 ? 'UN MILLON' : $convertirCentena($millones).' MILLONES');
        }
        if ($miles > 0) {
            $partes[] = ($miles === 1 ? 'MIL' : $convertirCentena($miles).' MIL');
        }
        if ($resto > 0) {
            $partes[] = $convertirCentena($resto);
        }

        return strtoupper(implode(' ', $partes));
    };

    $montoEntero = (int) round((float) $payment->monto);
    $letras = $numeroEnLetras($montoEntero);
    $prefijoMoneda = match ($project->moneda) { 'USD' => 'DÓLARES AMERICANOS', 'EUR' => 'EUROS', default => 'PESOS COLOMBIANOS' };
    $numeroRecibo = str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT);
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo #{{ $numeroRecibo }} · {{ $project->cliente_nombre }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { background: #F5F5F5; font-family: 'Segoe UI', Roboto, Arial, sans-serif; color: #1F2937; }

        .page {
            width: 21cm; min-height: 29.7cm;
            margin: 20px auto;
            background: #fff;
            padding: 2.2cm 2cm 2cm;
            position: relative;
            box-shadow: 0 8px 40px rgba(0,0,0,.08);
        }

        /* Barra de acciones flotante — sólo pantalla */
        .actions {
            position: fixed; top: 16px; right: 16px; z-index: 100;
            display: flex; gap: .5rem;
        }
        .actions button, .actions a {
            padding: .55rem 1rem; border-radius: 10px; border: none;
            font-weight: 600; font-size: .85rem; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
            transition: all .15s;
        }
        .actions .print { background: #2563EB; color: #fff; }
        .actions .print:hover { background: #1D4ED8; }
        .actions .back { background: #fff; color: #334155; border: 1px solid #E2E8F0; }
        .actions .back:hover { background: #F1F5F9; color: #0F172A; }

        /* MEMBRETE */
        .membrete {
            display: flex; align-items: center; gap: 1.2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #0F172A;
            margin-bottom: 1.6rem;
        }
        .membrete img { height: 74px; width: auto; flex-shrink: 0; }
        .membrete .brand h1 {
            font-size: 1.4rem; font-weight: 800; color: #0F172A;
            letter-spacing: -.02em; margin: 0;
        }
        .membrete .brand .tag {
            font-size: .82rem; color: #64748B; font-weight: 500;
        }
        .membrete .accent { flex: 1; }
        .membrete .doc {
            text-align: right;
        }
        .membrete .doc-title {
            font-size: .68rem; letter-spacing: .1em;
            text-transform: uppercase; color: #94A3B8; font-weight: 700;
        }
        .membrete .doc-num {
            font-size: 1.1rem; font-weight: 800; color: #2563EB;
            font-variant-numeric: tabular-nums; margin-top: .1rem;
        }
        .membrete .doc-date {
            font-size: .76rem; color: #64748B; margin-top: .15rem;
        }

        /* TITULO */
        .titulo {
            text-align: center; margin: 1.2rem 0 1.6rem;
        }
        .titulo h2 {
            font-size: 1.5rem; font-weight: 800;
            letter-spacing: .18em; color: #0F172A;
            padding: .55rem 1rem; display: inline-block;
            background: #F1F5F9; border-radius: 6px;
        }

        /* CUERPO */
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-bottom: 1.4rem; }
        .box {
            border: 1px solid #E5E7EB; border-radius: 8px;
            padding: .85rem 1rem;
        }
        .box .lbl {
            font-size: .68rem; text-transform: uppercase; letter-spacing: .06em;
            color: #94A3B8; font-weight: 700; margin-bottom: .35rem;
        }
        .box .val { font-size: .95rem; color: #0F172A; font-weight: 600; line-height: 1.35; }
        .box .val small { color: #64748B; font-weight: 500; font-size: .8rem; }

        /* MONTO destacado */
        .monto-hero {
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
            border: 1px solid #BFDBFE; border-radius: 12px;
            padding: 1.2rem 1.4rem;
            display: flex; justify-content: space-between; align-items: center; gap: 1rem;
            margin-bottom: 1.4rem;
        }
        .monto-hero .l { font-size: .72rem; text-transform: uppercase; letter-spacing: .1em; color: #1E40AF; font-weight: 700; }
        .monto-hero .v {
            font-size: 2.2rem; font-weight: 800; color: #1D4ED8;
            letter-spacing: -.02em; font-variant-numeric: tabular-nums; line-height: 1;
        }
        .monto-hero .v small { font-size: 1rem; font-weight: 700; margin-left: .3rem; color: #2563EB; }
        .monto-hero .side { text-align: right; }
        .monto-hero .side .m { font-size: .74rem; color: #1E3A8A; font-weight: 600; }
        .monto-hero .side .m span { font-weight: 800; }

        /* LETRAS */
        .letras {
            padding: .7rem 1rem;
            border: 1px dashed #CBD5E1;
            border-radius: 8px;
            font-size: .82rem; color: #334155; line-height: 1.5;
            margin-bottom: 1.4rem;
        }
        .letras strong { color: #0F172A; font-weight: 700; letter-spacing: .01em; }

        /* CONCEPTO */
        .concepto {
            margin-bottom: 1.4rem;
        }
        .concepto .lbl {
            font-size: .68rem; text-transform: uppercase; letter-spacing: .06em;
            color: #94A3B8; font-weight: 700; margin-bottom: .4rem;
        }
        .concepto .txt {
            font-size: .92rem; color: #0F172A;
            padding: .8rem 1rem; background: #FAFAFA; border-left: 3px solid #2563EB;
            border-radius: 4px; line-height: 1.5;
        }

        /* RESUMEN */
        .resumen {
            border: 1px solid #E5E7EB; border-radius: 8px; overflow: hidden;
            margin-bottom: 1.6rem;
        }
        .resumen .row {
            display: flex; justify-content: space-between; align-items: center;
            padding: .55rem .95rem; font-size: .84rem;
            border-bottom: 1px solid #F1F5F9;
        }
        .resumen .row:last-child { border-bottom: none; }
        .resumen .row .k { color: #64748B; font-weight: 500; }
        .resumen .row .v { font-weight: 700; color: #0F172A; font-variant-numeric: tabular-nums; }
        .resumen .row.total { background: #0F172A; color: #fff; }
        .resumen .row.total .k, .resumen .row.total .v { color: #fff; font-size: .95rem; font-weight: 800; }
        .resumen .row.saldo .v { color: #B45309; }
        .resumen .row.saldo.zero .v { color: #047857; }

        /* FIRMAS */
        .firmas { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 3rem; }
        .firma { text-align: center; }
        .firma-line { border-top: 1px solid #0F172A; padding-top: .35rem; }
        .firma-nombre { font-size: .84rem; font-weight: 700; color: #0F172A; }
        .firma-rol { font-size: .72rem; color: #64748B; margin-top: .15rem; }

        /* FOOTER */
        .footer {
            position: absolute; bottom: 1cm; left: 2cm; right: 2cm;
            border-top: 1px solid #E5E7EB;
            padding-top: .7rem;
            display: flex; justify-content: space-between; align-items: center;
            font-size: .72rem; color: #94A3B8;
        }
        .footer .left { display: flex; gap: 1rem; align-items: center; }
        .footer .left strong { color: #64748B; }

        @media print {
            html, body { background: #fff; }
            .actions { display: none !important; }
            .page { box-shadow: none; margin: 0; width: auto; min-height: 0; padding: 1.5cm 1.5cm 1cm; }
            @page { size: A4 portrait; margin: 0; }
        }
    </style>
</head>
<body>

<div class="actions">
    <a href="{{ route('admin.internal-projects.show', $project) }}" class="back">← Volver al proyecto</a>
    <button type="button" class="print" onclick="window.print()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
        Imprimir / Guardar PDF
    </button>
</div>

<div class="page">
    <div class="membrete">
        <img src="{{ asset('images/mytech-logo.jpg') }}" alt="MYTECH SOLUTIONS S.A.S">
        <div class="brand">
            <h1>MYTECH SOLUTIONS S.A.S</h1>
            <div class="tag">NIT 901.923.467-5 · Innovación y Tecnología para tu Empresa</div>
        </div>
        <div class="accent"></div>
        <div class="doc">
            <div class="doc-title">Recibo de caja</div>
            <div class="doc-num">N° {{ $numeroRecibo }}</div>
            <div class="doc-date">{{ $payment->fecha->translatedFormat('d \d\e F \d\e Y') }}</div>
        </div>
    </div>

    <div class="titulo">
        <h2>RECIBO DE CAJA</h2>
    </div>

    <div class="grid">
        <div class="box">
            <div class="lbl">Recibido de</div>
            <div class="val">
                {{ $project->cliente_nombre ?: '—' }}
                @if($project->cliente_contacto)
                    <br><small>Tel: {{ $project->cliente_contacto }}</small>
                @endif
                @if($project->cliente_email)
                    <br><small>{{ $project->cliente_email }}</small>
                @endif
            </div>
        </div>
        <div class="box">
            <div class="lbl">Proyecto</div>
            <div class="val">
                {{ $project->nombre }}
                <br><small>Estado: {{ $project->estado_label }}</small>
            </div>
        </div>
    </div>

    <div class="monto-hero">
        <div>
            <div class="l">La suma de</div>
            <div class="v">{{ $simbolo($project->moneda) }}{{ $fmt($payment->monto) }}<small>{{ $project->moneda }}</small></div>
        </div>
        <div class="side">
            <div class="m">Fecha del pago<br><span>{{ $payment->fecha->format('d/m/Y') }}</span></div>
            @if($payment->metodo)
                <div class="m" style="margin-top:.4rem;">Método<br><span>{{ $payment->metodo }}</span></div>
            @endif
        </div>
    </div>

    <div class="letras">
        <strong>Son:</strong> {{ $letras }} {{ $prefijoMoneda }}
        @if($payment->monto != $montoEntero)
            <em>({{ $simbolo($project->moneda) }}{{ number_format((float) $payment->monto, 2, ',', '.') }})</em>
        @endif
    </div>

    <div class="concepto">
        <div class="lbl">Por concepto de</div>
        <div class="txt">
            Abono al proyecto <strong>«{{ $project->nombre }}»</strong>{{ $payment->nota ? '. '.$payment->nota : '.' }}
            @if($payment->referencia)
                <br><small style="color:#64748B;">Referencia: {{ $payment->referencia }}</small>
            @endif
        </div>
    </div>

    <div class="resumen">
        <div class="row">
            <span class="k">Valor total del proyecto</span>
            <span class="v">{{ $simbolo($project->moneda) }}{{ $fmt($project->precio) }} {{ $project->moneda }}</span>
        </div>
        <div class="row">
            <span class="k">Total pagado a la fecha (incluye este recibo)</span>
            <span class="v">{{ $simbolo($project->moneda) }}{{ $fmt($totalPagado) }} {{ $project->moneda }}</span>
        </div>
        <div class="row saldo {{ $saldo <= 0 ? 'zero' : '' }}">
            <span class="k">Saldo pendiente</span>
            <span class="v">{{ $saldo <= 0 ? 'PAGADO EN SU TOTALIDAD' : $simbolo($project->moneda).$fmt($saldo).' '.$project->moneda }}</span>
        </div>
        <div class="row total">
            <span class="k">Este recibo</span>
            <span class="v">{{ $simbolo($project->moneda) }}{{ $fmt($payment->monto) }} {{ $project->moneda }}</span>
        </div>
    </div>

    <div class="firmas">
        <div class="firma">
            <div class="firma-line">
                <div class="firma-nombre">MYTECH SOLUTIONS S.A.S</div>
                <div class="firma-rol">NIT 901.923.467-5 · Emisor</div>
            </div>
        </div>
        <div class="firma">
            <div class="firma-line">
                <div class="firma-nombre">{{ $project->cliente_nombre ?: 'Cliente' }}</div>
                <div class="firma-rol">Recibí conforme</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="left">
            <strong>MYTECH SOLUTIONS S.A.S</strong>
            <span>· NIT 901.923.467-5</span>
            <span>· mytechsolutionsco.com</span>
        </div>
        <div>Generado el {{ now()->format('d/m/Y H:i') }}</div>
    </div>
</div>

</body>
</html>
