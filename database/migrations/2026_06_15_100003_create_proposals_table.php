<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('titulo')->nullable();
            $table->decimal('monto', 12, 2)->nullable();
            $table->string('moneda', 3)->default('COP');
            $table->string('estado')->default('enviada'); // borrador, enviada, aceptada, rechazada
            $table->date('enviada_at')->nullable();
            $table->string('url')->nullable();            // link a la propuesta / Workana
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
