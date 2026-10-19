<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\User;
use App\Services\DevNotifier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Agrega al proyecto EXISTENTE "kinvo" (cliente Karla, dev Yeniffer Palomino)
 * la tarea del fallo de pagos en produccion + sus 5 subtareas.
 *
 * NO crea el proyecto: lo resuelve por nombre. Idempotente: si ya existe una
 * tarea con el mismo titulo en el proyecto, no la vuelve a crear.
 *
 * Imagenes (opcional): si colocas 1.png / 2.png / 3.png en
 *   storage/app/kinvo-pago/   (en el servidor de PROD)
 * las adjunta a la tarjeta; si no estan, avisa y las subes por el panel.
 *
 * Ejecutar en PROD:  php artisan db:seed --class=KinvoPagosProdSeeder
 */
class KinvoPagosProdSeeder extends Seeder
{
    /** Pon false si NO quieres que se le envie el correo de aviso a Yeniffer. */
    private const NOTIFICAR = true;

    private const PROYECTO = 'kinvo';
    private const DEV = 'Yeniffer Palomino';

    private const TITULO = 'Fallo de pagos en produccion: todas las tarjetas rechazadas (revisar token del admin)';

    public function run(): void
    {
        $project = InternalProject::where('nombre', self::PROYECTO)->first();
        if (! $project) {
            $this->command?->error("No existe el proyecto «".self::PROYECTO."». Abortado.");

            return;
        }

        $dev = Developer::where('nombre', self::DEV)->first();
        if (! $dev) {
            $this->command?->error("No existe el dev «".self::DEV."». Abortado.");

            return;
        }

        $adminId = User::role('admin')->value('id');

        $task = $project->tasks()->where('titulo', self::TITULO)->first();

        if ($task) {
            $this->command?->warn("La tarea ya existe (id {$task->id}). No se recrea.");
        } else {
            $project->equipo()->syncWithoutDetaching([$dev->id]);

            $task = $project->tasks()->create([
                'titulo' => self::TITULO,
                'descripcion' => $this->descripcion(),
                'columna' => 'por_hacer',
                'prioridad' => 'alta',
                'developer_id' => $dev->id,
                'fecha_limite' => '2026-10-21',
                'orden' => (int) $project->tasks()->where('columna', 'por_hacer')->max('orden') + 1,
                'created_by' => $adminId,
            ]);

            foreach (array_values($this->subtareas()) as $orden => $titulo) {
                $task->subtasks()->create([
                    'titulo' => $titulo,
                    'developer_id' => $dev->id,
                    'hecha' => false,
                    'orden' => $orden,
                ]);
            }

            $this->command?->info("Tarea id {$task->id} creada con ".$task->subtasks()->count()." subtareas para ".self::DEV.".");

            if (self::NOTIFICAR) {
                app(DevNotifier::class)->tareasAsignadasLote($dev, $project, [$task->titulo]);
                $this->command?->info("Correo de aviso enviado a {$dev->email}.");
            }
        }

        $this->adjuntarImagenes($task);
    }

    private function adjuntarImagenes($task): void
    {
        $dir = storage_path('app/kinvo-pago');
        $mapa = [
            '1.png' => 'Error de pago - captura 1.png',
            '2.png' => 'Error de pago - captura 2.png',
            '3.png' => 'Error de pago - captura 3.png',
        ];

        $algo = false;
        foreach ($mapa as $archivo => $nombre) {
            $src = $dir.DIRECTORY_SEPARATOR.$archivo;
            if (! is_file($src)) {
                continue;
            }
            $algo = true;

            if ($task->files()->where('nombre', $nombre)->exists()) {
                $this->command?->warn("Ya adjunta: {$nombre}. Se omite.");

                continue;
            }

            $contents = file_get_contents($src);
            $path = 'internal-projects/'.$task->internal_project_id.'/tasks/'.$task->id.'/'.Str::random(40).'.png';
            Storage::disk('public')->put($path, $contents);

            $task->files()->create([
                'nombre' => $nombre,
                'archivo' => $path,
                'tipo' => 'image/png',
                'tamano' => strlen($contents),
                'subido_por' => 'Michael cardenas',
            ]);
            $this->command?->info("Adjuntada: {$nombre}.");
        }

        if (! $algo) {
            $this->command?->warn('No encontre imagenes en storage/app/kinvo-pago/ (1.png,2.png,3.png). Subelas por el panel.');
        }
    }

    private function descripcion(): string
    {
        return <<<TXT
Reporte del cliente (Karla, via Mytechsolution - 19/10/2026): siguio los pasos del documento tal cual e intento pagar con 3 tarjetas distintas (2 mexicanas, incl. Nu, y 1 extranjera). En las 3 sale el error de Mercado Pago: "Algo salio mal... No pudimos procesar tu pago. Usa un medio de pago distinto." Confirma que la cuenta esta en modo productivo.

Sospecha principal: la credencial/token de produccion en el usuario admin se guarda truncada - dice que la guarda pero el numero de caracteres no coincide (la pega larga y queda corta). Validar que el access token se almacene completo y no se recorte.

Evidencia: Video (Drive): https://drive.google.com/file/d/1hgqUcRJv9OKEC2S6V17qwCCbI4hiF-R3/view?usp=sharing - 3 capturas del error (adjuntas en el tablero).

URLs: prod https://kinvo.com - staging https://staging.kinvo.com - repo https://github.com/mytech/kinvo
TXT;
    }

    /** @return array<int,string> */
    private function subtareas(): array
    {
        return [
            'Verificar en el admin que el access token de produccion se guarda completo (longitud pegada vs almacenada en BD)',
            'Revisar el campo del token en BD/migracion por limite de longitud que lo trunque (varchar corto)',
            'Confirmar credenciales productivas correctas en Mercado Pago (public key + access token del mismo entorno)',
            'Reproducir el pago en staging con las credenciales corregidas y validar aprobacion',
            'Avisar a Karla una vez corregido',
        ];
    }
}
