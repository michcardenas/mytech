<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AsignacionDevMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, string>  $detalles  Pares etiqueta => valor para la tarjeta de detalles.
     */
    public function __construct(
        public string $devNombre,
        public string $tipo,   // 'proyecto' | 'tarea'
        public string $titulo,
        public array $detalles = [],
        public ?string $url = null,
    ) {}

    public function build(): self
    {
        $asunto = $this->tipo === 'tarea'
            ? 'Nueva tarea asignada: '.$this->titulo
            : 'Nuevo proyecto asignado: '.$this->titulo;

        return $this->subject($asunto)->view('emails.asignacion');
    }
}
