<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuentaCobro extends Model
{
    protected $table = 'cuenta_cobros';

    protected $fillable = [
        'internal_project_id',
        'numero_doc',
        'tipo',
        'valor_param',
        'monto',
        'moneda',
        'periodo',
        'visible_cliente',
    ];

    protected $casts = [
        'valor_param' => 'decimal:2',
        'monto' => 'decimal:2',
        'periodo' => 'date',
        'visible_cliente' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(InternalProject::class, 'internal_project_id');
    }
}
