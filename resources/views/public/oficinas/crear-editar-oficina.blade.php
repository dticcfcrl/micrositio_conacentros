<x-app-layout layout="dashboardv" :assets="$assets ?? []">
    <div class="iq-navbar-header" style="height: auto;">
        <div class="container-fluid iq-container dorada">
           <div class="row">
                <div class="d-flex justify-content-between">                              
                    <h3 class="card-title">{{isset($id) ? 'Editar' : 'Crear' }} Oficina</h3>
                    <div class="text-white">
                    @if(auth()->user()->perfil == 'superusuario')
                        <a href="{{route('listaroficinas')}}" class="btn btn-sm btn-primary btn-primary-ccls" role="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                            <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 10L3.29289 10.7071L2.58579 10L3.29289 9.29289L4 10ZM21 18C21 18.5523 20.5523 19 20 19C19.4477 19 19 18.5523 19 18L21 18ZM8.29289 15.7071L3.29289 10.7071L4.70711 9.29289L9.70711 14.2929L8.29289 15.7071ZM3.29289 9.29289L8.29289 4.29289L9.70711 5.70711L4.70711 10.7071L3.29289 9.29289ZM4 9L14 9L14 11L4 11L4 9ZM21 16L21 18L19 18L19 16L21 16ZM14 9C17.866 9 21 12.134 21 16L19 16C19 13.2386 16.7614 11 14 11L14 9Z" fill="#ffffff"/>
                            </svg>
                        </a>
                    @endif
                    </div>
                </div>
           </div>
           <div class="col-sm-12">
            <div class="card mb-3">               
               <div class="card-body">
                  <div class="new-user-info">
                    <form action="{{isset($id) ?  route('actualizaroficina') : route('guardaroficina')}}" method="post" id="form_oficina_alta">
                        @csrf
                        @if(isset($id))
                           @method('PUT')
                        @endif
                        <div class="row">                           
                           
                            <div class="form-group col-md-6">
                                <label for="_estado " class="form-label">                                     
                                    {{isset($id) ? 'Entidad:' : 'Seleccione entidad:'}} <span class="text-danger fs-5" id="_estadoRequerido"></span>
                                </label>                                  
                                <select name="estado" id="_estado" class="col-12 form-select mb-3" onchange="obtenerMunicipios(this,1); habilitarAgregar()" value="{{old('estado')}}">
                                    <option> Seleccione entidad</option> 
                                    @foreach ($estados as $estado)
                                        @if(isset($id) && $oficina[0]->estado === $estado )
                                            <option value="{{$loop->index+1}}" selected>{{$estado}}</option>
                                        @else
                                            <option value="{{$loop->index+1}}">{{$estado}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="_municipio " class="form-label">
                                    {{isset($id) ? 'Municipio:' : 'Seleccione municipio:'}}                                    
                                </label>
                                <select name="municipio" id="_municipio" class="col-12 form-select mb-3">
                                    <option> Seleccione municipio </option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="_ambito " class="form-label">
                                    {{isset($id) ? 'Ámbito:' : 'Seleccione ámbito:'}}  <span class="text-danger fs-5" id="_ambitoRequerido"></span>
                                </label>
                                <select name="ambito" id="_ambito" class="col-12 form-select mb-3" value="{{old('ambito', isset($id) ? $id : '')}}">
                                    <option> Seleccione ámbito </option>
                                    <option {{isset($id) && $oficina[0]->ambito == 'Local' ? 'selected' : ''}}> Local </option>
                                    <option {{isset($id) && $oficina[0]->ambito == 'Federal' ? 'selected' : ''}}> Federal </option>
                                </select>                                                                        
                            </div>

                            <div class="form-group col-md-6">
                                <label for="_zona " class="form-label">
                                    {{isset($id) ? 'Zona horaria:' : 'Seleccione zona horaria:'}} 
                                    <a href="https://es.wikipedia.org/wiki/Husos_horarios_de_M%C3%A9xico" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ayuda" target="_blank">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#000000" stroke-width="2"/>
                                            <path d="M10.5 8.67709C10.8665 8.26188 11.4027 8 12 8C13.1046 8 14 8.89543 14 10C14 10.9337 13.3601 11.718 12.4949 11.9383C12.2273 12.0064 12 12.2239 12 12.5V12.5V13" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12 16H12.01" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    <span class="text-danger fs-5" id="_zonaRequerido"></span>
                                </label>
                                <select name="zona" id="_zona" class="col-12 form-select mb-3" value="{{old('zona')}}">
                                    <option> Seleccione zona horaria </option>
                                    <option {{isset($id) && $oficina[0]->zona_horaria == 'Zona Sureste (UTC-5)' ? 'selected' : ''}}> Zona Sureste (UTC-5) </option>
                                    <option {{isset($id) && $oficina[0]->zona_horaria == 'Zona Centro (UTC-6)' ? 'selected' : ''}}> Zona Centro (UTC-6) </option>
                                    <option {{isset($id) && $oficina[0]->zona_horaria == 'Zona Pacífico (UTC-7)' ? 'selected' : ''}}> Zona Pacífico (UTC-7) </option>
                                    <option {{isset($id) && $oficina[0]->zona_horaria == 'Zona Noroeste (UTC-8)' ? 'selected' : ''}}> Zona Noroeste (UTC-8) </option>
                                </select>                                                                        
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <label for="_direccion" class="form-label"> 
                                    Domicilio: <span class="text-danger fs-5" id="_direccionRequerido"></span>
                                </label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="direccion" id="_direccion" value="{{old('direccion', isset($id) ? $oficina[0]->direccion: '')}}" placeholder="" maxlength="255"> 
                                </div>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="_cp" class="form-label"> 
                                    Código postal: <span class="text-danger fs-5" id="_cpRequerido"></span>
                                </label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="cp" id="_cp" value="{{old('cp', isset($id) ? $oficina[0]->cp: '')}}" placeholder="" maxlength="5"> 
                                </div>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="_contacto" class="form-label"> 
                                    Contacto: <span class="text-danger fs-5" id="_contactoRequerido"></span>
                                </label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="contacto" id="_contacto" value="{{old('contacto', isset($id) ? $oficina[0]->contacto: '')}}" placeholder="" minlength="10"> 
                                </div>
                            </div>

                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                        <div class="w-100" id="map-container" style="height: 300px; width: 100%">Esperando mapa...</div>
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-12">

                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                            <label for="_latitud" class="form-label"> 
                                                Latitud: 
                                                <a href="https://support.google.com/maps/answer/18539?hl=es&co=GENIE.Platform%3DDesktop" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ayuda" target="_blank">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#000000" stroke-width="2"/>
                                                        <path d="M10.5 8.67709C10.8665 8.26188 11.4027 8 12 8C13.1046 8 14 8.89543 14 10C14 10.9337 13.3601 11.718 12.4949 11.9383C12.2273 12.0064 12 12.2239 12 12.5V12.5V13" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M12 16H12.01" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                                <span class="text-danger fs-5" id="_latitudRequerido"></span>
                                            </label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control mb-3" name="latitud" id="_latitud" value="{{old('latitud', isset($id) ? $oficina[0]->lat : '')}}" placeholder="" maxlength="20"> 
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                            <label for="_longitud" class="form-label"> 
                                                Longitud: 
                                                <a href="https://support.google.com/maps/answer/18539?hl=es&co=GENIE.Platform%3DDesktop" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ayuda" target="_blank">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#000000" stroke-width="2"/>
                                                        <path d="M10.5 8.67709C10.8665 8.26188 11.4027 8 12 8C13.1046 8 14 8.89543 14 10C14 10.9337 13.3601 11.718 12.4949 11.9383C12.2273 12.0064 12 12.2239 12 12.5V12.5V13" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M12 16H12.01" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                                <span class="text-danger fs-5" id="_longitudRequerido"></span>
                                            </label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control mb-3" name="longitud" id="_longitud" value="{{old('longitud', isset($id) ? $oficina[0]->long : '')}}" placeholder="" maxlength="20"> 
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <label for="_url" class="form-label"> 
                                    URL google maps: <span class="text-danger fs-5" id="_urlRequerido"></span>
                                </label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="url" id="_url" value="{{old('url', isset($id) ? $oficina[0]->url_google: '')}}" placeholder="" maxlength="2028"> 
                                </div>
                            </div>                            

                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <label for="_pagina" class="form-label"> 
                                    Página web: <span class="text-danger fs-5" id="_paginaRequerido"></span>
                                </label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="pagina" id="_pagina" value="{{old('pagina', isset($id) ? $oficina[0]->link: '')}}" placeholder="" maxlength="2028"> 
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <label for="_liga" class="form-label"> 
                                    Liga para agendar citas: <span class="text-danger fs-5" id="_ligaRequerido"></span>
                                </label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="liga" id="_liga" value="{{old('liga', isset($id) ? $oficina[0]->liga_cita: '')}}" placeholder="" maxlength="2028"> 
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <label for="_ligalocal" class="form-label"> 
                                    Liga para agendar citas ámbito local: <span class="text-danger fs-5" id="_ligaRequerido"></span>
                                </label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="ligalocal" id="_ligalocal" value="{{old('ligalocal', isset($id) ? $oficina[0]->liga_cita_local: '')}}" placeholder="" maxlength="2028"> 
                                </div>
                            </div>
                        </div>                                                                         
                        <hr>
                        {{-- <h5 class="mb-3">Security</h5> --}}      
                        @if(isset($bloqueo) && $bloqueo == true ? 'disabled': '')
                        <input type="hidden" name="estado" id="_estado" value="{{$oficina[0]->estado}}">
                        <input type="hidden" name="municipio" id="_municipio" value="{{$oficina[0]->municipio}}">
                        <input type="hidden" name="ambito" id="_ambito" value="{{$oficina[0]->ambito}}">
                        <input type="hidden" name="latitud" id="_latitud" value="{{$oficina[0]->lat}}">
                        <input type="hidden" name="longitud" id="_longitud" value="{{$oficina[0]->long}}">
                        @endif
                        <input type="hidden" name="id" id="_id" value="{{isset($oficina) ? $oficina[0]->id : ''}}">
                        <input type="hidden" name="bloqueo" id="_bloqueo" value="{{isset($bloqueo) && $bloqueo == true ? true : false}}">
                        <div class="d-flex">                                        
                           <div class="p-2 flex-grow-1 justify-content-star text-center">
                               <button type="submit" class="btn btn-primary btn-primary-ccls rounded-pill" id="btnAgregar" onclick="mensajeProcesando()" disabled>{{isset($id) ? 'Actualizar' : 'Agregar' }}</button>
                           </div>
                       </div>        
                    </form>                   
                  </div>
               </div>                      
            </div>
         </div>
        </div>
    </div>

    <script src="{{ asset('js/leaflet.js')}}"></script>
    <script>
        window.addEventListener('load', habilitarAgregar);
        let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.
     
        habilitaBoton = document.getElementById('btnAgregar'); // validarmos si dejamos pasar el botón
                
        let _estado = document.getElementById('_estado');
        let _municipio = document.getElementById('_municipio');

        let jv_municipio =  _municipio.selectedOptions[0].text;

        let latitud, longitud; //SE DEFINEN VARIABLES PARA ALMACENAR UBICACIÓN.                

        //Obtenemos los estados desde la edición   
        @if(isset($id))
            _claveEstado = @json($estado_clave[0]);
            $lat = @json($oficina[0]->lat);
            $long = @json($oficina[0]->long);
            if(_claveEstado != undefined){
                entEdit = _claveEstado; 
                obtenerMunicipios(entEdit, 0);
            }
        @endif
        
        /* Script para obtener estados y municipio Inicio */
        function obtenerMunicipios(selectEstado,desde){            
           if(desde == 1){
           entidadSeleccionada = selectEstado.value;
           }
     
           if(desde == 0){
           entidadSeleccionada = selectEstado;
           }
           
           fetch(`../../../ccls/oficinas/${entidadSeleccionada}/municipios`)
           .then(res => {
           return res.json();
           })
           .then(function (resCcls){
           listarCclsMunicipios(resCcls);
           })
        }
     
        /* Función para mostrar los municipios */
        function listarCclsMunicipios(municipio){
            
            let cclMunicipios = document.getElementById('_municipio');         
            limpiarCclsMunicipios(cclMunicipios);
            municipio.forEach(e => {                
                let opcionMunicipio = document.createElement('option');
                @if(isset($id))
                    if( "{{$oficina[0]->municipio}}" != '' && "{{$oficina[0]->municipio}}" == e.nombre){
                        opcionMunicipio.setAttribute('selected', true);
                    }                
                @endif
                opcionMunicipio.value = e.nombre;
                opcionMunicipio.innerHTML = e.nombre;
                cclMunicipios.append(opcionMunicipio);
            });
        }
        
        /* Función para limpiar municipio cuando se cambia de estado */
        function limpiarCclsMunicipios(cclMunicipios){
           while (cclMunicipios.options.length > 0){
              cclMunicipios.remove(0);
           }
        } 
     
        /* Validamos lo campos input */
        const validarCamposFormulario = (campoTipo, campoRequerido, e) => {
           const campo = e.target;
           const valorCampo = e.target.value.trim();
           switch(campoTipo){
              case 'select':
                 if(valorCampo.indexOf("Seleccione") == 0){
                    document.getElementById(campoRequerido).innerHTML = `requerido`;
                 }else{
                    document.getElementById(campoRequerido).innerHTML = '';
                    jv_municipio =  _municipio.selectedOptions[0].text;
                 }
              break;        
              case 'input':
                 if(valorCampo.length < 4){
                    document.getElementById(campoRequerido).innerHTML = `requerido`;
                 }else{
                    document.getElementById(campoRequerido).innerHTML = '';
                 }
              break;
              }
           habilitarAgregar();
        }
     
        /* Validamos CP */
        const validarCampoCP = e => {
           const campo = e.target;
           const valorCampo = e.target.value.trim();
           const regexCP = new RegExp(/^[0-9]{5}$/);
           if(valorCampo == '' || valorCampo.length > 0 && !regexCP.test(valorCampo)){
              document.getElementById('_cpRequerido').innerHTML = `cp no válido`;        
           }else{
              document.getElementById('_cpRequerido').innerHTML = '';
           }
           habilitarAgregar();
        }
     
        /* Validamos número celular */
        const validarCampoCelular = e => {
           const campo = e.target;
           const valorCampo = e.target.value.trim();
           if(valorCampo == '' || valorCampo.length < 10){
              document.getElementById('_contactoRequerido').innerHTML = `número no válido`;        
           }else{
              document.getElementById('_contactoRequerido').innerHTML = '';
           }
           habilitarAgregar();
        }

        /* Validamos coordenada*/
        const validarCoordenadas = (campoRequerido, e) => {            
            const valorCampo = e.target.value.trim();
            const regexCoordinadas = new RegExp(/-?[0-9]{1,3}[.][0-9]+/);
            if(valorCampo == '' || valorCampo.length > 0 && !regexCoordinadas.test(valorCampo)){
                document.getElementById(campoRequerido).innerHTML = `coordenada no válida`;        
            }else{
                document.getElementById(campoRequerido).innerHTML = '';
            }
            habilitarAgregar();
        }

        /* Se válida entidad selecionada */
        _estado.addEventListener("change", (e) => validarCamposFormulario('select','_estadoRequerido', e));

        /* Se válida ambito */
        _ambito.addEventListener("change", (e) => validarCamposFormulario('select','_ambitoRequerido', e));

        /* Se válida zona horaria */
        _zona.addEventListener("change", (e) => validarCamposFormulario('select','_zonaRequerido', e));

        /* Se válida nombre */
        _direccion.addEventListener("input", (e) => validarCamposFormulario('input','_direccionRequerido', e));
        
        /* Se válida cp */
        _cp.addEventListener("input", validarCampoCP);

        /* Se válida Número de contacto */    
        _contacto.addEventListener("input", validarCampoCelular);

        /* Se válida latitud */
        _latitud.addEventListener("input", (e) => validarCoordenadas('_latitudRequerido', e));

        /* Se válida longitud */
        _longitud.addEventListener("input", (e) => validarCoordenadas('_longitudRequerido', e));
     
        /* función para verificar si habilitamos botón agendar cita */
        function habilitarAgregar(){
           valBoton = 0;
           valEstado = _estado.value;
           valMunicipio = _municipio.selectedOptions[0].text;;
           valAmbito = _ambito.value;
           valZona = _zona.value;
           valDireccion = _direccion.value;
           valCP = _cp.value;
           valContacto = _contacto.value;
           valLatitud = _latitud.value;
           valLongitud = _longitud.value;
           const regexCP = new RegExp(/^[0-9]{5}$/);
           const regexCel = new RegExp(/^[0-9]{10}$/);
           const regexCoordinadas = new RegExp(/-?[0-9]{1,3}[.][0-9]+/);
           
           if(valEstado == '' || valEstado == 'Seleccione entidad'){
              valBoton++
           }

           if(valMunicipio == 'Seleccione municipio'){
              valBoton++
           }
     
           if(valAmbito == '' || valAmbito == 'Seleccione ámbito'){
              valBoton++
           }
     
           if(valZona == '' || valZona == 'Seleccione zona horaria'){
              valBoton++
           }
     
           if(valDireccion == '' || valDireccion.length < 10){
              valBoton++
           }

           if(valCP == '' || !regexCP.test(valCP)){
              valBoton++
           }

           if(valContacto == '' || valContacto.length < 10){
              valBoton++
           }
     
           if(valLatitud == ''){
                valBoton++
           }

           if(valLongitud == ''){
                valBoton++
           }
     
           if(valBoton == 0){
           habilitaBoton.disabled = false;
              @if(isset($id))
                 habilitaBoton.innerHTML = 'Actualizar'
              @else
                 habilitaBoton.innerHTML = 'Agregar'
              @endif
           }else{
           @if(isset($id))
              if(valBoton != 0 && 
                    (
                        valEstado == '' || valEstado == 'Seleccione entidad' 
                        || valMunicipio == ''
                        || valAmbito == '' || valAmbito == 'Seleccione ámbito'
                        || valZona == '' || valZona == 'Seleccione zona horaria'
                        || valDireccion == '' || valDireccion.length < 10
                        || valCP == '' || !regexCP.test(valCP)
                        || valContacto == '' || valContacto.length < 10
                        || valLatitud == ''
                        || valLongitud == ''
                    )
                ){
                
                 validaBoton(true, 'Complete el formulario');
              }else{
                 validaBoton(false, 'Actualizar');            
              }
           @else
              validaBoton(true, 'Complete el formulario');         
           @endif
           }
        }
     
        function validaBoton(tipo, texto){
        
           habilitaBoton.disabled = tipo;
           habilitaBoton.innerHTML = texto;
        }
     
        /*mensajes de espera*/
        function mensajeProcesando(){
           Swal.fire({
                title: "Procesando",
                text: "Espere, por favor",
                icon: "info",
                iconColor: alertasColor,
                showClass: {
                popup: 'animate__animated animate__fadeInDown'
                }                
           });
           Swal.showLoading();
        }

        /* Comienza interacción de mapa */
        viewMap();
    
        /* Función para pintar mapa y obtener coordenadas */ 
        function viewMap(){

            let lat;
            let long;
        
            // Perzonalizamos el pin
            let customIcon = {
                iconUrl:"{{asset('images/ccls/edificio.png')}}",
                iconSize:[25,25],
            }
            let iconoPin = L.icon(customIcon);
            
            // Configuramos pin perzonalizado
            let iconOptions = {
                title:"ccls",
                draggable:false,
                icon:iconoPin,
                clickable: true,
            }

            @if(isset($id))
                lat = "{{$oficina[0]->lat}}";
                lon = "{{$oficina[0]->long}}";
            @else
                lat = 23.634501;
                lon = -102.552784;
            @endif
            
            // Configuramos coorndeadas y zoom
            var mapOptions = {
                center: [lat, lon],
                zoom: 10
            }
            
            // Creamos el mapa con las configuracion previamente configuradas
            let map = new L.map('map-container' , mapOptions);
            
            // Libreia
            let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });
            map.addLayer(layer);
            
            // Inicializamos el pin
            let marker = null;
            
            // Agramos el pin cuando se carga el mapa
            if(marker == null){
                marker = new L.Marker([lat, lon] , iconOptions).addTo(map);
                // TODO colocar las coordenadas en los campos correspondientes
                document.getElementById('_latitud').value = marker._latlng.lat;
                document.getElementById('_longitud').value = marker._latlng.lng;
            }
            
            // Agregamos el pin de acuerdo al evento click y obtenemos las coordenadas
            map.on('click', (event) => {
                
                // Remove el pin inicalmente cargado
                map.removeLayer(marker);
            
                // Removemos todos los pines para solo pintar el último pin colocado
                if(marker !== null){
                    map.removeLayer(marker);
                }
            
                // Obtenemos las coordenadas del pin colocado
                marker = L.marker([event.latlng.lat , event.latlng.lng], iconOptions).addTo(map);
            
                // Coloca las coordenadas en los campos correspondientes
                document.getElementById('_latitud').value = event.latlng.lat;
                document.getElementById('_longitud').value = event.latlng.lng;
                
            });
        }

    </script>
</x-app-layout>