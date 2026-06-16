<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    protected $fillable = [
        'nombre', 'tipo', 'valor', 'moneda', 'activo', 'notas',
    ];

    protected function casts(): array
    {
        return [
            'valor'  => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    /** Tasa por defecto activa (fila única editable por el admin). */
    public static function actual(): self
    {
        return static::where('activo', true)->orderByDesc('id')->first()
            ?? static::firstOrCreate(
                ['nombre' => 'Tasa por defecto'],
                ['tipo' => 'porcentaje', 'valor' => 10, 'moneda' => 'COP', 'activo' => true]
            );
    }

    public function getResumenAttribute(): string
    {
        return $this->tipo === 'porcentaje'
            ? rtrim(rtrim(number_format((float) $this->valor, 2), '0'), '.') . '%'
            : ($this->moneda === 'USD' ? 'US$' : '$') . number_format((float) $this->valor, 0, ',', '.');
    }
}
