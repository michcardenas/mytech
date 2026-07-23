<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comercial_banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()
                ->comment('Destinatario; null = todos los comerciales');
            $table->string('titulo');
            $table->text('mensaje')->nullable();
            $table->string('imagen')->nullable();
            $table->string('cta_texto')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('color', 20)->default('azul');
            $table->date('desde')->nullable();
            $table->date('hasta')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['activo', 'desde', 'hasta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comercial_banners');
    }
};
