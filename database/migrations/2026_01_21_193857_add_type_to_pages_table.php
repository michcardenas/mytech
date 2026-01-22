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
        Schema::table('pages', function (Blueprint $table) {
            $table->enum('type', ['page', 'landing', 'blog'])
                  ->default('page')
                  ->after('slug')
                  ->comment('Tipo de página: page (normal), landing (landing page), blog (entrada de blog)');

            $table->boolean('is_active')
                  ->default(true)
                  ->after('videos')
                  ->comment('Si la página está activa o no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['type', 'is_active']);
        });
    }
};
