<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteImportado extends Model
{
    protected $table = 'clientes_importados';

    protected $fillable = [
        'identificacion', 'nombre', 'empresa', 'pais', 'email', 'telefono', 'telefono2',
        'descripcion', 'lote_importacion', 'importado_por',
    ];
}
