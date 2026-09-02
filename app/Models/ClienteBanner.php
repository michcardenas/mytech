<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteBanner extends Model
{
    protected $table = 'cliente_banners';

    protected $fillable = [
        'client_id',
        'titulo',
        'mensaje',
        'imagen',
        'cta_texto',
        'cta_url',
        'color',
        'desde',
        'hasta',
        'orden',
        'activo',
    ];

    protected $casts = [
        'desde' => 'date',
        'hasta' => 'date',
        'activo' => 'boolean',
    ];

    /**
     * Paletas disponibles para el banner.
     * accent = color sólido del acento (barra + botón); soft = tono claro para el marco de la imagen.
     */
    public const COLORES = [
        'azul' => ['label' => 'Azul MyTech', 'grad' => 'linear-gradient(135deg,#2563EB,#1D4ED8)', 'accent' => '#2563EB', 'soft' => '#EFF6FF'],
        'navy' => ['label' => 'Navy', 'grad' => 'linear-gradient(135deg,#1E293B,#0F172A)', 'accent' => '#334155', 'soft' => '#F1F5F9'],
        'verde' => ['label' => 'Verde éxito', 'grad' => 'linear-gradient(135deg,#10B981,#059669)', 'accent' => '#059669', 'soft' => '#ECFDF5'],
        'violeta' => ['label' => 'Violeta', 'grad' => 'linear-gradient(135deg,#8B5CF6,#6D28D9)', 'accent' => '#7C3AED', 'soft' => '#F5F3FF'],
        'naranja' => ['label' => 'Naranja oferta', 'grad' => 'linear-gradient(135deg,#F59E0B,#D97706)', 'accent' => '#D97706', 'soft' => '#FFF7ED'],
        'rojo' => ['label' => 'Rojo urgente', 'grad' => 'linear-gradient(135deg,#EF4444,#B91C1C)', 'accent' => '#DC2626', 'soft' => '#FEF2F2'],
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getGradienteAttribute(): string
    {
        return self::COLORES[$this->color]['grad'] ?? self::COLORES['azul']['grad'];
    }

    public function getAccentAttribute(): string
    {
        return self::COLORES[$this->color]['accent'] ?? self::COLORES['azul']['accent'];
    }

    public function getSoftAttribute(): string
    {
        return self::COLORES[$this->color]['soft'] ?? self::COLORES['azul']['soft'];
    }

    /**
     * Banners vigentes hoy para un cliente (los suyos + los generales).
     * Si $clientId es null (cliente identificado solo por teléfono, sin ficha),
     * ve únicamente los banners generales.
     */
    public function scopeVigentesPara(Builder $query, ?int $clientId = null): Builder
    {
        $hoy = now()->toDateString();

        return $query->where('activo', true)
            ->where(function ($q) use ($clientId) {
                $q->whereNull('client_id');
                if ($clientId) {
                    $q->orWhere('client_id', $clientId);
                }
            })
            ->where(fn ($q) => $q->whereNull('desde')->orWhereDate('desde', '<=', $hoy))
            ->where(fn ($q) => $q->whereNull('hasta')->orWhereDate('hasta', '>=', $hoy))
            ->orderBy('orden')
            ->orderByDesc('id');
    }
}
