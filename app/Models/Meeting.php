<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $fillable = [
        'lead_id', 'user_id', 'titulo', 'tipo', 'scheduled_at', 'estado', 'resultado', 'notas',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public const TIPOS = [
        'descubrimiento' => ['label' => 'Descubrimiento', 'color' => '#2563EB'],
        'seguimiento'    => ['label' => 'Seguimiento',    'color' => '#06B6D4'],
        'cierre'         => ['label' => 'Cierre',         'color' => '#8B5CF6'],
    ];

    public const ESTADOS = [
        'agendada'  => ['label' => 'Agendada',  'color' => '#2563EB'],
        'realizada' => ['label' => 'Realizada', 'color' => '#16A34A'],
        'cancelada' => ['label' => 'Cancelada', 'color' => '#6B7280'],
        'no_show'   => ['label' => 'No asistió', 'color' => '#DC2626'],
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->hasRole('admin')) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }

    public function scopeProximas(Builder $query): Builder
    {
        return $query->where('estado', 'agendada')->where('scheduled_at', '>=', now()->startOfDay());
    }

    public function getTipoLabelAttribute(): string
    {
        return self::TIPOS[$this->tipo]['label'] ?? ucfirst($this->tipo);
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado]['label'] ?? ucfirst($this->estado);
    }

    public function getEstadoColorAttribute(): string
    {
        return self::ESTADOS[$this->estado]['color'] ?? '#6B7280';
    }
}
