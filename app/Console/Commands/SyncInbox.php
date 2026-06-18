<?php

namespace App\Console\Commands;

use App\Models\CorreoRecibido;
use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;

class SyncInbox extends Command
{
    protected $signature = 'emails:sync-inbox {--limit=25}';

    protected $description = 'Sincroniza los correos recibidos (IMAP) a la base de datos.';

    public function handle(): int
    {
        @set_time_limit(0);

        try {
            $client = Client::account('default');
            $client->connect();
            $inbox = $client->getFolder('INBOX');
            $msgs = $inbox->query()->all()
                ->setFetchOrder('desc')->setFetchBody(false)
                ->limit((int) $this->option('limit'))->get();
        } catch (\Throwable $e) {
            $this->error('No se pudo conectar al IMAP: '.$e->getMessage());

            return self::FAILURE;
        }

        $nuevos = 0;
        foreach ($msgs as $m) {
            $from = $m->getFrom();

            try {
                $fecha = $m->getDate()->toDate();
            } catch (\Throwable $e) {
                $fecha = null;
            }

            $visto = in_array('seen', array_map('strtolower', $m->getFlags()->all()));

            $registro = CorreoRecibido::updateOrCreate(
                ['uid' => $m->getUid()],
                [
                    'de' => $from[0]->mail ?? '',
                    'nombre' => trim(mb_decode_mimeheader($from[0]->personal ?? '')) ?: ($from[0]->mail ?? ''),
                    'asunto' => trim(mb_decode_mimeheader($m->getSubject() ?? '')) ?: '(sin asunto)',
                    'fecha' => $fecha,
                    'visto' => $visto,
                    'synced_at' => now(),
                ]
            );

            if ($registro->wasRecentlyCreated) {
                $nuevos++;
            }
        }

        $this->info("Bandeja sincronizada. Correos nuevos: {$nuevos}");

        return self::SUCCESS;
    }
}
