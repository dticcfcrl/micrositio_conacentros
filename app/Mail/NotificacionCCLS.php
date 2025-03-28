<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Illuminate\Support\Carbon;
use DateTime;



class NotificacionCCLS extends Mailable
{
    use Queueable, SerializesModels;
    public $datosCita;
    public $folio;
    public $datosOficina;
    public $correoInvalido;
    public $correoReenviar;
    
    public $subject = 'Datos de confirmación para cita';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datosCita, $folio, $datosOficina, $correoInvalido=false, $correoReenviar=null)
    {
        $this->datosCita = $datosCita;
        $this->folio = $folio;
        $this->datosOficina = $datosOficina;
        $this->correoInvalido = $correoInvalido;
        $this->correoReenviar = $correoReenviar;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Crear el evento .ics
        $fechaEnd = Carbon::parse($this->datosCita['cclFecha'])->format('Y-m-d');
        $fechaEndH = Carbon::parse($fechaEnd . " " .$this->datosCita['cclHora'])->format('Y-m-d h:m');

        $event = Event::create()
            ->name("Cita en el centro de conciliación laboral")
            ->description( "Oficina: ". $this->datosOficina[0]->estado)
            ->startsAt( Carbon::parse($this->datosCita['cclFecha']) )
            ->endsAt( new DateTime($fechaEndH) )
            ->address($this->datosOficina[0]->direccion)
            ->organizer("CCL");

        $calendar = Calendar::create()
            ->event($event);

        $icsContent = $calendar->get();

        // Guardar el archivo .ics temporalmente
        $filePath = tempnam(sys_get_temp_dir(), 'event') . '.ics';
        file_put_contents($filePath, $icsContent);

        return $this->view('public.emails.notificacion-reserva')->attach($filePath, ['as' => 'event.ics', 'mime' => 'text/calendar']);

    }
}
