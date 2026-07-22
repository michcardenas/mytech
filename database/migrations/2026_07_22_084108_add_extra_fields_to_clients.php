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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('email')->nullable()->after('empresa');
            $table->string('direccion')->nullable()->after('email');
            $table->string('ciudad')->nullable()->after('direccion');
            $table->string('pais')->nullable()->after('ciudad');
            $table->string('web')->nullable()->after('pais');
            $table->string('cargo_contacto')->nullable()->after('web');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['email', 'direccion', 'ciudad', 'pais', 'web', 'cargo_contacto']);
        });
    }
};
