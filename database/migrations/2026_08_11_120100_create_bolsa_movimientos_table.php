<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bolsa_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_project_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->string('descripcion');
            $table->decimal('horas', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bolsa_movimientos');
    }
};
