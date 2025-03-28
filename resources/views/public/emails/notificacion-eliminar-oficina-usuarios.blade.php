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
        <h3>Estimado/a {{ucfirst($cancelarCCLS->nombre)}} {{ucfirst($cancelarCCLS->apellidos)}}</h3>
        <p>Le informamos que la oficina fue cerrada.</p>
    </head>
    <body>
        <p>Contacte a la administración para cualquier aclaración.</p>
        @if($correoInvalido)
        <p>No fue posible entregar el correo a la dirección registrada por el usuario: {{ $cancelarCCLS->email }}.</p>
        @endif
    </body>
    <footer>
        <br>
        <img src="{{ $message->embed(public_path() . '/images/ccls/logo.png') }}" alt="ccls" width="200"/>
    </footer>
</html>