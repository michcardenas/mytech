<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BolsaMovimiento extends Model
{
    protected $fillable = [
        'internal_project_id',
        'fecha',
        'tema',
        'descripcion',
        'horas',
    ];

    protected $casts = [
        'fecha' => 'date',
        'horas' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(InternalProject::class, 'internal_project_id');
    }
}
