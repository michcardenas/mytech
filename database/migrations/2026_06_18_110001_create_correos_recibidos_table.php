<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('correos_recibidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uid')->unique();
            $table->string('de')->nullable();
            $table->string('nombre')->nullable();
            $table->text('asunto')->nullable();
            $table->timestamp('fecha')->nullable();
            $table->boolean('visto')->default(false);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('correos_recibidos');
    }
};
