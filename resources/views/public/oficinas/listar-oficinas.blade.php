<x-app-layout layout="dashboardv" :assets="$assets ?? []">
    <div class="iq-navbar-header" style="height: auto;">
        <div class="container-fluid iq-container dorada">
           <div class="row">
              <div class="d-flex justify-content-between">                              
                    <h3 class="card-title">Oficinas</h3>
                    <div class="text-white">
                    @if(auth()->user()->perfil == 'superusuario')
                    <a href="{{route('agregaroficina')}}" class="btn btn-sm btn-primary btn-primary-ccls" role="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Agregar oficina">
                        <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.33 2H16.66C20.06 2 22 3.92 22 7.33V16.67C22 20.06 20.07 22 16.67 22H7.33C3.92 22 2 20.06 2 16.67V7.33C2 3.92 3.92 2 7.33 2ZM12.82 12.83H15.66C16.12 12.82 16.49 12.45 16.49 11.99C16.49 11.53 16.12 11.16 15.66 11.16H12.82V8.34C12.82 7.88 12.45 7.51 11.99 7.51C11.53 7.51 11.16 7.88 11.16 8.34V11.16H8.33C8.11 11.16 7.9 11.25 7.74 11.4C7.59 11.56 7.5 11.769 7.5 11.99C7.5 12.45 7.87 12.82 8.33 12.83H11.16V15.66C11.16 16.12 11.53 16.49 11.99 16.49C12.45 16.49 12.82 16.12 12.82 15.66V12.83Z" fill="currentColor"></path>
                        </svg>
                    </a>
                    @endif
                 </div>
              </div>
           </div>
            <div class="col-sm-12">
                <div class="card">
                <div class="card-body px-0">
                    <div class="table-responsive columna-fija">
                        <table id="oficinas-list-table" class="table table-striped" role="grid" data-toggle="data-table">
                            <thead>
                                <tr class="ligth">
                                    <th scope="col">No.</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Ámbito</th>
                                    <th scope="col">Domicilio</th>
                                    {{-- <th scope="col">Latitud</th>
                                    <th scope="col">Longitud</th> --}}
                                    <th scope="col">Contacto</th>
                                    <th scope="col">CP</th>
                                    <th scope="col">Zona horaria</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Registrado</th>
                                    @can('mostrar_acciones_citas')
                                        <th>Acciones</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($oficinas as $oficina)
                            <tr align="left">
                                <th scope="row">
                                    {{ $oficina->id }}
                                </th>
                                <td scope="row">
                                    {{ $oficina->estado }}
                                </td>                                    
                                <td>
                                    {{ $oficina->municipio }}
                                </td>
                                <td>
                                    {{ $oficina->ambito }}
                                </td>                              
                                <td>
                                    {{ $oficina->direccion }}
                                </td>
                               {{--  <td>
                                    {{ $oficina->lat }}
                                </td>
                                <td>
                                    {{ $oficina->long }}
                                </td> --}}
                                <td>
                                    {{ $oficina->contacto }}
                                </td>
                                <td>
                                    {{ $oficina->cp }}
                                </td>
                                <td>
                                    {{ $oficina->zona_horaria }}
                                </td>
                                <td>
                                    {{ $oficina->status === 1 ? 'activo' : 'bloqueado'}}
                                </td>
                                <td>
                                    <p>{{date("d-m-Y", strtotime($oficina->created_at));}}</p>
                                </td>
                                @can('mostrar_acciones_citas')
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            @can('editar_oficina')
                                            <button type="button" class="btn btn-primary-ccls btn-md btn-icon" >
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar" data-original-title="editar" href="{{route('editaroficina', $oficina->id)}}">
                                                <span class="btn-inner">
                                                    <svg width="32" class="text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.3764 20.0279L18.1628 8.66544C18.6403 8.0527 18.8101 7.3443 18.6509 6.62299C18.513 5.96726 18.1097 5.34377 17.5049 4.87078L16.0299 3.69906C14.7459 2.67784 13.1541 2.78534 12.2415 3.95706L11.2546 5.23735C11.1273 5.39752 11.1591 5.63401 11.3183 5.76301C11.3183 5.76301 13.812 7.76246 13.8651 7.80546C14.0349 7.96671 14.1622 8.1817 14.1941 8.43969C14.2471 8.94493 13.8969 9.41792 13.377 9.48242C13.1329 9.51467 12.8994 9.43942 12.7297 9.29967L10.1086 7.21422C9.98126 7.11855 9.79025 7.13898 9.68413 7.26797L3.45514 15.3303C3.0519 15.8355 2.91395 16.4912 3.0519 17.1255L3.84777 20.5761C3.89021 20.7589 4.04939 20.8879 4.24039 20.8879L7.74222 20.8449C8.37891 20.8341 8.97316 20.5439 9.3764 20.0279ZM14.2797 18.9533H19.9898C20.5469 18.9533 21 19.4123 21 19.9766C21 20.5421 20.5469 21 19.9898 21H14.2797C13.7226 21 13.2695 20.5421 13.2695 19.9766C13.2695 19.4123 13.7226 18.9533 14.2797 18.9533Z" fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                            </button>
                                            @endcan
                                            
                                            @can('eliminar_oficina')
                                                @if(auth()->user()->perfil == 'superusuario')
                                                    <button type="button" class="btn btn-danger btn-md btn-icon" onclick="eliminarOficina( {{ $oficina->id }} )">
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
                                            @endcan
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

        function eliminarOficina(id){

            Swal.fire({
                title: '¿Está seguro?',
                text: 'Se cancelarán todas las citas y se eliminaran a los usuarios de esta oficina.',
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

                    fetch("{{route('eliminaroficina')}}",
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
                                title: "Oficina eliminada con éxito",
                                text: "Se notificó a los usuarios por correo eletrónico.",
                                icon: "success",
                                confirmButtonColor: alertasColor,
                                allowEnterKey: true,
                                allowOutsideClick: false,
                                confirmButtonText: "Aceptar",
                                iconColor: alertasColor,
                            }).then((result) => {

                                if (result.isConfirmed) {

                                    location.reload();

                                }
                                
                            });

                        }else if(res.status === 404){

                            Swal.fire({
                                title: "Opps!",
                                text: 'Oficina previamente eliminada.',
                                icon: "info",
                                confirmButtonColor: alertasColor,
                                allowEnterKey: true,
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
                                text: 'No fue posible eliminar oficina, intente más tarde.',
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