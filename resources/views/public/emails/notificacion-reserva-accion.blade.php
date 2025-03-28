<!DOCTYPE html>
<style>
    p {
        text-align: center;
    }
</style>
<html>
    <head>
        <h3>Estimado/a {{ucfirst($datosCita['accionNombre'])}} {{ucfirst($datosCita['accionApellidos'])}}</h3>
        <p>Le informamos que su cita ha sido {{$mensaje}} por el conciliador.</p>
    </head>

    <body>
        <table border="1">
            <thead>
                <tr>
                    <th align="left">Folio de la cita:</th>
                    <td>{{$datosCita['accionFolio']}}</td>
                    <th align="left">Fecha de Registro:</th>
                    <td>{{date("d-m-Y", strtotime($datosCita['accionFecha']))}}</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>                    
                </tr>
            </thead>
            <tbody>                                
                <tr>
                    <th align="left">Solicitante:</th>
                    <td colspan="3">{{ucfirst($datosCita['accionNombre'])}} {{ucfirst($datosCita['accionApellidos'])}}</td>
                </tr>
                <tr>
                    <th align="left">Motivo:</th>
                    <td colspan="3">{{ucfirst($datosCita['accionCita'])}}</td>
                </tr>
                <tr>
                    <th align="left">Fecha y hora de la cita:</th>
                    <td colspan="3">{{date("d-m-Y", strtotime($datosCita['accionFecha']))}} a las {{Str::substr($datosCita['accionHora'],0,5)}} horas</td>
                </tr>
            </tbody>
        </table>
        @if($correoInvalido)
            <p>No fue posible entregar el correo de cancelación de cita a la dirección registrada por el usuario: {{ $datosCita['accionCorreo'] }}.</p>
        @endif
    </body>
    <footer>
        <br>
        <img src="{{ $message->embed(public_path() . '/images/ccls/logo.png') }}" alt="ccls" width="200"/>
    </footer>
</html>