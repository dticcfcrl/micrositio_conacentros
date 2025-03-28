<x-app-layout layout="dashboardv" :assets="$assets ?? []">

<div>
   <?php
      $id = $id ?? null;  
   ?>

   <div class="iq-navbar-header" style="height: auto;">
      <div class="container-fluid iq-container dorada">
         <div class="row">
            <div class="d-flex justify-content-between">                              
               <h3 class="card-title">{{$id !== null ? 'Editar' : 'Crear' }} usuario</h3>         
               <div class="text-white">
                  <a href="{{ auth()->user()->id != $id ? route('users.index') : route('dashboard') }}" class="btn btn-sm btn-primary btn-primary-ccls" role="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                     <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 10L3.29289 10.7071L2.58579 10L3.29289 9.29289L4 10ZM21 18C21 18.5523 20.5523 19 20 19C19.4477 19 19 18.5523 19 18L21 18ZM8.29289 15.7071L3.29289 10.7071L4.70711 9.29289L9.70711 14.2929L8.29289 15.7071ZM3.29289 9.29289L8.29289 4.29289L9.70711 5.70711L4.70711 10.7071L3.29289 9.29289ZM4 9L14 9L14 11L4 11L4 9ZM21 16L21 18L19 18L19 16L21 16ZM14 9C17.866 9 21 12.134 21 16L19 16C19 13.2386 16.7614 11 14 11L14 9Z" fill="#ffffff"/>
                     </svg>
                  </a>
               </div>
            </div>
         
            
            <div class="col-sm-12">
               <div class="card mb-3">               
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="{{isset($usuario_edit) ? route('users.update', $id) : route('users.store')}}" method="post" id="form_usuario_alta">
                           @csrf
                           @if(isset($usuario_edit))
                              @method('PUT')
                           @endif
                           <div class="row">
                              <div class="form-group col-md-6">
                                 <label for="_nombre" class="form-label"> 
                                    Nombre(s): <span class="text-danger fs-5" id="_nombreRequerido"></span>
                                 </label>
                                 <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="nombre" id="_nombre" value="{{old('nombre', isset($usuario_edit->nombre) ? $usuario_edit->nombre : '')}}" placeholder="" maxlength="50">
                                    <div class="invalid-feedback">
                                          Ingrese nombre(s)
                                    </div>
                                 </div>
                              </div>

                              <div class="form-group col-md-6">
                                 <label for="_apellidos" class="form-label"> 
                                    Apellidos: <span class="text-danger fs-5" id="_apellidosRequerido"></span>
                                 </label>
                                 <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="apellidos" id="_apellidos" value="{{old('apellidos', isset($usuario_edit->apellidos) ? $usuario_edit->apellidos : '')}}" placeholder="" maxlength="50">
                                    <div class="invalid-feedback">
                                          Ingrese apellidos
                                    </div>
                                 </div>
                              </div>

                              <div class="form-group col-md-6">
                                 <label for="_email" class="form-label"> 
                                    Correo electrónico: <span class="text-danger fs-5" id="_emailRequerido"></span>
                                  </label>
                                  <div class="input-group has-validation">
                                      <input type="text" name="email" class="form-control mb-3" id="_email" value="{{old('email', isset($usuario_edit->email) ? $usuario_edit->email : '')}}" placeholder="micorreo@midominio.com" maxlength="50">
                                      <div class="invalid-feedback">
                                          Ingrese correo electrónico
                                      </div>
                                  </div>
                              </div>

                              <div class="form-group col-md-6">
                                 <label for="_email_confirmar" class="form-label"> 
                                    Confirmar correo electrónico: <span class="text-danger fs-5" id="_email_confirmarRequerido"></span>
                                  </label>
                                  <div class="input-group has-validation">
                                      <input type="text" name="email_confirmar" class="form-control mb-3" id="_email_confirmar" value="{{isset($usuario_edit->email) ? $usuario_edit->email : ''}}" placeholder="micorreo@midominio.com" maxlength="50" onpaste="return false">
                                      <div class="invalid-feedback">
                                          Confirme correo electrónico
                                      </div>
                                  </div>
                              </div>

                              <div class="form-group col-md-6">
                                 <label for="_celular " class="form-label"> 
                                    Número personal: <span class="text-danger fs-5" id="_celularRequerido"></span>
                                 </label>
                                 <div class="input-group has-validation">
                                    <input type="text" class="form-control mb-3" name="celular" id="_celular" value="{{old('celular', isset($usuario_edit->no_personal) ? $usuario_edit->no_personal : '')}}" maxlength="10" placeholder="5501234567">
                                    <div class="invalid-feedback">
                                          Ingrese número celular
                                    </div>
                                 </div>
                              </div>                           
                              
                              <div class="form-group col-md-6">
                                 <label for="_estado " class="form-label"> 
                                    {{isset($bloqueo) || auth()->user()->perfil === 'administrador' ? 'Entidad:' : 'Seleccione entidad:'}} <span class="text-danger fs-5" id="_estadoRequerido"></span>
                                  </label>                                  
                                  <select name="estado" id="_estado" class="col-12 form-select mb-3" onchange="obtenerCcls(this,1); habilitarAgregar()" value="{{old('estado')}}" {{(isset($bloqueo) && $bloqueo === true) || auth()->user()->perfil === 'administrador' ? 'disabled': ''}}>
                                    <option> Seleccione entidad</option> 
                                    @foreach ($estados as $estado)
                                       @if(isset($usuario_edit) && $estado_nombre[0]->nombre === $estado)
                                          <option value="{{$estado}}" selected>{{$estado}}</option>
                                       @elseif(auth()->user()->perfil === 'administrador')
                                          <option value="{{$estado}}" selected>{{$estado}}</option>
                                       @else
                                             <option value="{{$estado}}">{{$estado}}</option>
                                       @endif
                                    @endforeach
                                  </select>
                              </div>

                              <div class="form-group col-md-6">
                                 <label for="_oficina " class="form-label">
                                    {{isset($bloqueo) || auth()->user()->perfil === 'administrador' ? 'Oficina:' : 'Seleccione oficina:'}}
                                  </label>
                                  <select name="oficina" id="_oficina" class="col-12 form-select mb-3" value="{{old('oficina', isset($usuario_edit->id_ccls) ? $usuario_edit->id_ccls : '')}}" {{(isset($bloqueo) && $bloqueo == true) || auth()->user()->perfil === 'administrador' ? 'disabled': ''}}>
                                    <option> Seleccione oficina </option>                                    
                                  </select>                                                                                                     
                              </div>                              

                           </div>                                                                         
                           <hr>
                           <div class="row">
                              <div class="form-group col-md-6">
                                 <label for="_perfil " class="form-label"> 
                                    {{isset($bloqueo) ? 'Perfil:' : 'Seleccione perfil:'}} <span class="text-danger fs-5" id="_perfilRequerido"></span>                                  
                                  </label>
                                  <select name="perfil" id="_perfil" class="col-12 form-select mb-3" value="{{old('perfil', isset($usuario_edit->perfil) ? $usuario_edit->perfil : '')}}" {{ (isset($bloqueo) && $bloqueo == true) || (isset($usuario_edit->perfil)) && $usuario_edit->perfil == 'superusuario'  ? 'disabled': ''}}>
                                    <option> Seleccione perfil </option> 
                                      @foreach ($roles as $perfil)
                                          @if(isset($usuario_edit) && $usuario_edit->perfil == $perfil)
                                             <option value="{{$perfil}}" selected>{{ucfirst(trans($perfil))}}</option>
                                          @elseif($perfil !== 'superusuario' && (isset($usuario_edit->perfil) && $usuario_edit->perfil != 'superusuario'))
                                             <option value="{{$perfil}}">{{ucfirst(trans($perfil))}}</option>
                                          @elseif($perfil != 'superusuario')
                                             <option value="{{$perfil}}">{{ucfirst(trans($perfil))}}</option>
                                          @endif 
                                      @endforeach
                                  </select>                                                                                                     
                              </div>                   
                              
                              @if(isset($bloqueo) && $bloqueo == true ? 'disabled': '')
                                 <input type="hidden" name="estado" id="_estado" value="{{$usuario_edit->id_estado}}">
                                 <input type="hidden" name="oficina" id="_oficina" value="{{$usuario_edit->id_ccls}}">
                                 <input type="hidden" name="perfil" id="_perfil" value="{{$usuario_edit->perfil}}">
                                 <input type="hidden" name="id" id="_id" value="{{$usuario_edit->id}}">
                              @elseif(auth()->user()->perfil === 'administrador')
                                 <input type="hidden" name="estado" id="_estado" value="{{auth()->user()->id_estado}}">
                                 <input type="hidden" name="oficina" id="_oficina" value="{{auth()->user()->id_ccls}}">
                              @endif

                              <div class="form-group col-md-6">
                                 <label for="_password" class="form-label"> 
                                 Password: <span class="text-danger fs-5" id="_passwordRequerido"></span>
                                 </label>
                                 <div class="input-group has-validation">
                                    <input type="password" class="form-control mb-3" name="password" id="_password" value="{{old('password')}}" placeholder="" maxlength="50">
                                    <div class="invalid-feedback">
                                          Ingrese password
                                    </div>
                                 </div>
                              </div>

                              <div class="form-group col-md-6">
                                 <label for="_password_confirmacion" class="form-label"> 
                                    Confirmar password: <span class="text-danger fs-5" id="_password_confirmacionRequerido"></span>
                                 </label>
                                 <div class="input-group has-validation">
                                    <input type="password" class="form-control mb-3" name="password_confirmacion" id="_password_confirmacion" placeholder="" maxlength="50">
                                    <div class="invalid-feedback">
                                          Ingrese password de confirmacion
                                    </div>
                                 </div>
                              </div>

                              <div class="form-group col-md-6" id="div_buzon">
                                 <label for="_buzon_opcional" class="form-label"> 
                                    Buzón: <span class="text-danger fs-5" id="_buzonOpcionalRequerido"></span>
                                  </label>
                                  <div class="input-group has-validation">
                                      <input type="text" name="buzon" class="form-control mb-3" id="_buzon" value="{{isset($usuario_edit->buzon) ? $usuario_edit->buzon : ''}}" placeholder="micorreo@midominio.com" maxlength="50" onpaste="return false">
                                      <div class="invalid-feedback">
                                          Buzón
                                      </div>
                                  </div>
                              </div>   
                           </div>

                           
                           <input type="hidden" name="bloqueo" id="_bloqueo" value="{{isset($bloqueo) && $bloqueo == true ? true : false}}">
                           <div class="d-flex">                                        
                              <div class="p-2 flex-grow-1 justify-content-star text-center">
                                  <button type="submit" class="btn btn-primary btn-primary-ccls rounded-pill" id="btnAgregar" onclick="mensajeProcesando()" disabled>{{isset($usuario_edit->nombre) ? 'Actualizar' : 'Agregar' }}</button>
                              </div>
                          </div>        
                        </form>                   
                     </div>
                  </div>                      
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   window.addEventListener('load', habilitarAgregar);
   let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.

   habilitaBoton = document.getElementById('btnAgregar'); // validarmos si dejamos pasar el botón

   let _nombre = document.getElementById('_nombre');
   let _apellidos = document.getElementById('_apellidos');
   let _email = document.getElementById('_email');
   let _email_confirmar = document.getElementById('_email_confirmar');
   let _celular = document.getElementById('_celular');
   let _estado = document.getElementById('_estado');
   let _oficina = document.getElementById('_oficina');
   let _perfil = document.getElementById('_perfil');
   let _password = document.getElementById('_password');
   let div_buzon = document.getElementById('div_buzon');
   let _buzon = document.getElementById('_buzon');
   let _password_confirmacion = document.getElementById('_password_confirmacion');

   //default, ocultamos buzon
   div_buzon.style.display = 'none';

   //En caso de editar usuario tipo administrador
   @if(isset($usuario_edit) && $usuario_edit->perfil === 'administrador')
      div_buzon.style.display = 'block';
   @else
      div_buzon.style.display = 'none';
   @endif

   //Obtenemos los estados desde la edición   
   @if(isset($id) || auth()->user()->perfil === 'administrador')
      _nombreEstado = @json($estado_nombre);
      if(_nombreEstado[0].nombre != undefined){
         entEdit = _nombreEstado[0].nombre; 
         obtenerCcls(entEdit, 0);
      }      
   @endif
   

   /* Script para obtener estados y oficinas Inicio */
   function obtenerCcls(selectEstado,desde){  
      
      if(desde == 1){
      entidadSeleccionada = selectEstado.value;
      }

      if(desde == 0){
      entidadSeleccionada = selectEstado;
      }
      
      fetch(`../../../ccls/${entidadSeleccionada}/oficinas`)
      .then(res => {
      return res.json();
      })
      .then(function (resCcls){
      listarCclsOficinas(resCcls);
      })
   }

   /* Función para mostrar las oficinas */
   function listarCclsOficinas(ccls){      
         let cclOficinas = document.getElementById('_oficina');         
         limpiarCclsOficinascl(cclOficinas);
         ccls.forEach(e => {            
            let direccion_corta = e.direccion.split(',');
            let opcionCcl = document.createElement('option');
            @if(isset($id))
               if( "{{$usuario_edit->id_ccls}}" != '' && "{{$usuario_edit->id_ccls}}" == e.id){
                  opcionCcl.setAttribute('selected', true);
               }
            @elseif(auth()->user()->perfil === 'administrador')
               if("{{auth()->user()->id_ccls}}" == e.id){
                  opcionCcl.setAttribute('selected', true);
               }
            @endif
                        
            opcionCcl.value = e.id;
            opcionCcl.innerHTML = e.ambito + ': ' + e.municipio + ' - ' + direccion_corta[0] + ', ' + direccion_corta[1];
            cclOficinas.append(opcionCcl);
         });
   }
   
   /* Función para limpiar oficinas cuando se cambia de estado */
   function limpiarCclsOficinascl(cclOficinas){
      while (cclOficinas.options.length > 0){
         cclOficinas.remove(0);
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
            }
         break;        
         case 'input':            
            if(valorCampo.length < 4){
               document.getElementById(campoRequerido).innerHTML = `requerido`;
            }else{
               document.getElementById(campoRequerido).innerHTML = '';
            }
         break;
         case 'password':
            if(valorCampo.length < 8){
               document.getElementById(campoRequerido).innerHTML = `minimo 8 caracateres`;
            }else{
               document.getElementById(campoRequerido).innerHTML = '';            
            }
            break;
         case 'repassword':            
            if(valorCampo != _password.value){
               document.getElementById(campoRequerido).innerHTML = `no coincide`;
            }else{
               document.getElementById(campoRequerido).innerHTML = '';
            }
         break;
         case 'reemail':
            if(valorCampo != _email.value){
               document.getElementById(campoRequerido).innerHTML = `no coincide`;
            }else{
               document.getElementById(campoRequerido).innerHTML = '';
            }
         break;
         }
      habilitarAgregar();
   }

   /* Validamos expresión correo */
   const validarCampoEmail = e => {
      _email_confirmar.value = '';
      const campo = e.target;
      const valorCampo = e.target.value.trim();
      const regexEmail = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/);
      if(valorCampo.length > 0 && !regexEmail.test(valorCampo)){
         document.getElementById('_emailRequerido').innerHTML = `email no válido`;
      }else{
         document.getElementById('_emailRequerido').innerHTML = '';
         _buzon.value = valorCampo;
      }
      habilitarAgregar();
   }

   /* Validamos expresión correo para buzón*/
   const validarCampoBuzon = e => {      
      const campo = e.target;
      const valorCampo = e.target.value.trim();
      const regexEmail = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/);
      if(valorCampo.length > 0 && !regexEmail.test(valorCampo)){
         document.getElementById('_buzonOpcionalRequerido').innerHTML = `email no válido`;
      }else{
         document.getElementById('_buzonOpcionalRequerido').innerHTML = '';
         _buzon.value = valorCampo;
      }
      habilitarAgregar();
   }

   /* Validamos número celular */
   const validarCampoCelular = e => {
      const campo = e.target;
      const valorCampo = e.target.value.trim();
      const regexCel = new RegExp(/^[0-9]{10}$/);
      if(valorCampo.length > 0 && !regexCel.test(valorCampo)){
         document.getElementById('_celularRequerido').innerHTML = `número no válido`;        
      }else{
         document.getElementById('_celularRequerido').innerHTML = '';
      }
      habilitarAgregar();
   }

   /* Se válida nombre */
   _nombre.addEventListener("input", (e) => validarCamposFormulario('input','_nombreRequerido', e));
   /* Se válida apellidos */
   _apellidos.addEventListener("input", (e) => validarCamposFormulario('input','_apellidosRequerido', e));
   /* Se válida correo */    
   _email.addEventListener("input", validarCampoEmail);
   /* Se válida buzón */
   _buzon.addEventListener("input", validarCampoBuzon);
   /* Se válida correo de confirmación */    
   _email_confirmar.addEventListener("input", (e) => validarCamposFormulario('reemail', '_email_confirmarRequerido', e));
   /* Se válida entidad selecionada */
   _estado.addEventListener("change", (e) => validarCamposFormulario('select','_estadoRequerido', e));
   /* Se válida número celular */    
   _celular.addEventListener("input", validarCampoCelular);
   /* Se válida usuario */   
   /* Se válida perfil selecionado */
   _perfil.addEventListener("change", (e) => (validarCamposFormulario('select','_perfilRequerido', e), mostrarBuzon(e)));
   /* Se válida password */
   _password.addEventListener("input", (e) => validarCamposFormulario('password','_passwordRequerido', e));
   /* Se válida repetri password */
   _password_confirmacion.addEventListener("input", (e) => validarCamposFormulario('repassword','_password_confirmacionRequerido', e));

   function mostrarBuzon(e){
      //Verificamos si el usuario será administrador, siendo así, mostramos buzon
      if(e.target.value == 'administrador'){
         div_buzon.style.display = 'block';
      }else{
         div_buzon.style.display = 'none';
      }
   }

   /* función para verificar si habilitamos botón agendar cita */
   function habilitarAgregar(){
      valBoton = 0;
      valNombre = _nombre.value;
      valApellidos = _apellidos.value;
      valEmail = _email.value;
      valBuzon = _buzon.value;
      valEmailConfirmar = _email_confirmar.value;
      valCelular = _celular.value;
      valEstado = _estado.value;
      valOficina = _oficina.value;
      /* valUsuario = _usuario.value; */
      valPerfil = _perfil.value;
      valPassword = _password.value;
      valPassword_confirmacion = _password_confirmacion.value;
      const regexEmail = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/);
      const regexCel = new RegExp(/^[0-9]{10}$/);      

      if(valNombre == '' || valNombre.length < 4){
         valBoton++
      }

      if(valApellidos == '' || valApellidos.length < 4){
         valBoton++
      }

      if(valEmail == '' || !regexEmail.test(valEmail) || valEmail != valEmailConfirmar){
         valBoton++
      }

      if(valBuzon == '' || !regexEmail.test(valBuzon)){
         valBoton++
      }

      if(valCelular == '' || valCelular.length < 10 || !regexCel.test(valCelular)){
         valBoton++
      }
      
      if(valEstado == '' || valEstado == 'Seleccione entidad'){
         valBoton++
      }

      if(valOficina == '' || valOficina == 'Seleccione oficina'){
         valBoton++
      }

      if(valPerfil == '' || valPerfil == 'Selecciona perfil'){
         valBoton++
      }

      if(valPassword.length < 8 || valPassword != valPassword_confirmacion){
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
         if(valEmailConfirmar != valEmail && valBoton != 0){
            validaBoton(true, 'Complete el formulario');
         }else if(valBoton != 0 && valPassword != valPassword_confirmacion){
            validaBoton(true, 'Complete el formulario');
         }else if(valBoton != 0 && (valNombre == '' || valNombre.length < 4 || valApellidos == '' || valApellidos.length < 4 || valCelular == '' || valCelular.length < 10 || !regexCel.test(valCelular) || valEstado == '' || valEstado == 'Selecciona una entidad' || valOficina == '' || valOficina == 'Selecciona una oficina')){
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

</script>
</x-app-layout>
