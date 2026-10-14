<?php

namespace App\Services;

use App\Mail\AsignacionDevMail;
use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\ProjectTask;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Envía avisos por correo al desarrollador cuando se le asigna un proyecto
 * interno o una tarea del tablero. El envío es tolerante a fallos: si el
 * correo no sale, se registra en el log pero NO interrumpe el guardado.
 */
class DevNotifier
{
    public function proyectoAsignado(InternalProject $project): void
    {
        $email = $project->desarrollador_email;
        if (! $email && $project->developer_id) {
            $email = optional(Developer::find($project->developer_id))->email;
        }
        if (! $email) {
            return;
        }

        $detalles = array_filter([
            'Cliente' => $project->cliente_nombre,
            'Estado' => $project->estado_label,
            'Entrega' => optional($project->fecha_entrega)->format('d/m/Y'),
        ]);

        $this->enviar($email, new AsignacionDevMail(
            devNombre: $project->desarrollador_nombre ?: 'desarrollador',
            tipo: 'proyecto',
            titulo: $project->nombre,
            detalles: $detalles,
            url: $this->portalUrl(),
        ));
    }

    public function tareaAsignada(ProjectTask $task): void
    {
        $dev = $task->developer_id ? Developer::find($task->developer_id) : null;
        if (! $dev || ! $dev->email) {
            return;
        }

        $task->loadMissing('project');

        $detalles = array_filter([
            'Proyecto' => optional($task->project)->nombre,
            'Prioridad' => ucfirst((string) $task->prioridad),
            'Fecha límite' => optional($task->fecha_limite)->format('d/m/Y'),
        ]);

        $this->enviar($dev->email, new AsignacionDevMail(
            devNombre: $dev->nombre,
            tipo: 'tarea',
            titulo: $task->titulo,
            detalles: $detalles,
            url: $this->portalUrl(),
        ));
    }

    private function portalUrl(): ?string
    {
        try {
            return route('portal.developer.login.show');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function enviar(string $email, AsignacionDevMail $mail): void
    {
        try {
            Mail::to($email)->send($mail);
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar el correo de asignación al desarrollador.', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
