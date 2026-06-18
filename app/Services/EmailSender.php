<?php

namespace App\Services;

use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailSender
{
    /**
     * Envía hasta $limit correos pendientes (tanda). Devuelve cuántos procesó.
     * Se llama tanto al redactar (primera tanda) como desde el programador cada minuto.
     */
    public function sendBatch(int $limit = 10): int
    {
        $pendientes = Email::where('estado', 'pendiente')->orderBy('id')->limit($limit)->get();

        $procesados = 0;
        foreach ($pendientes as $email) {
            $this->sendOne($email);
            $procesados++;
        }

        return $procesados;
    }

    public function sendOne(Email $email): void
    {
        try {
            Mail::html($email->cuerpo, function ($message) use ($email) {
                $message->to($email->para)->subject($email->asunto);

                foreach (($email->adjuntos ?? []) as $ruta) {
                    if (Storage::disk('local')->exists($ruta)) {
                        $message->attach(Storage::disk('local')->path($ruta));
                    }
                }
            });

            $email->update(['estado' => 'enviado', 'sent_at' => now(), 'error' => null]);
        } catch (\Throwable $e) {
            $email->update(['estado' => 'fallido', 'error' => mb_substr($e->getMessage(), 0, 1000)]);
        }
    }
}
