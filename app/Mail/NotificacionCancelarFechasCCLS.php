<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionCancelarFechasCCLS extends Mailable
{
    use Queueable, SerializesModels;
    public $cancelarCita;
    public $motivo;
    public $correoInvalido;

    public $subject = 'Cita cancelada por causa mayor';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cancelarCita, $motivo, $correoInvalido = false)
    {        
        $this->cancelarCita = $cancelarCita;
        $this->motivo = $motivo;
        $this->correoInvalido = $correoInvalido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('public.emails.notificacion-cancelar');
    }
}
