<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Comentarios (comunicación del equipo) por tarea del tablero.
     */
    public function up(): void
    {
        Schema::create('project_task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_task_id')->constrained('project_tasks')->cascadeOnDelete();
            $table->text('cuerpo');
            $table->string('autor_tipo')->default('admin'); // admin | dev
            $table->string('autor_nombre');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('developer_id')->nullable()->constrained('developers')->nullOnDelete();
            $table->timestamps();

            $table->index('project_task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_task_comments');
    }
};
