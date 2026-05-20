{{-- ════════════════ CAPÍTULO 02 — OPERATIONS CONSOLE ════════════════ --}}
{{-- Estilo dashboard de monitoring (Vercel/Linear/Datadog). Stats como
     tiles con sparklines SVG animados, mono labels, traffic lights. --}}

@php
    $numLineas = [
        [
            'key'   => 'platforms',
            'num'   => $data['stat_1_num']   ?? '40',
            'suf'   => $data['stat_1_suf']   ?? '+',
            'label' => $data['stat_1_label'] ?? 'PLATFORMS_IN_PROD',
            'desc'  => $data['stat_1_desc']  ?? 'Apps en producción en LATAM y EU',
            'delta' => $data['stat_1_delta'] ?? '+12 YoY',
            'spark' => '6,9,13,17,22,27,32,36,40',
            'health'=> 'healthy',
        ],
        [
            'key'   => 'countries',
            'num'   => $data['stat_2_num']   ?? '7',
            'suf'   => $data['stat_2_suf']   ?? '',
            'label' => $data['stat_2_label'] ?? 'COUNTRIES_SERVED',
            'desc'  => $data['stat_2_desc']  ?? 'CO · AR · CL · MX · GT · CR · ES',
            'delta' => $data['stat_2_delta'] ?? '+4 YoY',
            'spark' => '1,2,3,4,4,5,6,7,7',
            'health'=> 'healthy',
        ],
        [
            'key'   => 'templates',
            'num'   => $data['stat_3_num']   ?? '0',
            'suf'   => $data['stat_3_suf']   ?? '',
            'label' => $data['stat_3_label'] ?? 'TEMPLATE_DELIVERIES',
            'desc'  => $data['stat_3_desc']  ?? 'Cada línea, hecha a medida',
            'delta' => $data['stat_3_delta'] ?? '0% generic',
            'spark' => '0,0,0,0,0,0,0,0,0',
            'health'=> 'optimal',
        ],
        [
            'key'   => 'sla',
            'num'   => $data['stat_4_num']   ?? '24',
            'suf'   => $data['stat_4_suf']   ?? 'h',
            'label' => $data['stat_4_label'] ?? 'RESPONSE_SLA',
            'desc'  => $data['stat_4_desc']  ?? 'Promedio real: 6.2h',
            'delta' => $data['stat_4_delta'] ?? 'p99 < 24h',
            'spark' => '22,18,14,12,9,8,7,6,6',
            'health'=> 'healthy',
        ],
    ];
@endphp

