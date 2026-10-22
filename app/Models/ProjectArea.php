<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectArea extends Model
{
    protected $fillable = [
        'internal_project_id',
        'nombre',
        'color',
        'orden',
    ];

    /** Paleta por defecto para asignar color al crear areas automaticamente. */
    public const PALETA = [
        '#7c3aed', '#2563eb', '#059669', '#d97706', '#dc2626',
        '#0891b2', '#db2777', '#65a30d', '#4f46e5', '#ea580c',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(InternalProject::class, 'internal_project_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'area_id');
    }

    /**
     * Resuelve el area de una tarea a partir de un payload: "area_id" (debe
     * pertenecer al proyecto) o "area" por nombre (la crea con color de la
     * paleta si no existe). Devuelve null si se mando vacio.
     *
     * @param  array<string, mixed>  $raw
     */
    public static function resolverParaProyecto(InternalProject $project, array $raw): ?int
    {
        if (array_key_exists('area_id', $raw)) {
            if (empty($raw['area_id'])) {
                return null;
            }

            return optional($project->areas()->whereKey($raw['area_id'])->first())->id;
        }

        if (array_key_exists('area', $raw)) {
            $nombre = trim((string) $raw['area']);
            if ($nombre === '') {
                return null;
            }

            return $project->areas()->firstOrCreate(
                ['nombre' => $nombre],
                [
                    'color' => self::colorSugerido($project),
                    'orden' => (int) $project->areas()->max('orden') + 1,
                ]
            )->id;
        }

        return null;
    }

    /** Siguiente color de la paleta que no este ya en uso en el proyecto. */
    public static function colorSugerido(InternalProject $project): string
    {
        $usados = $project->areas()->pluck('color')->all();
        foreach (self::PALETA as $color) {
            if (! in_array($color, $usados, true)) {
                return $color;
            }
        }

        return self::PALETA[$project->areas()->count() % count(self::PALETA)];
    }
}
