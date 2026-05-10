<?php

namespace App\Mail;

use App\Models\Alumno;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RiesgoNotaPPEMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Alumno $alumno,
        public readonly float  $notaActual,
        public readonly float  $notaMinima,
        public readonly float  $horasNecesarias,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "[PPE] Alerta: nota en riesgo — {$this->alumno->nombre_completo}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.riesgo_nota_ppe');
    }
}
