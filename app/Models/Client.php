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
    ];

    public function internalProjects()
    {
        return $this->hasMany(InternalProject::class);
    }
}
