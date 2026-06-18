<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // quién lo envió
            $table->uuid('batch_id')->nullable()->index();  // agrupa un envío a varios destinatarios
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete(); // si se envía desde un lead
            $table->string('para');                          // destinatario
            $table->string('nombre_destinatario')->nullable();
            $table->string('asunto');
            $table->longText('cuerpo');                      // HTML
            $table->json('adjuntos')->nullable();            // rutas de adjuntos
            $table->string('estado')->default('pendiente');  // pendiente, enviado, fallido
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
