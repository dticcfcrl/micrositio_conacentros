<x-app-layout layout="dashboardv" :assets="$assets ?? []">  
    <div class="iq-navbar-header" style="height: auto;">
        <div class="container-fluid iq-container dorada">
            <div class="row">
              <div class="d-flex justify-content-between">                              
                <h3 class="card-title">Configurar vacaciones</h3>
              </div>
                <div class="col-lg-12 col-md-12 col-sm-12">                    
                    <div class="card px-0 col-md-12 text-muted">
                      <div class="container col-md-12 m-3 shadow p-3 mb-5 bg-body-tertiary rounded">
                        <div class="row">
                          <div class="col-md-4 my-2"><span style="background-color: #afc8ea; border: 1px solid black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Vacaciones asignadas</div>
                          <div class=" col-lg-6 col-md-12 col-sm-12" > 
                            <select name="usaurio" id="_usuario" class="col-12 form-select mb-3" onchange="obtenerVacacionesUsuario(this)">
                              <option value='0' selected> Seleccione usuario</option> 
                                @foreach ($usuarios as $usuario)
                                    <option value="{{$usuario->id}}">{{ucfirst($usuario->nombre)}}&nbsp;{{ucfirst($usuario->apellidos)}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                        <div id='calendar-vacaciones' style="width:50vw; margin: 0 auto;"></div>
                        @csrf                        
                        {{-- Obtenemos fecha selecionada desde fullCalendar --}}
                        <input id="fechaVacaciones" name="cclFecha" type="hidden" value="" />
                        <div class="d-flex">
                            <div class="p-2 flex-grow-1 justify-content-star text-center">
                                <button type="button" id="btnGuardar" class="btn btn-primary btn-primary-ccls rounded-pill" onclick="guardarFechas()">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
      {{-- Script para FullCalendar --}}
    <script>  
    
      let habilitaBoton = document.getElementById('btnGuardar');

      habilitaBoton.disabled = true;
  
      let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.
  
      var diasSelecionados = []; //Variable auxiliar para guardar los días seleccionados
  
      // función para calendario
      function mostrarVacaciones(data){

        Swal.close();

        habilitaBoton.disabled = false;

        var date = new Date(); //variable para selección de horarios
        var dateMes = new Date(); //variable para mostrar un mes calendario
        var datePlusMes = new Date(dateMes.setMonth(dateMes.getMonth() + 12));
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var calendarVacaciones = document.getElementById('calendar-vacaciones');
        var fechasDiponiblesEvents = [];
        let colorDefinido = '#FFFFFF';
        
        var fechasVacaciones = data;  //obtenemos las fechas guardadas
  
        /*recorremos las fechas guardadas*/
        fechasVacaciones.forEach(e => { 
          if(e.status == 1 ){
            colorDefinido = '#afc8ea';
          }else{
            colorDefinido = '#FFFFFF';
          }
          event = {
            /* title: 'Titulo', */
            start: e.fecha, 
            overlap: true,
            display: 'background',
            /* color: '#C04F15' */
            color: colorDefinido
          };
          fechasDiponiblesEvents.push(event);
        });
        
        var calendarVacaciones = new FullCalendar.Calendar(calendarVacaciones, {
  
          /* Rango de meses visibles */
          validRange: {
            start: date,
            end: datePlusMes
          },
  
          /*Días festivos y que no pueden agendar cita*/          
          events: fechasDiponiblesEvents,
  
          initialView: 'dayGridMonth', /*Vista mensual*/        
          selectable: true, /*Selecionar celda*/               
          locale: 'es', /* establecer idioma */        
          hiddenDays: [ 6, 0 ], /* esconder días de semana */        
          buttonText: {
            today: 'hoy',          
          }, /* cambiar texto de botones */                
          showNonCurrentDates: false, /*Muestra solo los días en calendario*/        
          fixedWeekCount: false, /*Muestra solo las semanas del mes*/
          /* Establecer color de dias selecionados */
          select:function(info){          
            diasSelecionados = [];      
            diaInicio = info.start; //obtenemos el día inicio
            diaInicioAxiliar = new Date(info.start); //Obtenemos el día inicio axiliar encargada de sumas días
            diaFin = info.end; //Obtenemos día último selección + 1
            diaFin = new Date(diaFin.setDate(diaFin.getDate() - 1)); //restar un día a fecha final por selección
            diferenciaTiempo = Math.abs(diaFin - diaInicio); //tomamos la diferencias de días en milisegundos
            diferenciaDias = Math.floor(diferenciaTiempo / (1000 * 60 * 60 * 24)); //obtenemos la diferencia en días              
            
            let fechaActual = obtenerFechaActual();
            
            diasSelecionados.push(info.startStr);
            
            for(let i = 0; i<diferenciaDias; i++){
              datePlus = new Date(diaInicioAxiliar.setDate(diaInicioAxiliar.getDate() + 1)); //Obtenemos la suma de un día después del prierm día seleccionado              
              diaPlus = datePlus.getDate();
              mesPlus = datePlus.getMonth() + 1;
              anioPlus = datePlus.getFullYear();

              if(diaPlus < 10){
                diaPlus = '0' + diaPlus;
              }
              if(mesPlus < 10){
                mesPlus = '0' + mesPlus;
              }

              if(datePlus.getDay() > 0 && datePlus.getDay() < 6){

                diaPlusStr = anioPlus + '-' + mesPlus + '-' + diaPlus;
                diasSelecionados.push(diaPlusStr);

              }
            }       
            
          },                
  
        });        
        
        calendarVacaciones.render();      
      };

      //Obtenemos fechas de vacaciones, en caso de tener
      function obtenerVacacionesUsuario(id){

        let usuario_id = id.value;

        if(usuario_id === '0'){
          Swal.fire({
            text: 'Seleccione usuario, por favor',
            icon: "info",
            confirmButtonColor: alertasColor,
            showConfirmButton: true,
            iconColor: alertasColor,
            confirmButtonText: "Aceptar",
            allowEnterKey: true,
            allowOutsideClick: true
          });
          habilitaBoton.disabled = true;
          document.getElementById('calendar-vacaciones').innerHTML = '';
          return
        }

         //Solicitud de guardado sin refrescar
         fetch(`../../../vacaciones/${usuario_id}`).then(res => {
  
            return res.json();
  
          }).then(function(res){
            
            mensajeProcesando();

            if(res.status === 200 && usuario_id > 0){

              mostrarVacaciones(res.data);

            }else{

              Swal.fire({
                title: "Opps!",
                text: 'No fue posible guardar la configuración, intente más tarde',
                icon: "error",
                confirmButtonColor: alertasColor,
                showConfirmButton: true,
                iconColor: alertasColor,
                confirmButtonText: "Aceptar",
                allowEnterKey: true,
                allowOutsideClick: true
              });

            }

          });

      }
  
      // Mensaje de guardado
      function guardarFechas(){
  
          mensajeProcesando();

          let id = document.getElementById('_usuario').value;

          const token = document.querySelector('meta[name="csrf-token"]').content; //Obtenemos el token

          let diasVacaciones = {
            'diasSelecionados': diasSelecionados,
            'usuario': id
          }
          
          //Solicitud de guardado sin refrescar
          fetch("{{route('guardarvacacionesfechas')}}",
          {
              method: "POST",
              body: JSON.stringify(diasVacaciones),
              headers: {
                  'Content-Type': 'application/json',
                  "Accept": "application/json",
                  "X-Requested-With": "XMLHttpRequest",
                  "X-CSRF-Token":  token, // Pasamos el token CSRF de la etiqueta meta 
              },
              credentials: "same-origin"
  
          }).then(res => {
  
            return res.json();
  
          }).then(function(res){
              
            //Si todo resulto bien, mandamos mensaje
            if(res.status == 200){
  
              Swal.fire({
                  title: "Configuración guardada",                
                  icon: "success",
                  confirmButtonColor: alertasColor,
                  allowEnterKey: false,
                  allowOutsideClick: false,
                  confirmButtonText: "Aceptar",
                  iconColor: alertasColor,
              }).then((result) => {                    
                  if (result.isConfirmed) {
                      window.location.href = "{{route('configurarvacaciones')}}";
                  }
              });
                
            }else{
              
              Swal.fire({
                title: "Opps!",
                text: 'No fue posible guardar la configuración, intente más tarde',
                icon: "error",
                confirmButtonColor: alertasColor,
                showConfirmButton: true,
                iconColor: alertasColor,
                confirmButtonText: "Aceptar",
                allowEnterKey: true,
                allowOutsideClick: true
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

      function obtenerFechaActual() {
        const fecha = new Date();
        const anio = fecha.getFullYear();
        const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // getMonth() devuelve un valor de 0 a 11
        const dia = String(fecha.getDate()).padStart(2, '0'); // getDate() devuelve el día del mes

        return `${anio}-${mes}-${dia}`;
      }
  
    </script>
  
    {{-- Estilos aplicados al calendario --}}
    <style>
      #calendar {       
         font-size: 14px;
         max-width: 1100px;
         margin: 40px auto;
      }    
  
      .fc-highlight {
        background: #fff !important;
        border: 2px solid blue;
        border-radius: 5%;
      }
  
      .btn_radio_selec{
        font-size: 16px;
        width: 150px;      
        margin: 5px;
        padding: 10px;
        border-radius: 5px;
        position: relative;    
        display: inline;  
      }
  
      .btn_radio_selec_list{
        display: none;
      }
  
      /* .selectedDate{
        background-color: #C04F15 !important;
      } */
  
      @media (max-width:1280) {
        .btn_radio_selec {        
          font-size: 14px;
          width: 125px;      
          margin: 5px;
          padding: 5px;
          border-radius: 5px;
          position: relative; 
          display: block;
        }
          
      }
  
      @media (max-width:991px) {
        .btn_radio_selec {        
          font-size: 12px;
          width: 75px;      
          margin: 3px;
          padding: 3px;
          border-radius: 5px;
          position: relative;   
        }
      }
  
      @media (max-width:854px) {
        .btn_radio_selec_list {        
          display: block
        }
  
        .btn_radio_selec{
          display: none;
        }
  
        #calendar {       
         width: 100%;
         font-size: 12px;       
         margin: 1px;
        } 
  
      }
    </style>
  </x-app-layout>