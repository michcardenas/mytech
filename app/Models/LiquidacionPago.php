<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidacionPago extends Model
{
    protected $table = 'liquidacion_pagos';

    protected $fillable = [
        'vendedor_id',
        'periodo',
        'fecha_pago',
        'monto',
        'metodo',
        'referencia',
        'comprobante',
        'nota',
    ];

    protected $casts = [
        'periodo' => 'date',
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Vendedor::class);
    }
}
