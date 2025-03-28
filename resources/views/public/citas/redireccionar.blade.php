<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirección</title>
</head>
<body>
    <form id="autoPostForm" action="/herramientas/consultar-cita" method="POST">
        @csrf
        <input type="hidden" name="error_codigo" value="{{ $error_codigo }}">
        <input type="hidden" name="mensaje" value="{{ $mensaje }}">
        <input type="hidden" name="folio" value="{{ $folio }}">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const folio = document.querySelector('input[name="folio"]').value;

            if (!folio || folio.trim() === "") {
                window.location.href = '/herramientas/calculadora'; 
            } else {
                document.getElementById('autoPostForm').submit();
            }
        });
    </script>
</body>
</html>