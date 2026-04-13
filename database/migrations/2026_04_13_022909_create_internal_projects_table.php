<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_projects', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cliente_nombre');
            $table->string('cliente_contacto')->nullable();
            $table->string('cliente_email')->nullable();
            $table->enum('fuente', ['directo', 'workana'])->default('directo');
            $table->string('fuente_url')->nullable();
            $table->decimal('precio', 12, 2);
            $table->enum('moneda', ['COP', 'USD'])->default('COP');
            $table->enum('estado', ['cotizado', 'en_progreso', 'pausado', 'completado', 'cancelado'])->default('cotizado');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_projects');
    }
};
