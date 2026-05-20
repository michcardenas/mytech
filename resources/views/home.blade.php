@extends('layouts.app-home')

{{--
    Title y meta description NO se sobreescriben aquí — viven en BD (tabla `seo`),
    editables desde /admin/seo/1/edit. Si necesitas cambiar copy de SEO, hazlo allí.
--}}

@section('content')
    @include('partials.home.hero')
    @include('partials.home.casos-produccion')
    @include('partials.home.servicios')
    @include('partials.home.proceso')
    @include('partials.home.stack-tecnologico')
    @include('partials.home.cta-intermedio')
@endsection
