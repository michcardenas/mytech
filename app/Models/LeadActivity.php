<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivity extends Model
{
    protected $fillable = [
        'lead_id', 'user_id', 'tipo', 'descripcion',
    ];

    /** Tipos de actividad con etiqueta e ícono. */
    public const TIPOS = [
        'nota'      => ['label' => 'Nota',             'icon' => 'fas fa-note-sticky',     'color' => '#6B7280'],
        'llamada'   => ['label' => 'Llamada',          'icon' => 'fas fa-phone',           'color' => '#2563EB'],
        'whatsapp'  => ['label' => 'WhatsApp',         'icon' => 'fab fa-whatsapp',        'color' => '#25D366'],
        'email'     => ['label' => 'Email',            'icon' => 'fas fa-envelope',        'color' => '#0EA5E9'],
        'reunion'   => ['label' => 'Reunión',          'icon' => 'fas fa-video',           'color' => '#06B6D4'],
        'propuesta' => ['label' => 'Propuesta',        'icon' => 'fas fa-file-invoice',    'color' => '#F59E0B'],
        'etapa'     => ['label' => 'Cambio de etapa',  'icon' => 'fas fa-arrows-turn-right','color' => '#8B5CF6'],
        'sistema'   => ['label' => 'Sistema',          'icon' => 'fas fa-gear',            'color' => '#9CA3AF'],
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return self::TIPOS[$this->tipo]['label'] ?? ucfirst($this->tipo);
    }

    public function getTipoIconAttribute(): string
    {
        return self::TIPOS[$this->tipo]['icon'] ?? 'fas fa-note-sticky';
    }

    public function getTipoColorAttribute(): string
    {
        return self::TIPOS[$this->tipo]['color'] ?? '#6B7280';
    }
}
