<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropColumn('price'); // 🔴 Eliminar la columna price
        });
    }

    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable(); // Por si necesitas revertir
        });
    }
};