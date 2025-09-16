<?php
// database/migrations/2025_08_23_create_sections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->string('name')->comment('Identificador de la sección (hero, featured, etc.)');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->text('images')->nullable()->comment('URLs separadas por comas');
            $table->text('videos')->nullable()->comment('URLs separadas por comas');
            $table->integer('order')->default(0)->comment('Orden de visualización');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['page_id', 'order']);
            $table->unique(['page_id', 'name']); // Una sección por nombre por página
        });
    }

    public function down()
    {
        Schema::dropIfExists('sections');
    }
};