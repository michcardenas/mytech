<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTask extends Model
{
    protected $fillable = [
        'internal_project_id',
        'titulo',
        'descripcion',
        'columna',
        'prioridad',
        'developer_id',
        'fecha_limite',
        'orden',
        'created_by',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    /** Columnas del tablero (Kanban) en orden. */
    public const COLUMNAS = [
        'por_hacer' => ['label' => 'Por hacer',    'color' => '#94a3b8'],
        'en_progreso' => ['label' => 'En progreso',  'color' => '#2563eb'],
        'en_revision' => ['label' => 'En revisión',  'color' => '#f59e0b'],
        'hecho' => ['label' => 'Hecho',         'color' => '#16a34a'],
    ];

    public const PRIORIDADES = [
        'baja' => ['label' => 'Baja',  'color' => '#94a3b8'],
        'media' => ['label' => 'Media', 'color' => '#2563eb'],
        'alta' => ['label' => 'Alta',  'color' => '#dc2626'],
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(InternalProject::class, 'internal_project_id');
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(ProjectSubtask::class)->orderBy('orden')->orderBy('id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectTaskFile::class)->latest();
    }

    public function getColumnaLabelAttribute(): string
    {
        return self::COLUMNAS[$this->columna]['label'] ?? $this->columna;
    }

    public function getPrioridadLabelAttribute(): string
    {
        return self::PRIORIDADES[$this->prioridad]['label'] ?? $this->prioridad;
    }

    /** % de subtareas completadas (0-100); null si no tiene subtareas. */
    public function getProgresoAttribute(): ?int
    {
        $total = $this->subtasks->count();
        if ($total === 0) {
            return null;
        }

        return (int) round($this->subtasks->where('hecha', true)->count() / $total * 100);
    }

    public function getVencidaAttribute(): bool
    {
        return $this->fecha_limite
            && $this->columna !== 'hecho'
            && $this->fecha_limite->startOfDay()->isPast();
    }

    /** Tareas de un desarrollador: asignadas a él, o con al menos una subtarea suya. */
    public function scopeForDeveloper($query, int $developerId)
    {
        return $query->where(function ($q) use ($developerId) {
            $q->where('developer_id', $developerId)
                ->orWhereHas('subtasks', function ($s) use ($developerId) {
                    $s->where('developer_id', $developerId);
                });
        });
    }
}