<section class="mt-sn-numeros" data-sn-numeros>
    <div class="mt-container">
        <header class="mt-sn-cap-head">
            <span class="mt-sn-cap-mono">CAP. 02</span>
            <span class="mt-sn-cap-sep" aria-hidden="true">·</span>
            <span class="mt-sn-cap-name">{{ $data['cap2_label'] ?? 'Los números, en contexto' }}</span>
        </header>

        {{-- ═══════════════ CONSOLE WINDOW ═══════════════ --}}
        <div class="mt-sn-console" data-sn-console>

            {{-- Title bar (terminal lights) --}}
            <div class="mt-sn-console-bar">
                <div class="mt-sn-console-lights" aria-hidden="true">
                    <span></span><span></span><span></span>
                </div>
                <div class="mt-sn-console-tab">
                    <span class="mt-sn-console-tab-icon" aria-hidden="true">▸</span>
                    mytech / operations.status
                </div>
                <div class="mt-sn-console-meta">
                    <span class="mt-sn-console-pulse"></span>
                    <span>LIVE</span>
                </div>
            </div>

            {{-- Command prompt --}}
            <div class="mt-sn-console-prompt">
                <span class="mt-sn-prompt-user">mytech@studio</span>
                <span class="mt-sn-prompt-sep">:</span>
                <span class="mt-sn-prompt-path">~/operations</span>
                <span class="mt-sn-prompt-sigil">$</span>
                <span class="mt-sn-prompt-cmd" data-sn-typing>./status --since 2022 --format=tiles</span>
                <span class="mt-sn-prompt-caret" aria-hidden="true"></span>
            </div>

            {{-- Stat tiles grid --}}
            <div class="mt-sn-tiles">
                @foreach($numLineas as $i => $ln)
                    @php
                        $sparkValues = array_map('intval', explode(',', $ln['spark']));
                        $max = max($sparkValues) ?: 1;
                        $w = 80; $h = 28;
                        $step = count($sparkValues) > 1 ? $w / (count($sparkValues) - 1) : $w;
                        $points = [];
                        foreach ($sparkValues as $idx => $v) {
                            $x = round($idx * $step, 2);
                            $y = $max > 0 ? round($h - ($v / $max) * ($h - 4) - 2, 2) : $h - 2;
                            $points[] = "$x,$y";
                        }
                        $path = 'M ' . implode(' L ', $points);
                        // Fill area path
                        $areaPath = $path . " L $w,$h L 0,$h Z";
                    @endphp
                    <article class="mt-sn-tile" data-sn-tile data-sn-tile-idx="{{ $i }}">
                        <header class="mt-sn-tile-head">
                            <span class="mt-sn-tile-label">{{ $ln['label'] }}</span>
                            <span class="mt-sn-tile-health mt-sn-tile-health--{{ $ln['health'] }}" aria-hidden="true"></span>
                        </header>

                        <div class="mt-sn-tile-body">
                            <div class="mt-sn-tile-number">
                                <span class="mt-sn-tile-value" data-sn-counter
                                      data-from="0" data-to="{{ preg_replace('/[^0-9]/', '', $ln['num']) ?: 0 }}">{{ $ln['num'] }}</span><span class="mt-sn-tile-suf">{{ $ln['suf'] }}</span>
                            </div>
                            <svg class="mt-sn-tile-spark" viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="none" aria-hidden="true">
                                <defs>
                                    <linearGradient id="spark-grad-{{ $i }}" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%"   stop-color="rgba(96, 165, 250, 0.4)"/>
                                        <stop offset="100%" stop-color="rgba(96, 165, 250, 0)"/>
                                    </linearGradient>
                                </defs>
                                <path class="mt-sn-spark-fill"
                                      d="{{ $areaPath }}"
                                      fill="url(#spark-grad-{{ $i }})"
                                      data-sn-spark-fill></path>
                                <path class="mt-sn-spark-line"
                                      d="{{ $path }}"
                                      fill="none"
                                      stroke="#60A5FA"
                                      stroke-width="1.5"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      data-sn-spark-line></path>
                                @foreach($points as $idx => $pt)
                                    @php [$px, $py] = explode(',', $pt); @endphp
                                    <circle cx="{{ $px }}" cy="{{ $py }}" r="{{ $idx === count($points) - 1 ? 2.5 : 0 }}"
                                            fill="#60A5FA" opacity="{{ $idx === count($points) - 1 ? 1 : 0 }}"/>
                                @endforeach
                            </svg>
                        </div>

                        <footer class="mt-sn-tile-foot">
                            <span class="mt-sn-tile-desc">{{ $ln['desc'] }}</span>
                            <span class="mt-sn-tile-delta">▲ {{ $ln['delta'] }}</span>
                        </footer>
                    </article>
                @endforeach
            </div>

            {{-- Console footer --}}
            <div class="mt-sn-console-foot">
                <div class="mt-sn-console-foot-row">
                    <span class="mt-sn-foot-label">→ Process exited</span>
                    <span class="mt-sn-foot-value">code: 0 · {{ count($numLineas) }} metrics shown · uptime: 99.97%</span>
                </div>
                <div class="mt-sn-console-foot-row">
                    <span class="mt-sn-foot-label">→ Note</span>
                    <span class="mt-sn-foot-value mt-sn-foot-note">
                        {{ $data['numeros_foot'] ?? 'No son métricas de pitch deck. Son la base sobre la que prometemos lo que prometemos.' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
