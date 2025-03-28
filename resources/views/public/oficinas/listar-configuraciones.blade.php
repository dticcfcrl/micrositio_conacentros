<x-app-layout layout="dashboardv" :assets="$assets ?? []">
    <div class="iq-navbar-header" style="height: auto;">
        <div class="container-fluid iq-container dorada">
           <div class="row">
              <div class="d-flex justify-content-between">                              
                    <h3 class="card-title">Lista de Configuraciones</h3>
                    <div class="text-white">
                    <a href="{{route('disponibilidad')}}" class="btn btn-sm btn-primary btn-primary-ccls" role="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 10L3.29289 10.7071L2.58579 10L3.29289 9.29289L4 10ZM21 18C21 18.5523 20.5523 19 20 19C19.4477 19 19 18.5523 19 18L21 18ZM8.29289 15.7071L3.29289 10.7071L4.70711 9.29289L9.70711 14.2929L8.29289 15.7071ZM3.29289 9.29289L8.29289 4.29289L9.70711 5.70711L4.70711 10.7071L3.29289 9.29289ZM4 9L14 9L14 11L4 11L4 9ZM21 16L21 18L19 18L19 16L21 16ZM14 9C17.866 9 21 12.134 21 16L19 16C19 13.2386 16.7614 11 14 11L14 9Z" fill="#ffffff"/>
                        </svg>
                    </a>
                 </div>
              </div>
           </div>
            <div class="col-sm-12">
                <div class="card">
                <div class="card-body px-0">
                    <div class="table-responsive columna-fija sticky-top">
                        <table id="oficinas-list-table" class="table table-striped" role="grid" data-toggle="data-table">
                            <thead>
                                <tr class="ligth">
                                    <th scope="col">No.</th>
                                    <th scope="col">Fecha de aplicación</th>
                                    <th scope="col">Fecha de creación</th>
                                    <th scope="col">Conciliadores permitidos</th>
                                    <th scope="col">Auxiliares permitidos</th>
                                    <th scope="col">Lunes</th>
                                    <th scope="col">Martes</th>
                                    <th scope="col">Miércoles</th>
                                    <th scope="col">Jueves</th>
                                    <th scope="col">Viernes</th>
                                    <th scope="col">Horario de inicio</th>
                                    <th scope="col">Horario de cierre</th>
                                    <th scope="col">Minutos de atención</th>
                                    <th scope="col">Horario de alimentos inicio</th>
                                    <th scope="col">Horario de alimentos fin</th>
                                    <th scope="col">Meses a mostrar</th>
                                    @can('mostrar_acciones_citas')
                                        <th>Acciones</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($configuraciones as $config)
                            <tr align="left">
                                <th scope="row">
                                    {{ $config->id }}
                                </th>
                                <td>
                                    <p>{{date("d-m-Y", strtotime($config->aplica))}}</p>
                                </td>
                                <td>
                                    <p>{{date("d-m-Y", strtotime($config->created_at));}}</p>
                                </td>
                                <td scope="row">
                                    {{ $config->total_conciliadores }}
                                </td>                                    
                                <td>
                                    {{ $config->total_auxiliares }}
                                </td>
                                <td>
                                    {{ $config->status_lunes === 1 ? 'Habilitado' : 'Deshabilitado' }}
                                </td>
                                <td>
                                    {{ $config->status_martes === 1 ? 'Habilitado' : 'Deshabilitado' }}
                                </td>
                                <td>
                                    {{ $config->status_miercoles === 1 ? 'Habilitado' : 'Deshabilitado' }}
                                </td>
                                <td>
                                    {{ $config->status_jueves === 1 ? 'Habilitado' : 'Deshabilitado' }}
                                </td>
                                <td>
                                    {{ $config->status_viernes === 1 ? 'Habilitado' : 'Deshabilitado' }}
                                </td>
                                <td>
                                    {{ $config->hora_cita_inicio}}
                                </td>
                                <td>
                                    {{ $config->hora_cita_fin }}
                                </td>                              
                                <td>
                                    {{ $config->minutos_cita }}
                                </td>
                                <td>
                                    {{ $config->hora_comida_inicio }}
                                </td>
                                <td>
                                    {{ $config->hora_comida_fin }}
                                </td>
                                <td>
                                    {{ $config->meses_cita }}
                                </td>
                                @can('mostrar_acciones_citas')
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            @if($fechaActual > $config->cita_Fecha)

                                                <button type="button" class="btn btn-danger btn-md btn-icon" onclick="eliminarConfig( {{ $config->id }} )">
                                                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Eliminar" data-original-title="Eliminar" data-bs-toggle="modal" data-bs-target="#Eliminar" disabled>
                                                        <span class="btn-inner">
                                                            <svg width="32" class="text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                                <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                @endcan
                            </tr>  
                            @endforeach
                                
                            </tbody>
                        </table>
                    </div> 
                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.

        function eliminarConfig(id){

            Swal.fire({
                title: '¿Está seguro?',
                text: 'Se eliminará está configuración y no se podrá visualizar al momento de agendar citas.',
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

                    const token = document.querySelector('meta[name="csrf-token"]').content; //Obtenemos el token    

                    fetch(`configuracion/${id}/eliminar`, 
                    {
                        method: "DELETE",
                        body: id,
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

                        if(res.status === 200){

                            Swal.fire({
                                title: "Configuracion eliminada con éxito",
                                icon: "success",
                                confirmButtonColor: alertasColor,
                                allowEnterKey: false,
                                allowOutsideClick: false,
                                confirmButtonText: "Aceptar",
                                iconColor: alertasColor,
                            }).then((result) => {

                                if (result.isConfirmed) {

                                    location.reload();

                                }
                                
                            });

                        }else{

                            Swal.fire({
                                title: "Opps!",
                                text: 'No fue posible eliminar configuración, intente más tarde.',
                                icon: "error",
                                confirmButtonColor: alertasColor,
                                allowEnterKey: true,
                                allowOutsideClick: true,
                                confirmButtonText: "Aceptar",
                                iconColor: alertasColor,
                            });
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

    <!-- Se coloca última columna fija-->
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
</x-app-layout>