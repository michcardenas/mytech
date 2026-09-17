<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tasa de cambio estimada (a COP) del proyecto interno.
 *
 * Para proyectos en USD/EUR: el usuario fija al crear una tasa global estimada
 * para ver cuánto valdría el precio en pesos. El valor REAL recibido se sigue
 * registrando por pago en ProjectPayment.monto_recibido_cop.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_projects', function (Blueprint $table): void {
            $table->decimal('tasa_cambio_estimada', 12, 2)->nullable()->after('moneda');
        });
    }

    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table): void {
            $table->dropColumn('tasa_cambio_estimada');
        });
    }
};
