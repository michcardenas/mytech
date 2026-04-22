<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalProject extends Model
{
    protected $fillable = [
        'nombre',
        'client_id',
        'developer_id',
        'vendedor_id',
        'comision_tipo',
        'comision_valor',
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
        'comision_valor' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_entrega' => 'date',
        'es_recurrente' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    /**
     * Comisión calculada del vendedor, en la moneda del proyecto.
     * Si tipo = 'monto' → devuelve el valor tal cual.
     * Si tipo = 'porcentaje' → calcula % sobre (precio − pago_dev).
     *   Cuando el dev está en moneda distinta, se usa la tasa USD_COP de config.
     */
    public function getComisionCalculadaAttribute(): float
    {
        if (!$this->comision_tipo || !$this->comision_valor) {
            return 0.0;
        }

        if ($this->comision_tipo === 'monto') {
            return (float) $this->comision_valor;
        }

        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $pagoDev = (float) ($this->desarrollador_pago ?? 0);
        $devMoneda = $this->desarrollador_moneda ?? 'COP';

        // Normalizar pago del dev a la moneda del proyecto antes de restar
        if ($devMoneda !== $this->moneda) {
            $pagoDev = $devMoneda === 'USD' && $this->moneda === 'COP'
                ? $pagoDev * $usdCop
                : ($devMoneda === 'COP' && $this->moneda === 'USD' ? $pagoDev / $usdCop : $pagoDev);
        }

        $base = max((float) $this->precio - $pagoDev, 0);
        return round($base * ((float) $this->comision_valor / 100), 2);
    }

    public function payments()
    {
        return $this->hasMany(ProjectPayment::class);
    }

    public function developerPayments()
    {
        return $this->hasMany(DeveloperPayment::class);
    }

    public function expenses()
    {
        return $this->hasMany(ProjectExpense::class);
    }

    public function getTotalGastosAttribute()
    {
        return $this->expenses->sum('monto');
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
        return $this->total_recibido_cop - $this->total_pagado_dev - $this->total_gastos;
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
