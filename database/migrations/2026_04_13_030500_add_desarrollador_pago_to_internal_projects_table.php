<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->decimal('desarrollador_pago', 12, 2)->nullable()->after('desarrollador_email');
            $table->enum('desarrollador_moneda', ['COP', 'USD'])->default('COP')->after('desarrollador_pago');
        });
    }

    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->dropColumn(['desarrollador_pago', 'desarrollador_moneda']);
        });
    }
};
