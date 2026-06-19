<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('pais')->nullable()->after('empresa');
        });

        Schema::table('clientes_importados', function (Blueprint $table) {
            $table->string('pais')->nullable()->after('empresa');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('pais');
        });

        Schema::table('clientes_importados', function (Blueprint $table) {
            $table->dropColumn('pais');
        });
    }
};
