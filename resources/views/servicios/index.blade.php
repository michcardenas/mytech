@extends('layouts.app-home')

{{--
    /servicios — rediseñada con scroll storytelling cinematográfico.
    Contenido y SEO viven en BD (slug='servicios', page id=3).
    Edición:
      - Contenido:  /admin/pages/servicios/edit  (tab "Storytelling")
      - SEO:        misma vista, tab SEO
--}}

@section('content')
    @include('partials.servicios.hero')
    @include('partials.servicios.storytelling')

    {{--
        Stack tecnológico — reuso del partial del home.
        El partial lee `stack_*` del JSON content. Para /servicios mapeamos los
        `serv_stack_*` a esos keys vía clon temporal del $page (no muta el original).
    --}}
    @php
        if (isset($page) && $page && $page->content) {
            $_sc = json_decode($page->content, true) ?? [];
            $_overlay = [];
            if (!empty($_sc['serv_stack_eyebrow']))  $_overlay['stack_eyebrow']  = $_sc['serv_stack_eyebrow'];
            if (!empty($_sc['serv_stack_title']))    $_overlay['stack_title']    = $_sc['serv_stack_title'];
            if (!empty($_sc['serv_stack_subtitle'])) $_overlay['stack_subtitle'] = $_sc['serv_stack_subtitle'];
            if (!empty($_overlay)) {
                $_merged = array_merge($_sc, $_overlay);
                $page = clone $page;
                $page->content = json_encode($_merged, JSON_UNESCAPED_UNICODE);
            }
        }
    @endphp
    @include('partials.home.stack-tecnologico')

    {{-- FAQ + FAQPage schema (activa rich snippet de FAQ en SERPs) --}}
    @include('partials.servicios.faq')
@endsection
