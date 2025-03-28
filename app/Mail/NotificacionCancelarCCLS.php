<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionCancelarCCLS extends Mailable
{
    use Queueable, SerializesModels;
    public $cancelarCita;
    public $correoInvalido;

    public $subject = 'Cita cancelada';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cancelarCita, $correoInvalido = false)
    {
        $this->cancelarCita = $cancelarCita;
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
