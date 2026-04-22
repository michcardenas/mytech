<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'comision_porcentaje_default',
    ];

    protected $casts = [
        'comision_porcentaje_default' => 'decimal:2',
    ];

    public function internalProjects()
    {
        return $this->hasMany(InternalProject::class);
    }
}
