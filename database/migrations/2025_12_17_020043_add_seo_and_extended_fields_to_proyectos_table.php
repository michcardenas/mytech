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
        Schema::table('proyectos', function (Blueprint $table) {
            // Campos SEO
            $table->string('meta_title')->nullable()->after('descripcion');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords'); // Imagen para compartir en redes sociales

            // Campos de contenido extendido
            $table->text('descripcion_extendida')->nullable()->after('og_image'); // Descripción más larga para la página individual
            $table->text('desafio')->nullable()->after('descripcion_extendida'); // El problema que resolviste
            $table->text('solucion')->nullable()->after('desafio'); // Cómo lo resolviste
            $table->text('resultados')->nullable()->after('solucion'); // Métricas de éxito
            $table->json('galeria')->nullable()->after('resultados'); // Array de imágenes adicionales

            // Campos de testimonios
            $table->text('testimonio')->nullable()->after('galeria'); // Quote del cliente
            $table->string('testimonio_autor')->nullable()->after('testimonio'); // Nombre del cliente
            $table->string('testimonio_cargo')->nullable()->after('testimonio_autor'); // Cargo del cliente

            // Campos adicionales del proyecto
            $table->string('duracion_desarrollo')->nullable()->after('testimonio_cargo'); // "3 meses"
            $table->integer('equipo_size')->nullable()->after('duracion_desarrollo'); // Cuántas personas trabajaron
            $table->date('fecha_lanzamiento')->nullable()->after('equipo_size');
            $table->integer('visitas_mensuales')->nullable()->after('fecha_lanzamiento'); // Tráfico del sitio
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'meta_keywords',
                'og_image',
                'descripcion_extendida',
                'desafio',
                'solucion',
                'resultados',
                'galeria',
                'testimonio',
                'testimonio_autor',
                'testimonio_cargo',
                'duracion_desarrollo',
                'equipo_size',
                'fecha_lanzamiento',
                'visitas_mensuales',
            ]);
        });
    }
};
