@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-blue: #007BFF;
        --dark-text: #2c3e50;
        --shadow-soft: 0 10px 30px rgba(0, 123, 255, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .edit-container { background: #fff; max-width: 1400px; margin: 0 auto; padding: 2rem; }
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 2rem; padding: 1.5rem;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        border-radius: 15px; border: 2px solid rgba(0, 123, 255, 0.1);
    }
    .page-title {
        color: var(--dark-text); font-size: 1.85rem; font-weight: 800; margin: 0;
        display: flex; align-items: center; gap: 1rem;
    }
    .btn-secondary {
        background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 50px;
        font-weight: 600; text-decoration: none; transition: var(--transition);
        box-shadow: var(--shadow-soft); display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-secondary:hover { background: #5a6268; transform: translateY(-2px); color: white; }
    .form-container {
        background: #fff; border: 2px solid rgba(0, 123, 255, 0.1); border-radius: 15px;
        padding: 2rem; box-shadow: var(--shadow-soft);
    }
    .form-section {
        margin-bottom: 1.5rem; padding: 1.5rem; background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04); border-left: 4px solid var(--primary-blue);
    }
    .section-title {
        color: var(--dark-text); font-size: 1.2rem; font-weight: 700;
        margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.75rem;
    }
    .section-title .chapter-num {
        background: var(--primary-blue); color: white; padding: 0.25rem 0.6rem;
        border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem;
    }
    .section-hint {
        font-size: 0.85rem; color: #6c757d; margin: -0.5rem 0 1rem 0; font-style: italic;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; }
    @media (max-width: 768px) {
        .form-row, .form-row-3 { grid-template-columns: 1fr; }
    }
    .form-group { margin-bottom: 1rem; }
    .form-label {
        color: var(--dark-text); font-weight: 600; margin-bottom: 0.4rem;
        display: block; font-size: 0.9rem;
    }
    .form-control {
        width: 100%; padding: 0.7rem 1rem; border: 2px solid #e9ecef;
        border-radius: 10px; font-size: 0.95rem; transition: var(--transition); background: #fff;
    }
    .form-control:focus {
        border-color: var(--primary-blue); box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15); outline: none;
    }
    textarea.form-control { min-height: 90px; resize: vertical; line-height: 1.5; }
    .form-text { font-size: 0.8rem; color: #6c757d; margin-top: 0.25rem; }
    .text-danger { color: #dc3545; }

    .credo-block, .team-block, .credit-block, .stat-block {
        padding: 1.25rem; background: #f8f9fa; border-radius: 10px;
        border-left: 3px solid var(--primary-blue); margin-bottom: 1rem;
    }
    .credo-block h4, .team-block h4, .credit-block h4, .stat-block h4 {
        color: var(--primary-blue); font-weight: 700; font-size: 0.95rem;
        text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.75rem;
    }

    .submit-row {
        display: flex; justify-content: flex-end; gap: 0.75rem; padding: 1.5rem 0 0;
        border-top: 2px solid rgba(0,0,0,0.05); margin-top: 1.5rem; position: sticky;
        bottom: 0; background: linear-gradient(to top, #fff 60%, transparent);
    }
    .btn-primary {
        background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
        color: white; padding: 0.85rem 2rem; border: none; border-radius: 50px;
        font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: var(--shadow-soft);
        transition: var(--transition); display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 15px 35px rgba(0, 123, 255, 0.3); }
    .alert {
        padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem;
        font-weight: 500; display: flex; align-items: center; gap: 0.5rem;
    }
    .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
    .alert-danger { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
    .seo-section { border-left-color: #28a745; }
    .seo-section .section-title .chapter-num { background: #28a745; }
    .accent-words-help {
        background: rgba(0, 123, 255, 0.05); border: 1px dashed rgba(0, 123, 255, 0.3);
        padding: 0.75rem 1rem; border-radius: 10px; margin-top: 0.5rem;
        font-size: 0.85rem; color: #495057;
    }
    .accent-words-help code {
        background: rgba(0, 123, 255, 0.1); padding: 0.1rem 0.4rem; border-radius: 4px;
        font-size: 0.85rem; color: var(--primary-blue);
    }
</style>

@php
    $c = json_decode($page->content ?? '{}', true) ?: [];
@endphp

<div class="edit-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-book-open"></i>
            Sobre Nosotros — Manifiesto cinemático
        </h1>
        <a href="{{ route('admin.pages.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Hay errores:</strong>
                <ul style="margin: 0.25rem 0 0 1rem;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-container">

            {{-- ════════ CAP 00 — PRÓLOGO ════════ --}}
            <div class="form-section">
                <h3 class="section-title">
                    <span class="chapter-num">CAP. 00</span> Prólogo
                </h3>
                <p class="section-hint">La primera pantalla. Una declaración corta y contundente.</p>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Label del capítulo</label>
                        <input type="text" class="form-control" name="cap0_label"
                               value="{{ old('cap0_label', $c['cap0_label'] ?? 'Prólogo') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Año de fundación</label>
                        <input type="text" class="form-control" name="founding_year"
                               value="{{ old('founding_year', $c['founding_year'] ?? '2022') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Título grande del prólogo *</label>
                    <input type="text" class="form-control" name="prologo_title"
                           value="{{ old('prologo_title', $c['prologo_title'] ?? 'No somos una agencia.') }}"
                           placeholder="Ej. No somos una agencia.">
                    <small class="form-text">El título cinematográfico que abre el manifiesto. Idealmente 2-5 palabras.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Subtítulo</label>
                    <textarea class="form-control" name="prologo_sub"
                              placeholder="La promesa o intención editorial">{{ old('prologo_sub', $c['prologo_sub'] ?? '') }}</textarea>
                    <small class="form-text">Acepta HTML básico como &lt;strong&gt; para resaltar.</small>
                </div>
            </div>

            {{-- ════════ CAP 01 — TESIS ════════ --}}
            <div class="form-section">
                <h3 class="section-title">
                    <span class="chapter-num">CAP. 01</span> La Tesis
                </h3>
                <p class="section-hint">La frase manifiesto. Las palabras clave se iluminan al scrollear.</p>

                <div class="form-group">
                    <label class="form-label">Label del capítulo</label>
                    <input type="text" class="form-control" name="cap1_label"
                           value="{{ old('cap1_label', $c['cap1_label'] ?? 'La tesis') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Frase de la tesis (manifiesto)</label>
                    <textarea class="form-control" name="tesis_text" rows="4"
                              placeholder="Tu declaración">{{ old('tesis_text', $c['tesis_text'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Palabras a destacar (separadas por coma)</label>
                    <input type="text" class="form-control" name="tesis_accent_words"
                           value="{{ old('tesis_accent_words', $c['tesis_accent_words'] ?? '') }}"
                           placeholder="medida,LATAM,rigor,mundo">
                    <div class="accent-words-help">
                        <strong>Cómo funciona:</strong> escribe las palabras (sin comillas, separadas por comas) que quieres que aparezcan en azul itálico dentro de la frase. Ej. para "Creemos que el software a <code>medida</code>…" escribe: <code>medida,LATAM,rigor</code>.
                    </div>
                </div>
            </div>

            {{-- ════════ CAP 02 — NÚMEROS ════════ --}}
            <div class="form-section">
                <h3 class="section-title">
                    <span class="chapter-num">CAP. 02</span> Los Números, en contexto
                </h3>
                <p class="section-hint">4 frases narrativas con un número grande que hace count-up.</p>

                <div class="form-group">
                    <label class="form-label">Label del capítulo</label>
                    <input type="text" class="form-control" name="cap2_label"
                           value="{{ old('cap2_label', $c['cap2_label'] ?? 'Los números, en contexto') }}">
                </div>

                @foreach([1,2,3,4] as $i)
                    <div class="stat-block">
                        <h4>Número {{ $i }}</h4>
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Antes del número</label>
                                <input type="text" class="form-control" name="stat_{{ $i }}_pre"
                                       value="{{ old('stat_'.$i.'_pre', $c['stat_'.$i.'_pre'] ?? '') }}"
                                       placeholder="Llevamos">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Número</label>
                                <input type="text" class="form-control" name="stat_{{ $i }}_num"
                                       value="{{ old('stat_'.$i.'_num', $c['stat_'.$i.'_num'] ?? '') }}"
                                       placeholder="28">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sufijo</label>
                                <input type="text" class="form-control" name="stat_{{ $i }}_suf"
                                       value="{{ old('stat_'.$i.'_suf', $c['stat_'.$i.'_suf'] ?? '') }}"
                                       placeholder="+ / h / %">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Después del número</label>
                            <input type="text" class="form-control" name="stat_{{ $i }}_post"
                                   value="{{ old('stat_'.$i.'_post', $c['stat_'.$i.'_post'] ?? '') }}"
                                   placeholder="plataformas en producción.">
                        </div>
                    </div>
                @endforeach

                <div class="form-group">
                    <label class="form-label">Frase de cierre</label>
                    <textarea class="form-control" name="numeros_foot" rows="2">{{ old('numeros_foot', $c['numeros_foot'] ?? '') }}</textarea>
                </div>
            </div>

            {{-- ════════ CAP 03 — CREDO ════════ --}}
            <div class="form-section">
                <h3 class="section-title">
                    <span class="chapter-num">CAP. 03</span> El Credo (7 declaraciones)
                </h3>
                <p class="section-hint">Las cosas que no se negocian. Cada una con una nota explicativa.</p>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Label del capítulo</label>
                        <input type="text" class="form-control" name="cap3_label"
                               value="{{ old('cap3_label', $c['cap3_label'] ?? 'El credo') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Headline del credo</label>
                        <input type="text" class="form-control" name="credo_headline"
                               value="{{ old('credo_headline', $c['credo_headline'] ?? 'Siete cosas que no negociamos.') }}">
                    </div>
                </div>

                @foreach(range(1,7) as $i)
                    <div class="credo-block">
                        <h4>Declaración {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</h4>
                        <div class="form-group">
                            <label class="form-label">Frase</label>
                            <input type="text" class="form-control" name="credo_{{ $i }}"
                                   value="{{ old('credo_'.$i, $c['credo_'.$i] ?? '') }}"
                                   placeholder="La declaración corta">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nota explicativa</label>
                            <textarea class="form-control" rows="2" name="credo_{{ $i }}_note"
                                      placeholder="El detalle que da contexto">{{ old('credo_'.$i.'_note', $c['credo_'.$i.'_note'] ?? '') }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ════════ CAP 04 — GENTE ════════ --}}
            <div class="form-section">
                <h3 class="section-title">
                    <span class="chapter-num">CAP. 04</span> La Gente (hasta 3 miembros)
                </h3>
                <p class="section-hint">Founders / equipo. Si no llenas un miembro, no aparece.</p>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Label del capítulo</label>
                        <input type="text" class="form-control" name="cap4_label"
                               value="{{ old('cap4_label', $c['cap4_label'] ?? 'La gente detrás') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sociales — LinkedIn empresa</label>
                        <input type="text" class="form-control" name="social_linkedin"
                               value="{{ old('social_linkedin', $c['social_linkedin'] ?? '') }}"
                               placeholder="https://linkedin.com/company/...">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Headline (texto plano)</label>
                        <input type="text" class="form-control" name="gente_head"
                               value="{{ old('gente_head', $c['gente_head'] ?? 'No vas a hablar con un comercial.') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Headline (texto azul itálico)</label>
                        <input type="text" class="form-control" name="gente_head_accent"
                               value="{{ old('gente_head_accent', $c['gente_head_accent'] ?? 'Vas a hablar con quien construye.') }}">
                    </div>
                </div>

                @foreach([1,2,3] as $i)
                    <div class="team-block">
                        <h4>Miembro {{ $i }}{{ $i === 1 ? ' (siempre visible si tiene nombre)' : '' }}</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" name="team_{{ $i }}_name"
                                       value="{{ old('team_'.$i.'_name', $c['team_'.$i.'_name'] ?? '') }}"
                                       placeholder="Nombre Apellido">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Rol</label>
                                <input type="text" class="form-control" name="team_{{ $i }}_role"
                                       value="{{ old('team_'.$i.'_role', $c['team_'.$i.'_role'] ?? '') }}"
                                       placeholder="Founder · Full-stack lead">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cita (frase memorable)</label>
                            <textarea class="form-control" rows="2" name="team_{{ $i }}_quote">{{ old('team_'.$i.'_quote', $c['team_'.$i.'_quote'] ?? '') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bio corta</label>
                            <textarea class="form-control" rows="2" name="team_{{ $i }}_bio">{{ old('team_'.$i.'_bio', $c['team_'.$i.'_bio'] ?? '') }}</textarea>
                        </div>
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">LinkedIn</label>
                                <input type="text" class="form-control" name="team_{{ $i }}_linkedin"
                                       value="{{ old('team_'.$i.'_linkedin', $c['team_'.$i.'_linkedin'] ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">GitHub</label>
                                <input type="text" class="form-control" name="team_{{ $i }}_github"
                                       value="{{ old('team_'.$i.'_github', $c['team_'.$i.'_github'] ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Web personal</label>
                                <input type="text" class="form-control" name="team_{{ $i }}_site"
                                       value="{{ old('team_'.$i.'_site', $c['team_'.$i.'_site'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ════════ CAP 05 — CRÉDITOS ════════ --}}
            <div class="form-section">
                <h3 class="section-title">
                    <span class="chapter-num">CAP. 05</span> Créditos (estilo película)
                </h3>
                <p class="section-hint">8 bloques tipo créditos de cine + CTA final.</p>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Label del capítulo</label>
                        <input type="text" class="form-control" name="cap5_label"
                               value="{{ old('cap5_label', $c['cap5_label'] ?? 'Créditos') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Headline</label>
                        <input type="text" class="form-control" name="creditos_head"
                               value="{{ old('creditos_head', $c['creditos_head'] ?? 'Nada se hace solo.') }}">
                    </div>
                </div>

                @foreach(range(1,8) as $i)
                    <div class="credit-block">
                        <h4>Crédito {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Rol (mayúsculas)</label>
                                <input type="text" class="form-control" name="cred_{{ $i }}_rol"
                                       value="{{ old('cred_'.$i.'_rol', $c['cred_'.$i.'_rol'] ?? '') }}"
                                       placeholder="DIRECCIÓN GENERAL">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Lista (1 línea por entrada)</label>
                                <textarea class="form-control" rows="3" name="cred_{{ $i }}_lista"
                                          placeholder="Cada salto de línea = nueva entrada">{{ old('cred_'.$i.'_lista', $c['cred_'.$i.'_lista'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- CTA final --}}
                <div class="credit-block" style="border-left-color: #f59e0b;">
                    <h4 style="color: #f59e0b;">CTA Final</h4>
                    <div class="form-group">
                        <label class="form-label">Eyebrow (sobre el título)</label>
                        <input type="text" class="form-control" name="cta_pre"
                               value="{{ old('cta_pre', $c['cta_pre'] ?? '¿Te suena el manifiesto?') }}">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Título (texto blanco)</label>
                            <input type="text" class="form-control" name="cta_title"
                                   value="{{ old('cta_title', $c['cta_title'] ?? 'Vamos a construir algo') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Título (texto itálico)</label>
                            <input type="text" class="form-control" name="cta_title_accent"
                                   value="{{ old('cta_title_accent', $c['cta_title_accent'] ?? 'que dure.') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Botón primario (→ /contacto)</label>
                            <input type="text" class="form-control" name="cta_button_text"
                                   value="{{ old('cta_button_text', $c['cta_button_text'] ?? 'Cuéntanos tu proyecto') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Botón secundario (→ /proyectos)</label>
                            <input type="text" class="form-control" name="cta_secondary_text"
                                   value="{{ old('cta_secondary_text', $c['cta_secondary_text'] ?? 'Ver lo que hicimos') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════ SEO ════════ --}}
            <div class="form-section seo-section">
                <h3 class="section-title">
                    <span class="chapter-num">SEO</span> Posicionamiento en buscadores
                </h3>
                <p class="section-hint">Meta tags, Open Graph, Twitter. Aquí defines cómo aparece en Google y al compartir.</p>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Meta Title (≤ 60 caracteres)</label>
                        <input type="text" class="form-control" name="meta_title" maxlength="60"
                               value="{{ old('meta_title', $page->seo->meta_title ?? '') }}"
                               placeholder="Sobre nosotros · MY Tech Solutions">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Focus Keyword</label>
                        <input type="text" class="form-control" name="focus_keyword"
                               value="{{ old('focus_keyword', $page->seo->focus_keyword ?? '') }}"
                               placeholder="agencia desarrollo software LATAM">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description (≤ 160 caracteres)</label>
                    <textarea class="form-control" name="meta_description" maxlength="160" rows="2"
                              placeholder="Lo que aparece en Google">{{ old('meta_description', $page->seo->meta_description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Keywords (separadas por coma)</label>
                    <input type="text" class="form-control" name="meta_keywords"
                           value="{{ old('meta_keywords', $page->seo->meta_keywords ?? '') }}"
                           placeholder="software a medida, agencia, LATAM, Colombia">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">URL Canonical</label>
                        <input type="text" class="form-control" name="canonical_url"
                               value="{{ old('canonical_url', $page->seo->canonical_url ?? '') }}"
                               placeholder="{{ url('/sobre-nosotros') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Robots</label>
                        <input type="text" class="form-control" name="robots"
                               value="{{ old('robots', $page->seo->robots ?? 'index,follow') }}"
                               placeholder="index,follow">
                    </div>
                </div>

                <h4 style="margin-top: 1.5rem; color: #28a745;">Open Graph (Facebook / LinkedIn)</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">OG Title</label>
                        <input type="text" class="form-control" name="og_title"
                               value="{{ old('og_title', $page->seo->og_title ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">OG Image (jpg/png, ≤ 4MB)</label>
                        <input type="file" class="form-control" name="og_image" accept="image/*">
                        @if(! empty($page->seo->og_image))
                            <small class="form-text">Actual: {{ basename($page->seo->og_image) }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">OG Description</label>
                    <textarea class="form-control" name="og_description" rows="2">{{ old('og_description', $page->seo->og_description ?? '') }}</textarea>
                </div>

                <h4 style="margin-top: 1.5rem; color: #28a745;">Twitter Card</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Twitter Card</label>
                        <select class="form-control" name="twitter_card">
                            <option value="summary_large_image" {{ ($page->seo->twitter_card ?? '') === 'summary_large_image' ? 'selected' : '' }}>summary_large_image</option>
                            <option value="summary" {{ ($page->seo->twitter_card ?? '') === 'summary' ? 'selected' : '' }}>summary</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Twitter Image (jpg/png, ≤ 4MB)</label>
                        <input type="file" class="form-control" name="twitter_image" accept="image/*">
                        @if(! empty($page->seo->twitter_image))
                            <small class="form-text">Actual: {{ basename($page->seo->twitter_image) }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Twitter Title</label>
                        <input type="text" class="form-control" name="twitter_title"
                               value="{{ old('twitter_title', $page->seo->twitter_title ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Twitter Description</label>
                        <input type="text" class="form-control" name="twitter_description"
                               value="{{ old('twitter_description', $page->seo->twitter_description ?? '') }}">
                    </div>
                </div>

                <h4 style="margin-top: 1.5rem; color: #28a745;">Sitemap</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Prioridad</label>
                        <select class="form-control" name="sitemap_priority">
                            @foreach(['1.0','0.9','0.8','0.7','0.5','0.3'] as $p)
                                <option value="{{ $p }}" {{ (string)($page->seo->sitemap_priority ?? '0.8') === $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Frecuencia de cambio</label>
                        <select class="form-control" name="sitemap_changefreq">
                            @foreach(['always','hourly','daily','weekly','monthly','yearly','never'] as $f)
                                <option value="{{ $f }}" {{ ($page->seo->sitemap_changefreq ?? 'monthly') === $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="submit-row">
                <a href="{{ route('sobre_nosotros.index') }}" target="_blank" class="btn-secondary">
                    <i class="fas fa-external-link-alt"></i> Ver página
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
