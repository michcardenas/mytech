<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()
                ->comment('Destinatario; null = todos los clientes');
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

    public function down(): void
    {
        Schema::dropIfExists('cliente_banners');
    }
};
