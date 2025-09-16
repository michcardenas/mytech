<?php
// database/migrations/2024_01_01_create_pages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // inicio, quienes-somos, contacto, servicios
            $table->string('section')->nullable(); // seccion que se va  editar 
            $table->string('title');
            $table->longText('content')->nullable(); // Texto contenido
            $table->text('images')->nullable(); // Rutas de imágenes separadas por comas
            $table->text('videos')->nullable(); // URLs de videos separadas por comas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
};