<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /* 1️⃣  habilita asignación masiva  */
    protected $fillable = [
        'name',
        'description',
        'price',
        'avg_weight',
        'stock',        // ← AGREGAR ESTE CAMPO
        'image',
        'category_id',
        'interest',
    ];


public function category()
{
    return $this->belongsTo(\App\Models\Category::class);
}

public function images()
{
    return $this->hasMany(ProductImage::class);
}
public function prices()
{
    return $this->hasMany(Price::class);
}


}
