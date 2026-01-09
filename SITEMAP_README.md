# Sitemap Dinámico - Guía de Uso

## Descripción

Este proyecto implementa un sistema de sitemap dinámico usando la librería **spatie/laravel-sitemap** que genera automáticamente un `sitemap.xml` con todas las URLs públicas del sitio, incluyendo:

- URLs estáticas (inicio, servicios, proyectos, sobre nosotros, contacto)
- **Proyectos dinámicos** - Cada vez que creas un nuevo proyecto, automáticamente se agrega su ruta al sitemap
- Productos y categorías (si existen)

## Características Principales

### 1. Generación Automática de Rutas Dinámicas
Cuando creas un nuevo proyecto en el administrador, automáticamente se generará su URL en el sitemap:
```
https://tudominio.com/proyectos/nombre-del-proyecto
```

### 2. Regeneración Automática Diaria
El sitemap se regenera automáticamente todos los días a las 2:00 AM mediante una tarea programada.

### 3. Cache Inteligente
- El sitemap en vivo se regenera automáticamente si tiene más de 24 horas
- El archivo robots.txt usa cache de 24 horas para mejor rendimiento

## Comandos Disponibles

### Generar Sitemap Manualmente
```bash
cd mytech
php artisan sitemap:generate
```

Esto generará el archivo `public/sitemap.xml` con todas las URLs del sitio.

### Ver Tareas Programadas
```bash
cd mytech
php artisan schedule:list
```

### Ejecutar Tareas Programadas Manualmente (para testing)
```bash
cd mytech
php artisan schedule:run
```

## URLs Generadas

### URLs Estáticas
- `/` (Prioridad: 1.0, Frecuencia: daily)
- `/servicios` (Prioridad: 0.9, Frecuencia: weekly)
- `/proyectos` (Prioridad: 0.9, Frecuencia: weekly)
- `/sobre-nosotros` (Prioridad: 0.7, Frecuencia: monthly)
- `/contacto` (Prioridad: 0.8, Frecuencia: monthly)

### URLs Dinámicas
- `/proyectos/{slug}` - Generado automáticamente para cada proyecto activo
  - Prioridad: 0.7
  - Frecuencia: monthly
  - Fecha de modificación: basada en `updated_at` del proyecto

## Acceso a los Archivos

### Sitemap
```
https://tudominio.com/sitemap.xml
```

### Robots.txt
```
https://tudominio.com/robots.txt
```

El archivo robots.txt incluye automáticamente la referencia al sitemap y bloquea rutas administrativas:
- `/admin`
- `/dashboard`
- `/login`
- `/register`
- `/password`
- etc.

## Configuración de Tarea Programada en Servidor

Para que la regeneración automática funcione en producción, debes configurar un cronjob en tu servidor:

### 1. Editar el crontab
```bash
crontab -e
```

### 2. Agregar esta línea
```bash
* * * * * cd /ruta/a/tu/proyecto/mytech && php artisan schedule:run >> /dev/null 2>&1
```

Esto ejecutará el scheduler de Laravel cada minuto, y Laravel se encargará de ejecutar las tareas programadas en el momento correcto.

## Cómo Funciona con Proyectos Dinámicos

### Ejemplo de Flujo

1. **Creas un nuevo proyecto en el admin:**
   - Nombre: "Aplicación Mobile Banking"
   - Slug: `aplicacion-mobile-banking`

2. **El sitemap se regenera automáticamente:**
   - Al acceder a `/sitemap.xml`, el sistema detecta que el sitemap tiene más de 24 horas
   - Se ejecuta `sitemap:generate` automáticamente
   - Se agrega la nueva URL: `/proyectos/aplicacion-mobile-banking`

3. **Los motores de búsqueda indexan tu proyecto:**
   - Google y otros buscadores revisan tu sitemap regularmente
   - Descubren y indexan tu nuevo proyecto automáticamente

## Personalización

### Agregar Más URLs Estáticas

Edita el archivo `app/Console/Commands/GenerateSitemap.php`, método `addStaticUrls()`:

```php
protected function addStaticUrls(Sitemap $sitemap): void
{
    $staticUrls = [
        // ... URLs existentes
        ['url' => '/nueva-pagina', 'priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_WEEKLY],
    ];
    // ...
}
```

### Cambiar la Frecuencia de Regeneración

Edita `bootstrap/app.php`:

```php
->withSchedule(function (Schedule $schedule) {
    $schedule->command('sitemap:generate')
        ->weekly() // Cambiar a weekly, hourly, etc.
        ->at('02:00');
})
```

### Modificar Prioridades de Proyectos

En `app/Console/Commands/GenerateSitemap.php`, método `addProyectos()`:

```php
protected function addProyectos(Sitemap $sitemap): void
{
    $proyectos = Proyecto::activos()->get();

    foreach ($proyectos as $proyecto) {
        $priority = $proyecto->destacado ? 0.9 : 0.7; // Mayor prioridad para proyectos destacados

        $sitemap->add(
            Url::create('/proyectos/' . $proyecto->slug)
                ->setLastModificationDate($proyecto->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority($priority)
        );
    }
}
```

## Verificación del Sitemap

### Validar XML
Puedes validar tu sitemap en:
- [XML Sitemaps Validator](https://www.xml-sitemaps.com/validate-xml-sitemap.html)
- [Google Search Console](https://search.google.com/search-console)

### Google Search Console
1. Ve a [Google Search Console](https://search.google.com/search-console)
2. Selecciona tu propiedad
3. Ve a "Sitemaps" en el menú lateral
4. Agrega la URL: `https://tudominio.com/sitemap.xml`
5. Click en "Enviar"

## Archivos Modificados

- `app/Console/Commands/GenerateSitemap.php` - Comando principal de generación
- `app/Http/Controllers/SitemapController.php` - Controlador para servir sitemap y robots.txt
- `bootstrap/app.php` - Configuración de tareas programadas
- `routes/web.php` - Ya contiene las rutas `/sitemap.xml` y `/robots.txt`

## Beneficios SEO

1. **Indexación Rápida**: Los nuevos proyectos se indexan automáticamente
2. **Mejor Rastreo**: Los motores de búsqueda conocen todas tus URLs importantes
3. **Priorización**: Las páginas importantes tienen mayor prioridad
4. **Actualización Inteligente**: El lastmod indica cuándo se actualizó cada página
5. **Robots.txt Optimizado**: Bloquea áreas administrativas y guía a los crawlers

## Solución de Problemas

### El sitemap no se regenera automáticamente
Verifica que el cronjob esté configurado correctamente:
```bash
crontab -l
```

### No aparecen los proyectos nuevos
Regenera manualmente el sitemap:
```bash
php artisan sitemap:generate
```

### Error de permisos
Asegúrate de que Laravel pueda escribir en la carpeta `public/`:
```bash
chmod -R 775 public/
```

## Soporte

Para más información sobre la librería spatie/laravel-sitemap:
- [Documentación oficial](https://github.com/spatie/laravel-sitemap)
- [Documentación de Google sobre sitemaps](https://developers.google.com/search/docs/crawling-indexing/sitemaps/overview)
