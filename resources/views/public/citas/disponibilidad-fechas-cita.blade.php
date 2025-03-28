<x-app-layout layout="dashboardv" :assets="$assets ?? []">  
  <div class="iq-navbar-header" style="height: auto;">
      <div class="container-fluid iq-container dorada">
          <div class="row">
            <div class="d-flex justify-content-between">                              
              <h3 class="card-title">Configurar fechas</h3>
            </div>
              <div class="col-lg-12 col-md-12 col-sm-12">                    
                  <div class="card px-0 col-md-12 text-muted">
                    <div class="container col-md-12 m-3 shadow p-3 mb-5 bg-body-tertiary rounded">
                      <div class="row">
                        <h3 class="section-title my-2 text-center dorada">Códigos de disponibilidad</h3>
                        <div class="col-md-4 my-2"><span style="background-color: #B3D9B3; border: 1px solid black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Fechas disponibles</div>
                        <div class="col-md-4 my-2"><span style="background-color: #C3C3C3; border: 1px solid black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Fechas no disponibles</div>
                        <div class="col-md-4 my-2"><span style="background-color: #FFFFFF; border: 1px solid black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Fechas por configurar</div>
                      </div>
                    </div>
                      <div id='calendar-conciliador' style="width:50vw; margin: 0 auto;"></div>
                      @csrf                        
                      {{-- Obtenemos fecha selecionada desde fullCalendar --}}
                      <input id="_cclFechaConciliador" name="cclFecha" type="hidden" value="" />
                      <div class="d-flex">
                          <div class="p-2 flex-grow-1 justify-content-star text-center">
                              <button type="button" class="btn btn-primary btn-primary-ccls rounded-pill" onclick="ValidarcitasAgendas()">Guardar</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

    {{-- Script para FullCalendar --}}
  <script>    

    let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.

    var diasSelecionados = []; //Variable auxiliar para guardar los días seleccionados

    document.addEventListener('DOMContentLoaded', function() {
      var date = new Date(); //variable para selección de horarios
      var dateMes = new Date(); //variable para mostrar un mes calendario
      var datePlusMes = new Date(dateMes.setMonth(dateMes.getMonth() + 12));
      var d = date.getDate();
      var m = date.getMonth();
      var y = date.getFullYear();      
      var calendarConciliador = document.getElementById('calendar-conciliador');
      var fechasDiponiblesEvents = [];
      let colorDefinido = '#FFFFFF';
      
      var fechasDisponibles = @json($fechasDisponibles);  //obtenemos las fechas guardadas

      /*recorremos las fechas guardadas*/
      fechasDisponibles.forEach(e => { 
        if(e.status == 1 ){
          colorDefinido = 'green';
        }else{
          colorDefinido = 'black';
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
      
      var calendarConciliador = new FullCalendar.Calendar(calendarConciliador, {

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
      
      calendarConciliador.render();      
    });

    //ValidarcitasAgendas
    function ValidarcitasAgendas(){

      mensajeProcesando();

      //Variable acumuladora de fechas y auxiliadores
      let fechasCitas = '';
      let titulo = '';

      const token = document.querySelector('meta[name="csrf-token"]').content; //Obtenemos el token

      fetch("{{route('validarfechasagendadas')}}",
      {
        method: "POST",
        body: JSON.stringify(diasSelecionados),
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

        let data = res.data; // datos de fechas y citas registradas
        let dataLongitud = data.length; // fechas encontradas con citas

        //Itermos las fechas con citas
        data.forEach((fechas, index) => {

          //Controlamos el titulo a mostrar
          if(dataLongitud > 1){
            titulo = 'Hay citas registradas en las fechas seleccionadas';
          }else{
            titulo = 'Hay citas registradas en la fecha seleccionada';
          }

          ///Texto a mostrar
          fechasCitas = fechasCitas + 'Fecha: ' + fechas.fecha + ', citas: ' + fechas.citas + '<br>';
          
        });

        fechasCitas = fechasCitas + '<br> Se cancelarán todas las citas y se notificarán via correo a los usuarios, ¿desea continuar?';

        //Verificamos si hay fechas con citas
        if(res.status == 200 && dataLongitud > 0){

          Swal.fire({
            title: titulo,
            html: fechasCitas,
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
              guardarDisponibilidad();
            }

          });

        }else if(res.status == 200){

          guardarDisponibilidad();

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
    function guardarDisponibilidad(){
      
        Swal.showLoading();
        const token = document.querySelector('meta[name="csrf-token"]').content; //Obtenemos el token
        //const _tipo = document.getElementById('_tipo').value; //Obtenemos el tipo para guardar fechas        
        
        //Solicitud de guardado sin refrescar
        fetch("{{route('guardardisponibilidadfechas')}}",
        {
            method: "POST",
            body: JSON.stringify(diasSelecionados),
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
                    window.location.href = "{{route('disponibilidadfechas')}}";
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
      border: 2px solid green;
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