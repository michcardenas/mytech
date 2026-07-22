<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'empresa',
        'identificacion',
        'email',
        'direccion',
        'ciudad',
        'pais',
        'web',
        'cargo_contacto',
    ];

    public function internalProjects()
    {
        return $this->hasMany(InternalProject::class);
    }
}
