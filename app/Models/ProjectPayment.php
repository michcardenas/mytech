<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPayment extends Model
{
    protected $fillable = [
        'internal_project_id',
        'monto',
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
