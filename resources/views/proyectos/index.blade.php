@extends('layouts.app-home')

{{--
    /proyectos — rediseñada con scroll storytelling cinematográfico premium.

    Contenido editable:
      - SEO de la página: tabla `seo` para page_id=(proyectos)
      - Textos del page header: `pages.content` slug='proyectos'
      - Proyectos individuales: tabla `proyectos`  →  /admin/admin-proyectos

    Skills aplicados: emil-design-eng (polish), gpt-taste (GSAP avanzado),
    impeccable (efectos ambiciosos), high-end-visual-design (premium).
--}}

@section('content')
    @include('partials.proyectos.hero')
    @include('partials.proyectos.marquee')
    @include('partials.proyectos.featured')
    @include('partials.proyectos.grid')
    @include('partials.proyectos.paises')

    {{-- Reuso del CTA dark del home con overlay de keys proy_cta_* --}}
    @php
        if (isset($page) && $page && $page->content) {
            $_pc = json_decode($page->content, true) ?? [];
            $_overlay = [];
            if (!empty($_pc['proy_cta_eyebrow']))      $_overlay['cta_eyebrow']      = $_pc['proy_cta_eyebrow'];
            if (!empty($_pc['proy_cta_title_main']))   $_overlay['cta_title_main']   = $_pc['proy_cta_title_main'];
            if (!empty($_pc['proy_cta_title_accent'])) $_overlay['cta_title_accent'] = $_pc['proy_cta_title_accent'];
            if (!empty($_pc['proy_cta_subtitle']))     $_overlay['cta_subtitle']     = $_pc['proy_cta_subtitle'];
            if (!empty($_overlay)) {
                $_merged = array_merge($_pc, $_overlay);
                $page = clone $page;
                $page->content = json_encode($_merged, JSON_UNESCAPED_UNICODE);
            }
        }
    @endphp
    @include('partials.home.cta-intermedio')
@endsection
