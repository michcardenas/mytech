<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    protected $fillable = [
        'user_id', 'batch_id', 'lead_id', 'para', 'nombre_destinatario',
        'asunto', 'cuerpo', 'adjuntos', 'estado', 'error', 'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'adjuntos' => 'array',
            'sent_at'  => 'datetime',
        ];
    }

    public const ESTADOS = [
        'pendiente' => ['label' => 'En cola',  'color' => '#F59E0B'],
        'enviado'   => ['label' => 'Enviado',  'color' => '#16A34A'],
        'fallido'   => ['label' => 'Fallido',  'color' => '#DC2626'],
    ];

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
}
