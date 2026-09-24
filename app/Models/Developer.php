<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'pago_default',
        'moneda_default',
    ];

    protected $casts = [
        'pago_default' => 'decimal:2',
    ];

    public function internalProjects(): HasMany
    {
        return $this->hasMany(InternalProject::class);
    }

    /** Proyectos internos donde este dev es parte del equipo (tablero de tareas). */
    public function equipoProjects(): BelongsToMany
    {
        return $this->belongsToMany(InternalProject::class, 'internal_project_developer')
            ->withTimestamps();
    }

    /** Tareas del tablero asignadas a este dev. */
    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }
}
