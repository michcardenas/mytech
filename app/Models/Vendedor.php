<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'comision_porcentaje_default',
        'sueldo_basico',
        'sueldo_moneda',
        'escalonada_activa',
        'escalones',
    ];

    protected $casts = [
        'comision_porcentaje_default' => 'decimal:2',
        'sueldo_basico' => 'decimal:2',
        'escalonada_activa' => 'boolean',
        'escalones' => 'array',
    ];

    /** Tramos por defecto cuando se activa la comisión escalonada. */
    public const ESCALONES_DEFAULT = [
        ['desde' => 1, 'pct' => 5],
        ['desde' => 3, 'pct' => 6],
        ['desde' => 5, 'pct' => 7],
    ];

    /**
     * Devuelve el % de comisión que aplica según cuántos proyectos cerró en el ciclo.
     * Retroactivo: el tramo alcanzado se aplica a TODOS los cierres del ciclo.
     */
    public function porcentajePorCierres(int $cierres): ?float
    {
        if (! $this->escalonada_activa || $cierres < 1) {
            return null;
        }

        $tramos = collect($this->escalones ?: self::ESCALONES_DEFAULT)
            ->sortBy('desde');

        $pct = null;
        foreach ($tramos as $t) {
            if ($cierres >= (int) ($t['desde'] ?? 0)) {
                $pct = (float) ($t['pct'] ?? 0);
            }
        }

        return $pct;
    }

    public function internalProjects()
    {
        return $this->hasMany(InternalProject::class);
    }
}
