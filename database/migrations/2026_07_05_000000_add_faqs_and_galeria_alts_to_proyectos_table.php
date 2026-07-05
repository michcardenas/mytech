<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // FAQ por proyecto → alimenta el schema FAQPage (AI search + People Also Ask)
            $table->json('faqs')->nullable()->after('resultados');

            // Alt text personalizado por imagen de galería (Google Images)
            $table->json('galeria_alts')->nullable()->after('galeria');
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn(['faqs', 'galeria_alts']);
        });
    }
};
