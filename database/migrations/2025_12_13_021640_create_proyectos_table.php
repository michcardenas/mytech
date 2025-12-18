<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('pais');
            $table->string('bandera_emoji')->default('🌎');
            $table->string('categoria'); // travel, booking, restaurant, admin, legal, tech, ecommerce
            $table->string('badge_text');
            $table->text('descripcion');
            $table->string('url')->nullable();
            $table->string('logo')->nullable(); // ruta de la imagen
            $table->json('tecnologias'); // array de tecnologías
            $table->enum('estado', ['en_vivo', 'en_desarrollo', 'pausado'])->default('en_vivo');
            $table->boolean('destacado')->default(false);
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
