<x-app-layout layout="dashboardv" :assets="$assets ?? []">
    <div class="iq-navbar-header" style="height: auto;">
        <div class="container-fluid iq-container dorada">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h3>Configure disponibilidad</h3>
                    <form>
                        @csrf
                        <div class="col-md-12 col-lg-12 dorada">
                            <div class="row row-cols-1">               
                                
                                <!--Horarios de apertura y cierre de oficina -->
                                <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <p class="card-text">Horario de atención</p>
                                    <div class="card mb-3">                    
                                        <div class="card-bod px-0 m-2">
                                            <div class="container-fluid dorada">
                                                <div class="text-white">
                                                    <label class="form-label">Apertura</label>
                                                    <select class="col-12 form-select mb-3" name="apertura" id="_apertura">
                                                        <option>Seleccione horario   </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="container-fluid dorada">
                                                <div class="text-white">
                                                    <label class="form-label">Cierre</label>
                                                    <select class="col-12 form-select mb-3" name="cierre" id="_cierre">
                                                    </select>
                                                </div>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <!--Horarios de inicio y termino de comida -->
                                <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <p class="card-text">Horario de alimentos</p>
                                    <div class="card mb-3">                    
                                        <div class="card-bod px-0 m-2">
                                            <div class="container-fluid dorada">
                                                <div class="text-white">
                                                    <label class="form-label">Inicia</label>
                                                    <select class="col-12 form-select mb-3" name="comida_inicio" id="_comidainicio">
                                                        <option>Seleccione horario   </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="container-fluid dorada">
                                                <div class="text-white">
                                                    <label class="form-label">Termina</label>
                                                    <select class="col-12 form-select mb-3" name="comida_fin" id="_comidafin">                                                
                                                    </select>
                                                </div>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <!-- Días de lunes a viernes -->
                                <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <p class="card-text">Días disponibles</p>
                                    <ul class="list-inline m-0 p-0 mb-2">
                                        <li class="card" data-aos="fade-up" data-aos-delay="105">
                                            <div class="card-body p-2">
                                                <div class="progress-widget">
                                                    <div class="progress-detail">
                                                        <!-- <span class="mb-2 text-muted">Días</span>-->
                                                        <div class="row">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="dia1" role="switch" value="lunes" id="_checklunes">
                                                                <label class="form-check-label text-black">
                                                                Lunes
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="dia2" role="switch" value="martes" id="_checkmartes">
                                                                <label class="form-check-label text-black">
                                                                Martes
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="dia3" role="switch" value="miércoles" id="_checkmiércoles">
                                                                <label class="form-check-label text-black">
                                                                Miércoles
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="dia4" role="switch" value="jueves" id="_checkjueves">
                                                                <label class="form-check-label text-black">
                                                                Jueves
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="dia5" role="switch" value="viernes" id="_checkviernes">
                                                                <label class="form-check-label text-black">
                                                                Viernes
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Minutos de atención -->
                                <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <p class="card-text">Minutos de atención</p>
                                    <div class="card mb-3">                    
                                        <div class="card-bod px-0 m-2">
                                            <div class="container-fluid dorada">
                                                <label class="form-label">Minutos:</label> <span class="dorada" id='_totalminutos'></span>
                                                <input type="range" class="form-range primary-ccls dorada" min="15" max="45" step="15" value="15" id="_rangominutos">
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>   
                                <!-- Meses a mostar al usaurio para agendar cita -->
                                <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <p class="card-text">Meses permitidos para agendar cita</p>
                                    <div class="card mb-3">                    
                                        <div class="card-bod px-0 m-2">
                                            <div class="container-fluid dorada">
                                                <label class="form-label">Meses:</label> <span class="dorada" id='_totalmeses'></span>
                                                <input type="range" class="form-range primary-ccls dorada" min="1" max="12" step="1" value="1" id="_rangomeses">
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>                                 
                                <div class="d-flex">
    
                                    <div class="p-2 flex-grow-1 justify-content-star text-center" data-aos="fade-up">
                                        <a href="{{route('listarconfig')}}" role="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver más">
                                            <button type="button" class="btn btn-secondary rounded-pill">
                                                Ver configuraciones
                                            </button>
                                        </a>
                                    </div>
    
                                    <div class="p-2 flex-grow-1 justify-content-end text-center" data-aos="fade-up">
                                        <button type="button" class="btn btn-primary btn-primary-ccls rounded-pill" id="_guardarconfig" onclick="validaConfiguracion(); mensajeProcesando(); ">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    
        let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.
        let hayConfiguraciones;
        let disponbilidadOficina = @json($disponbilidadOficina); // Obtenemos todos los datos de configuración de oficina

        // Válidamos si la oficina tiene al menos una configuración, de lo contrario es nueva.
        if ( disponbilidadOficina != null ){
            hayConfiguraciones = true;
        }else{
            hayConfiguraciones = false;
        }
    
        let horas = [
            '08:00',
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00',
            '18:00',
            '19:00',
            '20:00',
        ];
    
        window.onload = function() {
            verificarBoton();
            obtenerConfiguracion();
        }
        
        //Logica de checkbox
        /* const checkTodos = document.getElementById('SelecionarTodo'); //btn todos */
        let checkLunes = document.getElementById('_checklunes'); //btn dias
        let checkMartes = document.getElementById('_checkmartes'); //btn dias
        let checkMiercoles = document.getElementById('_checkmiércoles'); //btn dias
        let checkJueves = document.getElementById('_checkjueves'); //btn dias
        let checkViernes = document.getElementById('_checkviernes'); //btn dias    
        let cclApertura = document.getElementById('_apertura');
        let cclCierre = document.getElementById('_cierre');
        let cclComidaInicio = document.getElementById('_comidainicio');
        let cclComidaFin = document.getElementById('_comidafin');
        let rangoMinutos = document.getElementById('_rangominutos');
        let totalMinutos = document.getElementById('_totalminutos');
        let rangoMeses = document.getElementById('_rangomeses');
        let totalMeses = document.getElementById('_totalmeses');
        let guardarCongif = document.getElementById('_guardarconfig');
    
        function obtenerConfiguracion(){        
            //Mostramos campos llenos de acuerdo a lo guardado
            if(disponbilidadOficina){
                checkLunes.checked = disponbilidadOficina.status_lunes;
                checkMartes.checked = disponbilidadOficina.status_martes;
                checkMiercoles.checked = disponbilidadOficina.status_miercoles;
                checkJueves.checked = disponbilidadOficina.status_jueves;
                checkViernes.checked = disponbilidadOficina.status_viernes;
                rangoMinutos.value = disponbilidadOficina.minutos_cita;
                totalMinutos.innerHTML = disponbilidadOficina.minutos_cita;
                rangoMeses.value = disponbilidadOficina.meses_cita;
                totalMeses.innerHTML = disponbilidadOficina.meses_cita;
                
    
                //Mandamos los valores en caso de no ser tocados
                guardarConfiguracion(checkLunes.value, disponbilidadOficina.status_lunes);
                guardarConfiguracion(checkMartes.value, disponbilidadOficina.status_martes);
                guardarConfiguracion(checkMiercoles.value, disponbilidadOficina.status_miercoles);
                guardarConfiguracion(checkJueves.value, disponbilidadOficina.status_jueves);
                guardarConfiguracion(checkViernes.value, disponbilidadOficina.status_viernes);
            
            }else{
                rangoMinutos.value = 15;
                totalMinutos.innerHTML = 15;
                rangoMeses.value = 1;
                totalMeses.innerHTML = 1;
            }
    
            //Creamos las opciones de hora
            horas.forEach(h => {
                    cclOption = document.createElement('option');
                    if(disponbilidadOficina && h+':00' == disponbilidadOficina.hora_cita_inicio && disponbilidadOficina){
                        cclOption.setAttribute('selected', true);
                    }
                    cclOption.value = h;
                    cclOption.innerHTML = h;
                    cclApertura.append(cclOption);        
                });
    
                horas.forEach(h => {
                    cclOption = document.createElement('option');
                    if(disponbilidadOficina && h+':00' == disponbilidadOficina.hora_cita_fin && disponbilidadOficina){
                        cclOption.setAttribute('selected', true);
                    }
                    cclOption.value = h;
                    cclOption.innerHTML = h;
                    if(disponbilidadOficina && h > disponbilidadOficina.hora_cita_inicio){
                        cclCierre.append(cclOption);
                    }            
                });
    
                //Creamos opciones de comida
                horas.forEach(h => {            
                    cclComidasOption = document.createElement('option');
                    if(disponbilidadOficina && h+':00' == disponbilidadOficina.hora_comida_inicio && disponbilidadOficina){
                        cclComidasOption.setAttribute('selected', true);
                    }
                    cclComidasOption.value = h;
                    cclComidasOption.innerHTML = h;        
                    if(h > '12:30' && h < '16:30'){
                        cclComidaInicio.append(cclComidasOption);            
                    }
                });
    
                horas.forEach(h => {
                    cclOption = document.createElement('option');
                    if(disponbilidadOficina && h+':00' == disponbilidadOficina.hora_comida_fin && disponbilidadOficina){
                        cclOption.setAttribute('selected', true);
                    }   
                    cclOption.value = h;
                    cclOption.innerHTML = h;
                    if(disponbilidadOficina && h > disponbilidadOficina.hora_comida_inicio && h < '16:30'){
                        cclComidaFin.append(cclOption);
                    }            
                });
    
        }
    
        //Check para bloquear o desbloquear
        checkLunes.addEventListener('change', (event) => {
            //Obtenemos dia disponibilidad
            var bloquearDia = event.target.checked;
            var semanaDia = event.target.value;
            guardarConfiguracion(semanaDia, bloquearDia);
        });
    
        checkMartes.addEventListener('change', (event) => {
            //Obtenemos dia disponibilidad
            var bloquearDia = event.target.checked;
            var semanaDia = event.target.value;        
            guardarConfiguracion(semanaDia, bloquearDia);
        });
        
        checkMiercoles.addEventListener('change', (event) => {
            //Obtenemos dia disponibilidad
            var bloquearDia = event.target.checked;
            var semanaDia = event.target.value;        
            guardarConfiguracion(semanaDia, bloquearDia);
        });
    
        checkJueves.addEventListener('change', (event) => {
            //Obtenemos dia disponibilidad
            var bloquearDia = event.target.checked;
            var semanaDia = event.target.value;        
            guardarConfiguracion(semanaDia, bloquearDia);
        });
    
        checkViernes.addEventListener('change', (event) => {
            //Obtenemos dia disponibilidad
            let bloquearDia = event.target.checked;
            let semanaDia = event.target.value;        
            guardarConfiguracion(semanaDia, bloquearDia);
        });
    
        //Horario de oficina
        cclApertura.addEventListener('change', (event) => {
    
            verificarBoton();
            
            apertura = event.target.value;
            limpiarCclHorarios(cclCierre);
    
            horas.forEach(h => {
    
                cclOption = document.createElement('option');
                cclOption.value = h;
                cclOption.innerHTML = h;
                if(h > apertura){
                    cclCierre.append(cclOption);
                }            
            });
    
        });
    
        //Horarios de comida
        cclComidaInicio.addEventListener('change', (event) => {
    
            verificarBoton();
    
            inicio = event.target.value;
            limpiarCclHorarios(cclComidaFin);
    
            horas.forEach(h => {
    
                cclOption = document.createElement('option');
                cclOption.value = h;
                cclOption.innerHTML = h;
                if(h > inicio && h < '16:30'){
                    cclComidaFin.append(cclOption);
                }            
            });
    
        });
    
        //Rango minutos
        rangoMinutos.addEventListener('input', (event) => {
            total = event.target.value; 
            if(total >= 45){
                totalMinutos.innerHTML = 60;
            }else{
                totalMinutos.innerHTML = total;
            }
        });
    
        //Rango meses
        rangoMeses.addEventListener('input', (event) => {
            total = event.target.value;
            totalMeses.innerHTML = total;
        });
     
        let diasDiponibles = []; //Variable para guardar días disponibles
        // Guardamos los dias disponibles
        function guardarConfiguracion(semanaDia, bloquearDia){
            //Creamos el Obbjeto de días disponibles
            dias = {
                'id_ccls': {{auth()->user()->id_ccls}},
                'dia': semanaDia,
                'disponible': bloquearDia
            }
    
            //Validación de dias disponibles
            if(diasDiponibles.length != 0){
                indice = diasDiponibles.findIndex(d => d.dia === semanaDia);
    
                if(indice == -1){                
                    diasDiponibles.push(dias);
                }else{                
                    diasDiponibles.splice(indice, 1, dias)                
                }            
            }else{
                diasDiponibles.push(dias);
            }        
        }
    
        /* Función para limpiar horas cuando se cambia la primera */
        function limpiarCclHorarios(cclHorario){
          while (cclHorario.options.length > 0){
            cclHorario.remove(0);
          }
        }
    
        function verificarBoton(){
    
            texto = 'Seleccione horario';
            if(cclApertura.value == texto || cclComidaInicio.value == texto){            
                if(disponbilidadOficina){
                    guardarCongif.disabled = false;
                }else{
                    guardarCongif.disabled = true;
                }
            }else{
                guardarCongif.disabled = false;
            }
        }
    
        let horarios = []; // Guardaremos los horarios
        /* generamos los intervalos de tiempo entre horas de 15, 30 y 60 mininutos */
        function recorrerHorasMinutos(inicio, fin, inter, comidainicio, comidafin){
            horarios = []; // Guardaremos los horarios
            let intervalo = Number(inter);
            /* let dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes']; */ //En caso de que  los horario 
            /* let dias = diasDiponibles; */
            /* console.log(diasDiponibles); */
    
            //Obtenemos las horas y minutos de apertura y comidas e intervalor de minutos
            let th1 = (new Date("1/1/1960 " + inicio+':00')).getHours();
            let th2 = (new Date("1/1/1960 " + fin+':00')).getHours();
            let tm1 = (new Date("1/1/1960 " + inicio)).getMinutes(); 
            let tc1 = (new Date("1/1/1960 " + comidainicio+':00')).getHours();
            let tc2 = (new Date("1/1/1960 " + comidafin+':00')).getHours();
            /* for(let d = 0; d < 5; d++){ */ //iteramos días (ya no se necesita)
                for(let i = th1; i < th2; i++){ //iteramo las horas
                    for(let j = tm1; j < 60; j + intervalo){ //iteramos los minutos de 15, 30 y 60
                        if(i < 10){
                            if( j < 10){
                            horarios.push({'hora': '0' + i + ':0' + j + ':00', status: true});
                            }else{
                            horarios.push({'hora': '0' + i + ':' + j + ':00', status: true});
                            }          
                        }else{
                            if( j < 10){
                            if(i >= tc1 && i < tc2){
                                horarios.push({'hora': i + ':0' + j + ':00', status: false}); //False para horarios de comida
                            }else{
                                horarios.push({'hora': i + ':0' + j + ':00', status: true});
                            }
                            }else{
                            if(i >= tc1 && i < tc2){
                                horarios.push({'hora': i + ':' + j + ':00', status: false}); //False para horarios de comida
                            }else{
                                horarios.push({'hora': i + ':' + j + ':00', status: true});
                            }
                            }
                        }
                    j = j + intervalo;  //aumentamos los minutos de acuerdo al intervalo de minutos 15, 30 y 60              
                    }
                }
            /* } */
            /* console.log(horarios); */
        }
    
        /* Preguntamos al usuario la fecha en la que podría aplicarse la nueva configuración */
        function validaConfiguracion(){
    
            let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.

            // Variables auxiliares
            let status, created_at, aplica;
    
            //Solicitud de guardado sin refrescar
            //obtenemos solo valores, 15, 30 y 60
            if(rangoMinutos.value >= 45){
                nuevoRangoMinutos = 60;
            }else{
                nuevoRangoMinutos = rangoMinutos.value;
            }
    
            //generamos arreglo de horas por intervalos
            recorrerHorasMinutos(cclApertura.value, cclCierre.value, nuevoRangoMinutos, cclComidaInicio.value, cclComidaFin.value);

            if(hayConfiguraciones){

                // Oficina tiene ya configuraciones
                status = disponbilidadOficina.status;
                created_at = disponbilidadOficina.created_at;
                aplica = disponbilidadOficina.aplica ? disponbilidadOficina.aplica : false;

            }else{

                // es nueva configuración para oficina
                status = true;
                created_at = false;

            }

            //Preparamos los datos a enviar

            data = {
                'diasDiponibles': diasDiponibles,
                'horariosDisponibles': horarios,
                'cclApertura': cclApertura.value,
                'cclCierre': cclCierre.value,
                'cclComidaInicio': cclComidaInicio.value,
                'cclComidaFin': cclComidaFin.value,
                'rangoMinutos': nuevoRangoMinutos,
                'rangoMeses': rangoMeses.value,
                'status': status,
                'aplica': aplica,
                'created_at': created_at
            }
        
            const token = document.querySelector('meta[name="csrf-token"]').content; //Obtenemos el token
    
            /* console.log(data, token); */
    
            //Solicitud de guardado sin refrescar
            fetch("{{route('validaconfiguracion')}}",
            {
                method: "POST",
                /* body: JSON.stringify(horariosDisponibles), */
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token":  token // Pasamos el token CSRF de la etiqueta meta 
                },
                credentials: "same-origin",
            }).then(res => {
                return res.json();
            }).then(function(res){                
    
                if(res.status == 200){
                    
                    // Notificamos la fecha de configuración a aplicar
                    Swal.fire({
                        title: "¿Está seguro?",
                        html: res.text,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: alertasColor,
                        cancelButtonColor: "#6C757D",
                        confirmButtonText: "Sí",
                        cancelButtonText: "No",
                        reverseButtons: true,
                        iconColor: alertasColor,
                        allowEnterKey: false,
                        allowEnterKey: false,
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
                            guardarDisponibilidad(status, created_at, res.data, res.created);

                        }else{
                            window.location.href = "{{route('disponibilidad')}}";
                        }
                    });
    
                }else if(res.status == 201){
    
                    //Hubo un error al momento de configurar usuarios
                    Swal.fire({
                        title: "No fue posible continuar",
                        text: 'Configuración de Conciliadores/Auxiliares no puede ser menor a los registrados.',
                        icon: "info",
                        confirmButtonColor: alertasColor,
                        allowEnterKey: false,
                        allowOutsideClick: false,
                        confirmButtonText: "Aceptar",
                        iconColor: alertasColor,
                    }).then((result) => {                    
                        if (result.isConfirmed) {
                            window.location.href = "{{route('dashboard')}}";
                        }
                    });
    
                }else if(res.status == 202){
    
                    // mandamos a guardar los nuevos cambios de usuarios y/o meses 
                    // no se detectó cambios en nucleo de horarios y días                    
                    guardarDisponibilidad(status, created_at, res.data, res.created);                    
    
                } else if (res.status == 203){

                    // Limite excedido
                    Swal.fire({
                        title: "Límite excedido",
                        text: 'Solo dos configuraciones activas se permiten.',
                        icon: "info",
                        confirmButtonColor: alertasColor,
                        showConfirmButton: true,
                        iconColor: alertasColor,
                        confirmButtonText: "Aceptar",
                        allowEnterKey: true,
                        allowOutsideClick: true                    
                    });
    
                    return;


                }else{
    
                    // no fue posible validar
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
    
                    return;
    
                }
            }); 
            
        }
    
        // Mensaje de guardado
        function guardarDisponibilidad(status, created_at, fechaAplicar, created){
            
            //obtenemos solo valores, 15, 30 y 60
            //generamos arreglo de horas por intervalos
            recorrerHorasMinutos(cclApertura.value, cclCierre.value, nuevoRangoMinutos, cclComidaInicio.value, cclComidaFin.value);

            //Preparamos los datos a enviar
            data = {
                'diasDiponibles': diasDiponibles,
                'horariosDisponibles': horarios,
                'cclApertura': cclApertura.value,
                'cclCierre': cclCierre.value,
                'cclComidaInicio': cclComidaInicio.value,
                'cclComidaFin': cclComidaFin.value,
                'rangoMinutos': nuevoRangoMinutos,
                'rangoMeses': rangoMeses.value,
                'status': status,
                'aplica': fechaAplicar,
                'created_at': created_at,
                'created': created
            };
    
            const token = document.querySelector('meta[name="csrf-token"]').content; //Obtenemos el token
    
            /* console.log(data, token); */
    
            //Solicitud de guardado sin refrescar
            fetch("{{route('guardardisponibilidad')}}",
            {
                method: "POST",
                /* body: JSON.stringify(horariosDisponibles), */
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token":  token // Pasamos el token CSRF de la etiqueta meta 
                },
                credentials: "same-origin",
            }).then(res => {
                return res.json();
            }).then(function(res){
                    
                if(res.status == 200){
    
                    Swal.fire({
                        title: "Configuración guardada",
                        html: res.text,
                        icon: "success",
                        confirmButtonColor: alertasColor,
                        allowEnterKey: false,
                        allowOutsideClick: false,
                        confirmButtonText: "Aceptar",
                        iconColor: alertasColor,
                    }).then((result) => {                    
                        if (result.isConfirmed) {
                            window.location.href = "{{route('dashboard')}}";
                        }
                    });
    
                }else if(res.status === 201){
    
                    Swal.fire({
                        title: "No fue posible continuar",
                        text: 'Configuración de Conciliadores/Auxiliares no puede ser menor a los registrados.',
                        icon: "info",
                        confirmButtonColor: alertasColor,
                        allowEnterKey: true,
                        allowOutsideClick: true,
                        confirmButtonText: "Aceptar",
                        iconColor: alertasColor,
                    }).then((result) => {                    
                        if (result.isConfirmed) {
                            window.location.href = "{{route('dashboard')}}";
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
                        allowEnterKey: false,
                        allowOutsideClick: false                    
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