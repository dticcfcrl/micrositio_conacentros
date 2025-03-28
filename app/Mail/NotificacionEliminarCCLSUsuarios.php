<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionEliminarCCLSUsuarios extends Mailable
{
    use Queueable, SerializesModels;

    public $cancelarCCLS;
    public $correoInvalido;

    public $subject = 'Notificación: Oficina cerrada permanentemente.';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cancelarCCLS, $correoInvalido=false)
    {

        $this->cancelarCCLS = $cancelarCCLS;
        $this->correoInvalido = $correoInvalido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('public.emails.notificacion-eliminar-oficina-usuarios');
    }
}
