<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeveloperPayment extends Model
{
    protected $fillable = [
        'internal_project_id',
        'monto',
        'moneda',
        'fecha',
        'metodo',
        'referencia',
        'nota',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(InternalProject::class, 'internal_project_id');
    }
}
