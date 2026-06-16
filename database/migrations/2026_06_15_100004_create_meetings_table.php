<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // responsable / creador
            $table->string('titulo')->nullable();
            $table->string('tipo')->default('descubrimiento'); // descubrimiento, seguimiento, cierre
            $table->dateTime('scheduled_at');
            $table->string('estado')->default('agendada'); // agendada, realizada, cancelada, no_show
            $table->text('resultado')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'scheduled_at']);
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
