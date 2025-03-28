<!DOCTYPE html>
<style>
    table, th, td {
        border: 2px solid black;
        border-collapse: collapse;
        padding: 5px;
    }

    th {
        text-align: left;
    }
</style>
<html>
    <head>        
        <h3>Estimado/a {{ucfirst($cancelarCita->nombre)}} {{ucfirst($cancelarCita->apellidos)}}</h3>
        <p>Le informamos que su cita ha sido cancelada debido a que la oficina fue cerrada.</p>
    </head>
    <body>
        <table border="1">
            <thead>
                <tr>
                    <th align="left">Folio de la cita:</th>
                    <td>{{$cancelarCita->cita_folio}}</td>
                    <th align="left">Fecha de Registro:</th>
                    <td>{{date("d-m-Y", strtotime($cancelarCita->cita_fecha))}}</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>                    
                </tr>
            </thead>
            <tbody>                                
                <tr>
                    <th align="left">Solicitante:</th>
                    <td colspan="3">{{ucfirst($cancelarCita->nombre)}} {{ucfirst($cancelarCita->apellidos)}}</td>
                </tr>
                <tr>
                    <th align="left">Motivo:</th>
                    <td colspan="3">{{ucfirst($cancelarCita->observaciones)}}</td>
                </tr>
                <tr>
                    <th align="left">Fecha y hora de la cita:</th>
                    <td colspan="3">{{date("d-m-Y", strtotime($cancelarCita->cita_fecha))}} a las {{Str::substr($cancelarCita->cita_hora,0,5)}} horas</td>
                </tr>
                <tr>
                    <th align="left">Fecha y hora de cancelación:</th>
                    <td colspan="3">{{ date("d-m-Y", strtotime(now())) }} a las {{ date("H:i", strtotime(now())) }} horas</td>
                </tr>
                <tr>
                    <th align="left">Causa de cancelación:</th>
                    <td colspan="3">La oficina fue dada de baja, lamentamos las molestias que podamos causar.</td>
                </tr>
            </tbody>
        </table>
        @if($correoInvalido)
            <p>No fue posible entregar a la dirección registrada por el usuario: {{ $cancelarCita->correo }}.</p>
        @endif
    </body>
    <footer>
        <br>
        <img src="{{ $message->embed(public_path() . '/images/ccls/logo.png') }}" alt="ccls" width="200"/>
    </footer>
</html>