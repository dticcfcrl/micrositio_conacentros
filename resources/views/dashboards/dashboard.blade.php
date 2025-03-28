<x-app-layout  layout="dashboardv" :assets="$assets ?? []">
   @include('partials.dashboard.sub-header')
   @can('dashboard')
   <div class="iq-navbar-header" style="height: auto;">
      <div class="container-fluid">
         <div class="row">
            @if(auth()->user()->perfil === 'superusuario')
               @include('partials.dashboard.perfiles._dash_superusuario');
            @endif
            @if(auth()->user()->perfil === 'administrador')
               @include('partials.dashboard.perfiles._dash_administrador');
            @endif
            @if(auth()->user()->perfil === 'conciliador')
               @include('partials.dashboard.perfiles._dash_conciliador');
            @endif
         </div>
      </div>
   </div>
   @endcan

   <script src="{{ asset('js/leaflet.js')}}"></script>

   <script>

      let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.

      // Variables axuiliares
      let disponbilidadOficina = @json($disponbilidadOficina);
      let tConciliadores = @json($conciliadores);
      let tAuxiliar = @json($auxiliares);
      let usuario = @json(auth()->user()->perfil);

       //Logica de checkbox
      /* const checkTodos = document.getElementById('SelecionarTodo'); //btn todos */
      let checkLunes = document.getElementById('_checklunes'); //btn dias
      let checkMartes = document.getElementById('_checkmartes'); //btn dias
      let checkMiercoles = document.getElementById('_checkmiércoles'); //btn dias
      let checkJueves = document.getElementById('_checkjueves'); //btn dias
      let checkViernes = document.getElementById('_checkviernes'); //btn dias
      let _meses = document.getElementById('_meses'); // meses a mostrar
      let _horario = document.getElementById('_horarios'); // horarios 
      let _horarioFin = document.getElementById('_horarioFin'); // horarios 
      let _minutos = document.getElementById('_minutos'); // Minutos por cita
      let _conciliadores = document.getElementById('_conciliadores'); //Conciliadores máximos permitidos
      let _auxiliares = document.getElementById('_auxiliares'); //Auxiliares máximos permitidos

      if(disponbilidadOficina != null && usuario == 'administrador'){
         checkLunes.checked = disponbilidadOficina.status_lunes;
         checkMartes.checked = disponbilidadOficina.status_martes;
         checkMiercoles.checked = disponbilidadOficina.status_miercoles;
         checkJueves.checked = disponbilidadOficina.status_jueves;
         checkViernes.checked = disponbilidadOficina.status_viernes;

         _meses.innerHTML = disponbilidadOficina.meses_cita;
         _horario.innerHTML = (disponbilidadOficina.hora_cita_inicio).substr(0,5) + ' a ' + (disponbilidadOficina.hora_comida_inicio).substr(0,5) + 'hrs.';
         _horarioFin.innerHTML = (disponbilidadOficina.hora_comida_fin).substr(0,5) + ' a ' + (disponbilidadOficina.hora_cita_fin).substr(0,5) + 'hrs.';
         _minutos.innerHTML = disponbilidadOficina.minutos_cita + ' min.';
         _conciliadores.innerHTML = tConciliadores + ' de ' + disponbilidadOficina.total_conciliadores;
         _auxiliares.innerHTML = tAuxiliar + ' de ' + disponbilidadOficina.total_auxiliares

      }else if(usuario == 'administrador'){

         _meses.innerHTML = 'Sin configurar';
         _horario.innerHTML = 'Sin configurar';
         _horarioFin.innerHTML = 'Sin configurar';
         _minutos.innerHTML = 'Sin configurar';
         _conciliadores.innerHTML = 'Sin configurar';
         _auxiliares.innerHTML = 'Sin configurar';
         
      }      

      /* Validamos los caraceteres máximos permitidos */
      function valMotivo(caracteres){            
         let len = caracteres.value.length;
         document.getElementById('_carRestantes').innerHTML = 'Max: ' + len + '/128';
      }

      // Invocamos mapa
      viewMap();

      /*
      Busqueda usando mapa
      */
      function viewMap(){

         let lat = 23.634501;
         let long = -102.552784;

         let customIcon = {
            iconUrl: "/assets/contenidos/iconos/edificio.png",
            iconSize:[25,25],
         }

         let iconoPin = L.icon(customIcon);

         // Configuramos pin perzonalizado
         let iconOptions = {
            title:"ccls",
            draggable:false,
            icon:iconoPin,
            clickable: false,
         }

         let mapContainer = document.getElementById('map-container');

         @foreach ($cclsUbicaciones as $ccl)
            @if($ccl->id == auth()->user()->id_ccls)
               lat = {{$ccl->lat}};
               lon = {{$ccl->long}};
            @endif
         @endforeach

         map = L.map(mapContainer).setView([lat, lon], 15);
         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
         }).addTo(map);                
         marker = new L.Marker([lat, lon], iconOptions).addTo(map);

      }

      //función para cancelar citas del día y hora en adelante
      function cancelarCitas(){         

         Swal.fire({
            title: '¿Está seguro?',
            text: 'A partir de este momento, se cancelarán todas las citas del día y se notificarán a los usuarios vía correo.',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: alertasColor,
            cancelButtonColor: "#6C757D",
            confirmButtonText: "Sí",
            cancelButtonText: "No",
            reverseButtons: true,
            iconColor: alertasColor,
            showClass: {
            popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
            },
            customClass: {
               confirmButton: "btn-primary-ccls",
               cancelButton: "btn-info-ccls"
            },

         }).then((result) => {

            if (result.isConfirmed) {
               mensajeProcesando();
               cancelarCitasRegistradas();
            }else{
               location.reload();
            }

         });
      }

      /* Función para cancelar citas del día y a patir de la hora de solicitud */
      function cancelarCitasRegistradas(){

         // obtenemos el motivo de cancelación
         let motivo = document.getElementById('_motivoCancelarCitas').value;

         const token = document.querySelector('meta[name="csrf-token"]').content; //Obtenemos el token

         //Validamos dias
         fetch("{{route('cancelarcitasdia')}}",
         {
            method: "POST",            
            body: JSON.stringify(motivo),
            headers: {
                'Content-Type': 'application/json',
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token":  token // Pasamos el token CSRF de la etiqueta meta 
            },
            credentials: "same-origin",
         }).then(res => {
            return res.json();
         }).then(function (res){            
            if(res.status === 200 && res.data > 0){
               Swal.fire({
                  title: "Se han cancelado todas las citas",
                  text: "Citas canceladas del día: " + res.data,
                  icon: "success",
                  confirmButtonColor: alertasColor,
                  allowEnterKey: true,
                  allowOutsideClick: true,
                  confirmButtonText: "Aceptar",
                  iconColor: alertasColor,
               }).then((result) => {                    
                  if (result.isConfirmed) {
                     window.location.href = "/dashboard";
                  }
               });
            }else if(res.status === 200 && res.data === 0){
               Swal.fire({
                  title: "No hay citas por cancelar",
                  icon: "success",
                  confirmButtonColor: alertasColor,
                  allowEnterKey: true,
                  allowOutsideClick: true,
                  confirmButtonText: "Aceptar",
                  iconColor: alertasColor,
               }).then((result) => {
                  if (result.isConfirmed) {
                     window.location.href = "/dashboard";
                  }
               });
            }else{
               Swal.fire({
                  title: "Opps!",
                  text: 'No fue posible cancelar citas, intete más tarde.',
                  icon: "error",
                  confirmButtonColor: alertasColor,
                  allowEnterKey: true,
                  allowOutsideClick: true,
                  confirmButtonText: "Aceptar",
                  iconColor: alertasColor,
               }).then((result) => {                    
                  if (result.isConfirmed) {
                     window.location.href = "/dashboard";
                  }
               });
            }
         });
      }

      /*mensajes de espera*/
      function mensajeProcesando(){
         Swal.fire({
               title: "Procesando",
               text: "Espere, por favor",
               icon: "info",
               iconColor: alertasColor,
               allowEnterKey: false,
               allowOutsideClick: false,
               showClass: {
               popup: 'animate__animated animate__fadeInDown'
               }                
         });
         Swal.showLoading();
      }

   </script>

</x-app-layout>