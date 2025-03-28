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
        <h3>Estimado/a {{ucfirst($datosCita['cclNombre'])}} {{ucfirst($datosCita['cclApellidos'])}}</h3>
        <p>Por este medio se envía su comprobante de Cita que deberá presentar en el Centro de Conciliación correspondiente.</p>
    </head>    
    <body>
        <table border="1">
            <thead>
                <tr>
                    <th align="left">Folio de la cita:</th>
                    <td>{{$folio}}</td>
                    <th align="left">{{ $datosCita['tipoCita'] == '0' ? 'Fecha de Registro:' : 'Fecha de Cambio:' }} </th>
                    <td>{{date('Y-m-d')}}</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>                    
                </tr>
            </thead>
            <tbody>                                
                <tr>
                    <th align="left">Solicitante:</th>
                    <td colspan="3">{{ucfirst($datosCita['cclNombre'])}} {{ucfirst($datosCita['cclApellidos'])}}</td>
                </tr>
                <tr>
                    <th align="left">Tipo de caso:</th>
                    <td colspan="3">{{ucfirst($datosCita['cclObservaciones'])}}</td>
                </tr>
                <tr>
                    <th align="left">{{ $datosCita['tipoCita'] == '0' ? 'Fecha y hora de la cita:' : 'Nueva fecha y hora de la cita:' }}</th>
                    <td colspan="3">{{$datosCita['cclFecha']}} a las {{Str::substr($datosCita['cclHora'],0,5)}} horas</td>
                </tr>
                <tr>
                    <th align="left">Centro de conciliación (Local/Federal)</th>
                    <td colspan="3">
                        {{$datosOficina[0]->estado}} {{$datosOficina[0]->direccion}}
                    </td>
                </tr>
            </tbody>
        </table>
        @if($correoInvalido)
            <p>No fue posible entregar el correo de confirmación a la dirección registrada por el usuario: {{ $datosCita['cclCorreo'] }}.</p>
        @endif
        @if($correoReenviar)
            <p>Se ha actualizado el correo de contacto a: {{ $correoReenviar }}.</p>
        @endif
    </body>
    <footer>
        <br>
        <img src="{{ $message->embed(public_path() . '/images/ccls/logo.png') }}" alt="ccls" width="200"/>
    </footer>
</html>