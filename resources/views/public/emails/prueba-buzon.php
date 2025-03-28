<!DOCTYPE html>
<style>
    p {
        text-align: center;
    }
</style>
<html>
    <head>
        <h3>Prueba de validación para el correo</h3>
    </head>

    <body>
        <p>Mensaje de prueba para la comprobación del correo. Hacer caso omiso.</p>
    </body>
    <footer>
        <br>
        <img src="{{ $message->embed(public_path() . '/images/ccls/logo.png') }}" alt="ccls" width="200"/>
    </footer>
</html>