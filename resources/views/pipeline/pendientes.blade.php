@extends('layouts.app_admin')

@section('content')
<style>
    .pd-wrap { padding:1.5rem 1.75rem; max-width:1000px; }
    .pd-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .15rem; }
    .pd-sub { color:#64748B; font-size:.9rem; margin:0 0 1.4rem; }
    .pd-group { margin-bottom:1.6rem; }
    .pd-group h3 { font-size:.85rem; font-weight:800; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.7rem; display:flex; align-items:center; gap:.5rem; }
    .pd-row { display:flex; align-items:center; gap:.85rem; background:#fff; border:1px solid #E5E7EB; border-left:3px solid var(--c,#CBD5E1); border-radius:11px; padding:.7rem .9rem; margin-bottom:.5rem; text-decoration:none; transition:box-shadow .15s; }
    .pd-row:hover { box-shadow:0 6px 18px rgba(37,99,235,.1); }
    .pd-fuente { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; flex-shrink:0; }
    .pd-name { font-weight:700; color:#0F172A; font-size:.92rem; }
    .pd-note { font-size:.78rem; color:#64748B; }
    .pd-when { margin-left:auto; text-align:right; font-size:.8rem; font-weight:700; white-space:nowrap; }
    .pd-empty { color:#94A3B8; font-size:.85rem; }
</style>

<div class="pd-wrap">
    <h1 class="pd-title">Pendientes de seguimiento</h1>
    <p class="pd-sub">Tus leads ordenados por lo que requiere tu atención. No dejes ninguno sin próxima acción.</p>

    @php
        $render = function ($leads, $colorVar) {
            return [$leads, $colorVar];
        };
    @endphp

    {{-- Vencidos --}}
    <div class="pd-group">
        <h3 class="text-danger"><i class="fas fa-triangle-exclamation"></i> Vencidos ({{ $vencidos->count() }})</h3>
        @forelse($vencidos as $lead)
            @include('pipeline.partials.pendiente-row', ['lead' => $lead, 'color' => '#DC2626'])
        @empty
            <p class="pd-empty">Nada vencido. 🎉</p>
        @endforelse
    </div>

    {{-- Hoy --}}
    <div class="pd-group">
        <h3 style="color:#B45309"><i class="fas fa-bolt"></i> Hoy ({{ $hoy->count() }})</h3>
        @forelse($hoy as $lead)
            @include('pipeline.partials.pendiente-row', ['lead' => $lead, 'color' => '#F59E0B'])
        @empty
            <p class="pd-empty">Sin pendientes para hoy.</p>
        @endforelse
    </div>

    {{-- Esta semana --}}
    <div class="pd-group">
        <h3 style="color:#2563EB"><i class="fas fa-calendar-week"></i> Esta semana ({{ $semana->count() }})</h3>
        @forelse($semana as $lead)
            @include('pipeline.partials.pendiente-row', ['lead' => $lead, 'color' => '#2563EB'])
        @empty
            <p class="pd-empty">Sin pendientes esta semana.</p>
        @endforelse
    </div>

    {{-- Sin fecha --}}
    @if($sinFecha->count())
    <div class="pd-group">
        <h3 style="color:#64748B"><i class="fas fa-circle-question"></i> Sin próxima acción ({{ $sinFecha->count() }})</h3>
        @foreach($sinFecha as $lead)
            @include('pipeline.partials.pendiente-row', ['lead' => $lead, 'color' => '#CBD5E1'])
        @endforeach
    </div>
    @endif
</div>
@endsection
