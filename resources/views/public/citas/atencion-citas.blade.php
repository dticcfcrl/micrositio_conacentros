<x-app-layout layout="dashboardv" :assets="$assets ?? []">
  <div class="iq-navbar-header" style="height: auto;">
      <div class="container-fluid iq-container dorada">
          <div class="row">
              <h3>Atención a citas del día</h3>
              <div class="col-sm-12">
                  <div class="card mb-3">                
                      <div class="card-body px-0">
                          <div class="">
                              <div class="columna-fija">
                                  <table id="atencion-citas-list-table" class="table table-striped" role="grid" data-toggle="data-table">
                                      <thead>
                                          <tr>
                                              <th>No.</th>
                                              <th>Folio</th>
                                              <th>Nombre</th>
                                              <th>Apellidos</th>
                                              <th>Cita</th>
                                              <th>Estatus</th>
                                              <th>Observaciones</th>                                            
                                              <th>Estatus del conciliador</th>
                                              <th>Observaciones del conciliador</th>
                                              @can('mostrar_acciones_citas')
                                                  <th>Acciones</th>
                                              @endcan
                                          </tr>
                                      </thead>
                                      <tbody>                                            
                                          @foreach ($listar_citas as $cita)
                                              @if($cita->cita_fecha >= date("Y-m-d", strtotime($fechaActual)))
                                                  <tr>
                                                      <td scope="row">
                                                          <p>{{$loop->index + 1}}</p>
                                                      </td>
                                                      <td scope="row">
                                                          <p>{{$cita->cita_folio}}</p>
                                                      </td>                                    
                                                      <td>
                                                          <p>{{$cita->nombre}}</p>
                                                      </td>
                                                      <td>
                                                          <p>{{$cita->apellidos}}</p>
                                                      </td>
                                                      <td>
                                                          <p>{{date("d-m-Y", strtotime($cita->cita_fecha))}} {{Str::substr($cita->cita_hora, 0, 5)}}</p>
                                                      </td>
                                                      <td>
                                                          <p>{{$cita->status == 1 ? 'confirmada' : 'cancelada'}}</p>
                                                      </td>                                            
                                                      <td>
                                                          <p>{{$cita->observaciones}}</p>
                                                      </td>
                                                      <td>                                                
                                                          <p>{{$cita->status_conciliador == 2 ? 'pendiente' : ($cita->status_conciliador == 1 ? 'atentida' : 'cancelada')}}</p>
                                                      </td>
                                                      <td>
                                                          <p>{{$cita->observaciones_conciliador != null ? $cita->observaciones_conciliador : '...' }}</p>
                                                      </td>
                                                      @can('mostrar_acciones_citas')
                                                      <td>
                                                          <div class="align-items-center list-user-action">
                                                            <button type="button" class="btn btn-info btn-info-ccls btn-md btn-icon" data-original-title="Ver más" data-bs-toggle="modal" data-bs-target="#accionModalVerMas" onclick="obtenerDatos('{{$cita->cita_folio}}', '{{$cita->nombre}}', '{{$cita->apellidos}}', '{{$cita->cita_fecha}}', '{{$cita->cita_hora}}', '{{$cita->correo}}', '{{ $cita->celular }}', '0')">
                                                                <a class="" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver más">
                                                                    <span class="btn-inner">
                                                                        <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                                                            width="32px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                                                                            <path fill="#ffffff" d="M63.714,30.516C63.347,29.594,54.448,8,31.999,8S0.651,29.594,0.284,30.516
                                                                            c-0.379,0.953-0.379,2.016,0,2.969C0.651,34.406,9.55,56,31.999,56s31.348-21.594,31.715-22.516
                                                                            C64.093,32.531,64.093,31.469,63.714,30.516z M31.999,40c-4.418,0-8-3.582-8-8s3.582-8,8-8s8,3.582,8,8S36.417,40,31.999,40z"/>
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                            </button>
                                                            @can('confirmar_cita')                                                        
                                                            @if($cita->cita_fecha <= date("Y-m-d", strtotime($fechaActual)))
                                                            <button type="button" class="btn btn-primary-ccls btn-md btn-icon" data-original-title="Confirmar" data-bs-toggle="modal" data-bs-target="#accionModalCita" onclick="obtenerDatos('{{$cita->cita_folio}}', '{{$cita->nombre}}', '{{$cita->apellidos}}', '{{$cita->cita_fecha}}', '{{$cita->cita_hora}}', '{{$cita->correo}}', '{{ $cita->celular }}', '1')">
                                                                      <a class="" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Confirmar">
                                                                          <span class="btn-inner">
                                                                              <svg class="text-white" width="32" viewBox="0 0 36 36" version="1.1"  preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                                  <title>success-line</title>
                                                                                  <path stroke="#fff" fill="#fff" class="clr-i-outline clr-i-outline-path-1" d="M13.72,27.69,3.29,17.27a1,1,0,0,1,1.41-1.41l9,9L31.29,7.29a1,1,0,0,1,1.41,1.41Z"></path>
                                                                                  <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                                                                              </svg>
                                                                          </span>
                                                                      </a>
                                                                  </button>
                                                                  @endif
                                                              @endcan
                                                              
                                                              @can('cancelar_cita')
                                                                  @if($cita->cita_fecha > date("Y-m-d", strtotime($fechaActual)))
                                                                      <button type="button" class="btn btn-danger btn-info-ccls btn-md btn-icon" data-original-title="Rechazada" data-bs-toggle="modal" data-bs-target="#accionModalCita" onclick="obtenerDatos('{{$cita->cita_folio}}', '{{$cita->nombre}}', '{{$cita->apellidos}}', '{{$cita->cita_fecha}}', '{{$cita->cita_hora}}', '{{$cita->correo}}', '{{ $cita->celular }}', '0')">
                                                                          <a class="" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Cancelar">
                                                                              <span class="btn-inner">
                                                                                  <svg width="32" class="text-white" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">                                                                
                                                                                      <g id="Page-1" stroke="#ffffff" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                          <g id="work-case" fill="#fff" transform="translate(91.520000, 91.520000)">
                                                                                              <polygon id="Close" points="328.96 30.2933333 298.666667 1.42108547e-14 164.48 134.4 30.2933333 1.42108547e-14 1.42108547e-14 30.2933333 134.4 164.48 1.42108547e-14 298.666667 30.2933333 328.96 164.48 194.56 298.666667 328.96 328.96 298.666667 194.56 164.48"></polygon>
                                                                                          </g>
                                                                                      </g>
                                                                                  </svg>
                                                                              </span>
                                                                          </a>
                                                                      </button>                                                
                                                                  @endif
                                                              @endcan
                                                          </div>
                                                      </td>
                                                      @endcan
                                                  </tr>
                                              @endif

                                          @endforeach                                        
                                      </tbody>
                                  </table>
                              </div>
                          </div>                                                                        
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

