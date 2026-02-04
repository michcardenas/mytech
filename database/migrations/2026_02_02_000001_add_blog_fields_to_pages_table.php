<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Campos específicos para blogs
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Extracto/resumen para previews y SEO
            $table->text('excerpt')->nullable()->after('content');

            // Imagen destacada (featured image)
            $table->string('featured_image')->nullable()->after('excerpt');

            // Categoría del blog
            $table->string('category')->nullable()->after('featured_image');

            // Tags separados por comas
            $table->string('tags', 500)->nullable()->after('category');

            // Autor del artículo
            $table->string('author')->nullable()->after('tags');

            // Fecha de publicación (puede ser futura para programar)
            $table->timestamp('published_at')->nullable()->after('author');

            // Tiempo estimado de lectura en minutos
            $table->unsignedSmallInteger('reading_time')->nullable()->after('published_at');

            // Índice para consultas de blogs por categoría
            $table->index(['type', 'category', 'is_active']);
            $table->index(['type', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['type', 'category', 'is_active']);
            $table->dropIndex(['type', 'published_at']);

            $table->dropColumn([
                'excerpt',
                'featured_image',
                'category',
                'tags',
                'author',
                'published_at',
                'reading_time'
            ]);
        });
    }
};
