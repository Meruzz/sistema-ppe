<?php

namespace App\Mail;

use App\Models\Alumno;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProgresoPPEMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Alumno $alumno,
        public readonly int    $porcentaje,   // 50 | 80 | 100
        public readonly float  $horasCompletadas,
        public readonly int    $meta,
    ) {}

    public function envelope(): Envelope
    {
        $hito = match ($this->porcentaje) {
            50  => '50 % de horas completadas',
            80  => '80 % de horas completadas',
            100 => 'Programa PPE completado',
            default => "{$this->porcentaje}% de horas",
        };

        return new Envelope(subject: "[PPE] {$hito} — {$this->alumno->nombre_completo}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.progreso_ppe');
    }
}