<!--Modal para confirmar datos de cita-->
<div class="modal fade" id="accionModalCita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!--Texto de encabezado-->
            <div class="modal-header">
                <h5 class="modal-title" id="_infoCita"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Cuerpo de confirmación -->
            <div class="modal-body">
            <!-- Div para folio -->

                <form action="{{route('accioncitaconciliador')}}" method="post" id="_accionCitaConciliadorForm">
                    @csrf
            
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label for="cclRachazaCita" class="d-block dorada my-2"> 
                            <h4 class="dorada" id="_accionCita"></h4>
                        </label>
                        <div class="input-group has-validation">                
                            <textarea type="text" class="form-control mb-3" name="accionCita" id="_accionCitaText" maxlength="128" oninput="valObservaciones(this)"></textarea>
                            <div class="invalid-feedback">
                                razon
                            </div>
                        </div>
                        <span class="text-info fs-6" id="_carRestantes"></span>
                    </div>
                    <input type="hidden" name="accionFolio" id="_accionFolioH">
                    <input type="hidden" name="accionNombre" id="_accionNombreH">
                    <input type="hidden" name="accionApellidos" id="_accionApellidosH">
                    <input type="hidden" name="accionFecha" id="_accionFechaH">
                    <input type="hidden" name="accionHora" id="_accionHoraH">
                    <input type="hidden" name="accionCorreo" id="_accionCorreoH">
                    <input type="hidden" name="accionCitaStatus" id="_accionCitaH">

                    <!-- Div para Bótones -->
                    <div class="modal-footer justify-content-right">              
                        <div class="d-flex">
                            <div class="p-2 justify-content-start">
                                <button type="button" class="btn btn-secondary btn-info-ccls rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                            </div>            
                            <div class="p-2 flex-grow-1 justify-content-end">                        
                                <button type="submit" class="btn btn-primary btn-primary-ccls rounded-pill" data-bs-dismiss="modal" onclick="mensajeProcesando()">Confirmar</button>
                            </div>
                        </div>            
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal para ver más datos de usuario -->
<div class="modal fade" id="accionModalVerMas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!--Texto de encabezado-->
            <div class="modal-header">
                <h5 class="modal-title">Datos de contacto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Cuerpo de confirmación -->
            <div class="modal-body">
            <!-- Div para folio -->
        
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label class="d-block dorada my-2"> 
                        <h4 class="dorada">Nombre</h4>
                    </label>
                    <span class="fs-6 text-black" id="_datos"></span>
                    
                    <label class="d-block dorada my-2"> 
                        <h4 class="dorada">Correo</h4>
                    </label>
                    <span class="fs-6 text-black" id="_datosCorreo"></span>

                    <label class="d-block dorada my-2"> 
                        <h4 class="dorada">Teléfono Celular</h4>
                    </label>
                    <span class="fs-6 text-black" id="_datosCelular"></span>
                </div>

                <!-- Div para Bótones -->
                <div class="modal-footer justify-content-right">              
                    <div class="d-flex">
                        <div class="p-2 flex-grow-1 justify-content-end">                        
                            <button type="submit" class="btn btn-primary btn-primary-ccls rounded-pill" data-bs-dismiss="modal">Salir</button>
                        </div>
                    </div>            
                </div>

            </div>
        </div>
    </div>
