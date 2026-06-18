<?php

namespace App\Console\Commands;

use App\Services\EmailSender;
use Illuminate\Console\Command;

class SendPendingEmails extends Command
{
    protected $signature = 'emails:send-pending {--limit=10 : Cuántos enviar en esta tanda}';

    protected $description = 'Envía una tanda de correos pendientes (throttling anti-spam)';

    public function handle(EmailSender $sender): int
    {
        $n = $sender->sendBatch((int) $this->option('limit'));
        $this->info("Correos procesados en esta tanda: {$n}");

        return self::SUCCESS;
    }
}
