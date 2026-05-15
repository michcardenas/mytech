@extends('layouts.app-home')

@section('title', 'Agencia de Software a Medida en Colombia | MY Tech Solutions')
@section('meta_description', 'Desarrollamos software a medida, SaaS e integraciones IA para empresas en Colombia y LATAM. Proyectos en producción en 7 países. Cotiza tu proyecto gratis.')

@section('content')
    @include('partials.home.hero')
    @include('partials.home.casos-produccion')
    @include('partials.home.servicios')
    @include('partials.home.proceso')
    @include('partials.home.stack-tecnologico')
    @include('partials.home.cta-intermedio')
@endsection
