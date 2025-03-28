<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionCitaAccionCCLS extends Mailable
{
    use Queueable, SerializesModels;
    public $datosCita;
    public $mensaje;
    public $correoInvalido;

    public $subject = 'Información acerca de su cita';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datosCita, $mensaje, $correoInvalido=false)
    {
        $this->datosCita = $datosCita;
        $this->mensaje = $mensaje; 
        $this->correoInvalido = $correoInvalido;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('public.emails.notificacion-reserva-accion');
    }
}
