<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // comercial dueña
            $table->string('nombre');                      // contacto o empresa
            $table->string('empresa')->nullable();
            $table->string('fuente')->default('otro');     // workana, facebook, instagram, linkedin, referido, whatsapp, web, otro
            $table->string('fuente_url')->nullable();      // link de Workana / post, etc.
            $table->text('descripcion')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->decimal('valor_estimado', 12, 2)->nullable();
            $table->string('moneda', 3)->default('COP');   // COP / USD
            $table->string('etapa')->default('prospecto'); // prospecto, contactado, propuesta, reunion, cierre, ganado, perdido
            $table->string('estado')->default('abierto');  // abierto, ganado, perdido
            $table->string('motivo_perdido')->nullable();
            $table->dateTime('proxima_accion_at')->nullable();
            $table->string('proxima_accion_nota')->nullable();
            $table->unsignedInteger('orden')->default(0);  // orden dentro de la columna del kanban
            $table->timestamp('won_at')->nullable();
            $table->timestamp('lost_at')->nullable();
            $table->foreignId('internal_project_id')->nullable()->constrained('internal_projects')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'etapa']);
            $table->index('estado');
            $table->index('proxima_accion_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
