<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComercialBanner extends Model
{
    protected $table = 'comercial_banners';

    protected $fillable = [
        'user_id',
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

    /** Paletas disponibles para el banner. */
    public const COLORES = [
        'azul' => ['label' => 'Azul MyTech', 'grad' => 'linear-gradient(135deg,#2563EB,#1D4ED8)'],
        'navy' => ['label' => 'Navy', 'grad' => 'linear-gradient(135deg,#1E293B,#0F172A)'],
        'verde' => ['label' => 'Verde éxito', 'grad' => 'linear-gradient(135deg,#10B981,#059669)'],
        'violeta' => ['label' => 'Violeta', 'grad' => 'linear-gradient(135deg,#8B5CF6,#6D28D9)'],
        'naranja' => ['label' => 'Naranja reto', 'grad' => 'linear-gradient(135deg,#F59E0B,#D97706)'],
        'rojo' => ['label' => 'Rojo urgente', 'grad' => 'linear-gradient(135deg,#EF4444,#B91C1C)'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getGradienteAttribute(): string
    {
        return self::COLORES[$this->color]['grad'] ?? self::COLORES['azul']['grad'];
    }

    /** Banners vigentes hoy para un usuario (los suyos + los generales). */
    public function scopeVigentesPara(Builder $query, int $userId): Builder
    {
        $hoy = now()->toDateString();

        return $query->where('activo', true)
            ->where(fn ($q) => $q->whereNull('user_id')->orWhere('user_id', $userId))
            ->where(fn ($q) => $q->whereNull('desde')->orWhereDate('desde', '<=', $hoy))
            ->where(fn ($q) => $q->whereNull('hasta')->orWhereDate('hasta', '>=', $hoy))
            ->orderBy('orden')
            ->orderByDesc('id');
    }
}