</div>

  <!-- Se cooloca última columna fija-->
  <style>
      table {
          text-align: center;
      }

      .columna-fija {
          width: auto;
          height: auto;
          overflow: scroll;
      }

      table th, table td {
          white-space: nowrap;
          padding: 10px 20px;
          font-family: Arial;
      }

      table tr th:last-child, table td:last-child {
          position: sticky;
          width: 100px;
          right: 0;
          z-index: 10;
          background:transparent;
      }

      table tr td:last-child {
          background: #ffffff
      }

      table tr th:last-child {
          z-index: 11;
      }

      table tr th {
          position: sticky;
          top: 0;
          z-index: 9;
          background: #fff;
      }
      
  </style>

  <script>
      
      let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.

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

      /* Validamos los caraceteres máximos permitidos */
      function valObservaciones(caracteres){            
          let len = caracteres.value.length;
          document.getElementById('_carRestantes').innerHTML = 'Max: ' + len + '/128';
      }

      function obtenerDatos(folio, nombre, apellidos, fecha, hora, correo, celular, tipo){
      
          /* variable de apoyo para mensajes */
          let titulo = '';

          /*Obtenemos los datos de la posición 'i'*/
          var _folio = document.getElementById("_accionFolioH").value = folio;
          var _nombre = document.getElementById("_accionNombreH").value = nombre;
          var _apellidos = document.getElementById("_accionApellidosH").value = apellidos;
          var _fecha = document.getElementById("_accionFechaH").value = fecha;
          var _hora = document.getElementById("_accionHoraH").value = hora;
          var _correo = document.getElementById("_accionCorreoH").value = correo;
          var _accion = document.getElementById("_accionCitaH").value = tipo;
          var _accionCita = document.getElementById('_accionCita');
          var _infoCita = document.getElementById('_infoCita');

          // Datos de contacto
          _datos.innerHTML = _nombre.toUpperCase() + ' ' + _apellidos.toUpperCase(); 
          _datosCorreo.innerHTML = _correo;
          _datosCelular.innerHTML = celular ?  celular : 'No proporcionado';

          // Para Cancelar/Confirmar cita
          _infoCita.innerHTML = 'Folio: ' + _folio;

          if(tipo == '1'){
              _accionCita.innerHTML = "¿Fue atendida la cita de " + capitalizeFirstLetter(_nombre) + "?";
          }
          
          if(tipo == '0'){
              _accionCita.innerHTML = "Detalle los motivos de cancelación cita de " + capitalizeFirstLetter(_nombre);
          }
          
      }   

      /*Función para crear capital case*/
      function capitalizeFirstLetter(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
      }
      
  </script>
</x-app-layout>