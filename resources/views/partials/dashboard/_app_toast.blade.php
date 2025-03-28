<script type="text/javascript">
    {{-- Success Message --}}
    @if (Session::has('success'))
    Swal.fire({
    icon: 'success',
    iconColor: '#C04F15',
    title: 'Muy bien',
    text: '{{ Session::get("success") }}',
    confirmButtonColor: "#C04F15",
    confirmButtonText: "Aceptar",
    });
    @endif
    {{-- Errors Message --}}
    @if (Session::has('error'))
    Swal.fire({
    icon: 'error',
    title: 'Opps!!!',
    iconColor: '#C04F15',
    text: '{{Session::get("error")}}',
    confirmButtonColor: "#C04F15",
    confirmButtonText: "Aceptar",
    });
    @endif
    @if(Session::has('errors') || ( isset($errors) && is_array($errors) && $errors->any()))
    Swal.fire({
    icon: 'error',
    title: 'Opps!!!',
    iconColor: '#C04F15',
    text: '{{Session::get("errors")->first() }}',
    confirmButtonColor: "#C04F15",
    confirmButtonText: "Aceptar",
    });
    @endif
    @if (Session::has('info'))
    Swal.fire({
    icon: 'info',
    title: 'Opps!!!',
    iconColor: '#C04F15',
    text: '{{Session::get("info")}}',
    confirmButtonColor: "#C04F15",
    confirmButtonText: "Aceptar",
    });
    @endif
</script>