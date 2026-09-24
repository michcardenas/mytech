<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Archivos adjuntos a una tarea del tablero.
     */
    public function up(): void
    {
        Schema::create('project_task_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_task_id')->constrained('project_tasks')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('archivo');
            $table->string('tipo')->nullable();
            $table->unsignedBigInteger('tamano')->default(0);
            $table->string('subido_por')->nullable();
            $table->timestamps();

            $table->index('project_task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_task_files');
    }
};
