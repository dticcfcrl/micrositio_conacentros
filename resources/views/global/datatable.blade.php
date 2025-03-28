@push('scripts')
    {{-- {{ $dataTable->scripts() }} --}}
@endpush
<x-app-layout layout="dashboardv" :assets="$assets ?? []">
   <div class="iq-navbar-header" style="height: auto;">
      <div class="container-fluid iq-container dorada">
         <div class="row">
            <div class="d-flex justify-content-between">               
                  <h3 class="card-title">{{ $pageTitle ?? 'Usuarios'}}</h3>               
               <div class="text-white">
                  {!! $headerAction ?? '' !!}
               </div>
            </div>            
            <div class="col-sm-12">
               <div class="card">
                  <div class="card-body px-0">
                     <div class="table-responsive columna-fija">
                        <table id="usuarios-list-table" class="table table-striped" role="grid" data-toggle="data-table">
                            <thead>
                                 <tr class="ligth">
                                    <th scope="col">No.</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Oficina</th>
                                    <th scope="col">Perfil</th>
                                    {{-- <th scope="col">Usuario</th> --}}
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellidos</th>
                                    <th scope="col">Número</th>
                                    <th scope="col">Email</th>
                                    @if(auth()->user()->perfil == 'superusuario')
                                        <th scope="col">Buzón</th>
                                    @endif
                                    {{-- <th scope="col">Status</th> --}}
                                    <th scope="col">Registrado</th>
                                    <th scope="col">Acciones</th>
                                 </tr>
                            </thead>
                            <tbody>
                              @foreach ($listar_usuarios as $usuario)
                                @if(auth()->user()->perfil == 'superusuario')
                                <tr>
                                    <th scope="row">
                                        <p>{{$usuario->id}}</p>
                                    </th>
                                    <td scope="row">
                                        <p>{{$usuario->abreviacion}}</p>
                                    </td>
                                    <td>
                                        <p>{{$usuario->ambito}}:{{$usuario->municipio}}</p>
                                    </td>
                                    <td>
                                        <p>{{$usuario->perfil}}</p>
                                    </td>
                                    {{-- <td>
                                        <p>{{$usuario->usuario}}</p>
                                    </td> --}}
                                    <td>
                                        <p>{{ucfirst($usuario->nombre)}}</p>
                                    </td>
                                    <td>
                                        <p>{{ucfirst($usuario->apellidos)}}</p>
                                    </td>
                                    <td>
                                        <p>{{$usuario->no_personal}}</p>
                                    </td>
                                    <td>
                                        <p>{{$usuario->email}}</p>
                                    </td>
                                    <td>
                                        <p>{{$usuario->buzon}}</p>
                                    </td>
                                   {{--  <td>
                                        <p>{{$usuario->status == 1 ? 'activo' : 'bloqueado'}}</p>
                                    </td> --}}
                                    <td>
                                        <p>{{date("d-m-Y", strtotime($usuario->created_at));}}</p>
                                    </td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            @can('editar_usuario')
                                            <button type="button" class="btn btn-primary-ccls btn-md btn-icon" >
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar" data-original-title="editar" href="{{route('users.edit', $usuario->id)}}">
                                                <span class="btn-inner">
                                                    <svg width="32" class="text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.3764 20.0279L18.1628 8.66544C18.6403 8.0527 18.8101 7.3443 18.6509 6.62299C18.513 5.96726 18.1097 5.34377 17.5049 4.87078L16.0299 3.69906C14.7459 2.67784 13.1541 2.78534 12.2415 3.95706L11.2546 5.23735C11.1273 5.39752 11.1591 5.63401 11.3183 5.76301C11.3183 5.76301 13.812 7.76246 13.8651 7.80546C14.0349 7.96671 14.1622 8.1817 14.1941 8.43969C14.2471 8.94493 13.8969 9.41792 13.377 9.48242C13.1329 9.51467 12.8994 9.43942 12.7297 9.29967L10.1086 7.21422C9.98126 7.11855 9.79025 7.13898 9.68413 7.26797L3.45514 15.3303C3.0519 15.8355 2.91395 16.4912 3.0519 17.1255L3.84777 20.5761C3.89021 20.7589 4.04939 20.8879 4.24039 20.8879L7.74222 20.8449C8.37891 20.8341 8.97316 20.5439 9.3764 20.0279ZM14.2797 18.9533H19.9898C20.5469 18.9533 21 19.4123 21 19.9766C21 20.5421 20.5469 21 19.9898 21H14.2797C13.7226 21 13.2695 20.5421 13.2695 19.9766C13.2695 19.4123 13.7226 18.9533 14.2797 18.9533Z" fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                            </button>
                                            <form class="d-inline" method="POST" role="form-{{$usuario->nombre}}"  action="{{route('password.email')}}">
                                                @csrf
                                                <input type="hidden" name="email"  value="{{ $usuario->email }}">
                                                <button data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Restablecer contraseña" type="submit"  name="form-{{$usuario->nombre}}" class="btn btn-primary-ccls btn-md btn-icon" >
                                                    <span class="btn-inner">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" class="text-white" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.383V5.383zM1 5.383v5.992l4.708-2.825L1 5.383zM7.64 8.76 1.64 12.615A1 1 0 0 0 2 13h12a1 1 0 0 0 .36-.385L8.36 8.76l-.72.427z"/>
                                                        </svg>                                                    
                                                    </span>
                                                </button>
                                                </form>
                                            @endcan
                                            
                                            @can('eliminar_usuario')
                                                <button type="button" class="btn {{auth()->user()->id == $usuario->id || $usuario->perfil == 'administrador' ? 'btn-secondary' : 'btn-danger'}} btn-md btn-icon" {{auth()->user()->id == $usuario->id || $usuario->perfil == 'administrador'? 'disabled' : ''}} onclick="eliminarUsuario( {{ $usuario->id }}, '{{ ucfirst($usuario->nombre) }}' )">
                                                <a data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Eliminar" data-original-title="Rechazada" data-bs-toggle="modal" data-bs-target="#rechazarCita" disabled>
                                                    <span class="btn-inner">
                                                        <svg width="32" class="text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    </span>
                                                </a>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>  
                                @endif
                                @if(auth()->user()->perfil == 'administrador' && ($usuario->perfil != 'superusuario' && $usuario->perfil != 'administrador'))
                                <tr>
                                    <th scope="row">
                                        <p>{{$usuario->id}}</p>
                                    </th>
                                    <td scope="row">
                                        <p>{{$usuario->abreviacion}}</p>
                                    </td>                                    
                                    <td>
                                        <p>{{$usuario->ambito}}:{{$usuario->municipio}}</p>
                                    </td>
                                    <td>
                                        <p>{{$usuario->perfil}}</p>
                                    </td>
                                    {{-- <td>
                                        <p>{{$usuario->usuario}}</p>
                                    </td> --}}
                                    <td>
                                        <p>{{ucfirst($usuario->nombre)}}</p>
                                    </td>
                                    <td>
                                        <p>{{ucfirst($usuario->apellidos)}}</p>
                                    </td>
                                    <td>
                                        <p>{{$usuario->no_personal}}</p>
                                    </td>
                                    <td>
                                        <p>{{$usuario->email}}</p>
                                    </td>
                                    <td>
                                        <p>{{date("d-m-Y", strtotime($usuario->created_at));}}</p>
                                    </td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            @can('editar_usuario')
                                            <button type="button" class="btn btn-primary-ccls btn-md btn-icon" >
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar" data-original-title="editar" href="{{route('users.edit', $usuario->id)}}">
                                                <span class="btn-inner">
                                                    <svg width="32" class="text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.3764 20.0279L18.1628 8.66544C18.6403 8.0527 18.8101 7.3443 18.6509 6.62299C18.513 5.96726 18.1097 5.34377 17.5049 4.87078L16.0299 3.69906C14.7459 2.67784 13.1541 2.78534 12.2415 3.95706L11.2546 5.23735C11.1273 5.39752 11.1591 5.63401 11.3183 5.76301C11.3183 5.76301 13.812 7.76246 13.8651 7.80546C14.0349 7.96671 14.1622 8.1817 14.1941 8.43969C14.2471 8.94493 13.8969 9.41792 13.377 9.48242C13.1329 9.51467 12.8994 9.43942 12.7297 9.29967L10.1086 7.21422C9.98126 7.11855 9.79025 7.13898 9.68413 7.26797L3.45514 15.3303C3.0519 15.8355 2.91395 16.4912 3.0519 17.1255L3.84777 20.5761C3.89021 20.7589 4.04939 20.8879 4.24039 20.8879L7.74222 20.8449C8.37891 20.8341 8.97316 20.5439 9.3764 20.0279ZM14.2797 18.9533H19.9898C20.5469 18.9533 21 19.4123 21 19.9766C21 20.5421 20.5469 21 19.9898 21H14.2797C13.7226 21 13.2695 20.5421 13.2695 19.9766C13.2695 19.4123 13.7226 18.9533 14.2797 18.9533Z" fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                            </button>

                                            <form class="d-inline" method="POST" role="form-{{$usuario->nombre}}"  action="{{route('password.email')}}">
                                            @csrf
                                            <input type="hidden" name="email"  value="{{ $usuario->email }}">
                                            <button data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Restablecer contraseña" type="submit"  name="form-{{$usuario->nombre}}" class="btn btn-primary-ccls btn-md btn-icon" >
                                                <span class="btn-inner">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" class="text-white" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.383V5.383zM1 5.383v5.992l4.708-2.825L1 5.383zM7.64 8.76 1.64 12.615A1 1 0 0 0 2 13h12a1 1 0 0 0 .36-.385L8.36 8.76l-.72.427z"/>
                                                    </svg>                                                    
                                                </span>
                                            </button>
                                            </form>
                                            @endcan
                                            
                                            @can('eliminar_usuario')
                                                <button type="button" class="btn btn-danger btn-md btn-icon" {{auth()->user()->id == $usuario->id || $usuario->perfil == 'administrador'? 'disabled' : ''}} onclick="eliminarUsuario( {{ $usuario->id }}, '{{ ucfirst($usuario->nombre) }}' )">
                                                <a data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Eliminar" data-original-title="Rechazada" data-bs-toggle="modal" data-bs-target="#rechazarCita" disabled>
                                                    <span class="btn-inner">
                                                        <svg width="32" class="text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    </span>
                                                </a>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
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
        /* function candado(candado){
            console.log(candado, 'usuario');
        } */

        let alertasColor = '#C04F15'; //COLOR QUE SE APLICA A BOTONES DE CONFIRMACIÓN E ICONOS.

        function eliminarUsuario(id, nombre){

            Swal.fire({
                title: '¿Está seguro?',
                text: `Se eliminará a ` + nombre,
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
                    
                    fetch(`/usuarios/${id}`,
                    {
                        method: "DELETE",
                        params: id,
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

                        // usuario eliminado
                        if(res.status === 200){

                            Swal.fire({
                                title: "Usuario eliminada con éxito",
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

                        // usuario no eliminado débido a citas
                        }else if(res.status === 201){

                            Swal.fire({
                                title: "Usuario no eliminado!",
                                text: nombre + ' aún tiene citas por atender.',
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

                        // solo se puede cambiar datos de usuario
                        }else if(res.status === 202){

                            Swal.fire({
                                title: "Este usuario no puede ser eliminado!",
                                text: 'Actualice los datos de ' + nombre + ', para asignar perfil a alguién más.' ,
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
                                text: 'No fue posible eliminar a ' + nombre + ', intente más tarde.',
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
</x-app-layout>
