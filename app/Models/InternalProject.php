<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalProject extends Model
{
    protected $fillable = [
        'nombre',
        'cliente_nombre',
        'cliente_contacto',
        'cliente_email',
        'fuente',
        'fuente_url',
        'precio',
        'moneda',
        'estado',
        'fecha_inicio',
        'fecha_entrega',
        'descripcion',
        'notas',
        'desarrollador_nombre',
        'desarrollador_email',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_entrega' => 'date',
    ];

    public function payments()
    {
        return $this->hasMany(ProjectPayment::class);
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function getTotalPagadoAttribute()
    {
        return $this->payments->sum('monto');
    }

    public function getSaldoPendienteAttribute()
    {
        return $this->precio - $this->total_pagado;
    }

    public function getPorcentajePagadoAttribute()
    {
        if ($this->precio <= 0) return 0;
        return round(($this->total_pagado / $this->precio) * 100);
    }

    public function getEstadoLabelAttribute()
    {
        return match ($this->estado) {
            'cotizado' => 'Cotizado',
            'en_progreso' => 'En Progreso',
            'pausado' => 'Pausado',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado',
            default => $this->estado,
        };
    }

    public function getEstadoColorAttribute()
    {
        return match ($this->estado) {
            'cotizado' => '#f7a831',
            'en_progreso' => '#007BFF',
            'pausado' => '#6c757d',
            'completado' => '#28a745',
            'cancelado' => '#dc3545',
            default => '#6c757d',
        };
    }

    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeFuente($query, $fuente)
    {
        return $query->where('fuente', $fuente);
    }
}
