<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'pago_default',
        'moneda_default',
    ];

    protected $casts = [
        'pago_default' => 'decimal:2',
    ];

    public function internalProjects()
    {
        return $this->hasMany(InternalProject::class);
    }
}
