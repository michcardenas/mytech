<a href="{{ route('pipeline.leads.show', $lead) }}" class="pd-row" style="--c:{{ $color }}">
    <span class="pd-fuente" style="background:{{ $lead->fuente_color }}"><i class="{{ $lead->fuente_icon }}"></i></span>
    <div>
        <div class="pd-name">{{ $lead->nombre }}@if($lead->empresa)<span class="text-muted fw-normal"> · {{ $lead->empresa }}</span>@endif</div>
        <div class="pd-note">{{ $lead->proxima_accion_nota ?: 'Seguimiento' }} · <span style="color:{{ $lead->etapa_color }}">{{ $lead->etapa_label }}</span></div>
    </div>
    <div class="pd-when" style="color:{{ $color }}">
        @if($lead->proxima_accion_at)
            {{ $lead->proxima_accion_at->translatedFormat('d M') }}<br>
            <span class="fw-normal text-muted">{{ $lead->proxima_accion_at->format('H:i') }}</span>
        @else
            <span class="fw-normal text-muted">Sin fecha</span>
        @endif
    </div>
</a>
