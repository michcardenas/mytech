<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Headers de seguridad en TODAS las respuestas (web + api)
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Aliases de Spatie Permission para proteger rutas (role/permission)
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'api.key'            => \App\Http\Middleware\ApiKeyAuth::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Regenerar sitemap diariamente a las 2:00 AM
        $schedule->command('sitemap:generate')
            ->daily()
            ->at('02:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Enviar tandas de correos pendientes (anti-spam: ~10/min)
        $schedule->command('emails:send-pending')
            ->everyMinute()
            ->withoutOverlapping();

        // Sincronizar la bandeja de entrada (IMAP -> BD) cada 10 minutos
        $schedule->command('emails:sync-inbox')
            ->everyTenMinutes()
            ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
