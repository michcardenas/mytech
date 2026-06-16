<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proposal extends Model
{
    protected $fillable = [
        'lead_id', 'user_id', 'titulo', 'monto', 'moneda', 'estado', 'enviada_at', 'url', 'notas',
    ];

    protected function casts(): array
    {
        return [
            'monto'      => 'decimal:2',
            'enviada_at' => 'date',
        ];
    }

    public const ESTADOS = [
        'borrador'  => ['label' => 'Borrador',  'color' => '#6B7280'],
        'enviada'   => ['label' => 'Enviada',   'color' => '#2563EB'],
        'aceptada'  => ['label' => 'Aceptada',  'color' => '#16A34A'],
        'rechazada' => ['label' => 'Rechazada', 'color' => '#DC2626'],
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado]['label'] ?? ucfirst($this->estado);
    }

    public function getEstadoColorAttribute(): string
    {
        return self::ESTADOS[$this->estado]['color'] ?? '#6B7280';
    }

    public function getMontoFormateadoAttribute(): string
    {
        if ($this->monto === null) {
            return '—';
        }
        $simbolo = $this->moneda === 'USD' ? 'US$' : '$';

        return $simbolo . number_format((float) $this->monto, 0, ',', '.');
    }
}
