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
        'desarrollador_pago',
        'desarrollador_moneda',
        'es_recurrente',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'desarrollador_pago' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_entrega' => 'date',
        'es_recurrente' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(ProjectPayment::class);
    }

    public function developerPayments()
    {
        return $this->hasMany(DeveloperPayment::class);
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function getTotalPagadoAttribute()
    {
        return $this->payments->sum('monto');
    }

    public function getTotalRecibidoCopAttribute()
    {
        return $this->payments->sum(function ($p) {
            return $p->monto_recibido_cop ?? ($this->moneda === 'COP' ? $p->monto : 0);
        });
    }

    public function getTotalPagadoDevAttribute()
    {
        return $this->developerPayments->sum('monto');
    }

    public function getSaldoPendienteDevAttribute()
    {
        return ($this->desarrollador_pago ?? 0) - $this->total_pagado_dev;
    }

    public function getUtilidadAttribute()
    {
        return $this->total_recibido_cop - $this->total_pagado_dev;
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
