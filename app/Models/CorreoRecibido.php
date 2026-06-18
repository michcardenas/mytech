<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorreoRecibido extends Model
{
    protected $table = 'correos_recibidos';

    protected $fillable = [
        'uid', 'de', 'nombre', 'asunto', 'fecha', 'visto', 'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
            'visto' => 'boolean',
            'synced_at' => 'datetime',
        ];
    }
}
