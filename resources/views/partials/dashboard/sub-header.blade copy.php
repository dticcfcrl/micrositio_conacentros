

<div class="iq-navbar-header" style="height: auto;">
    <div class="container-fluid iq-container dorada">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="d-flex justify-content-between">               
                        <h3 class="mb-0 caption-title text-muted"><span class="dorada">Bienvenido</span> {{ auth()->user()->full_name ?? 'Desconocido'  }}<br><small class="fs-5 text-muted">{{ucfirst(trans(auth()->user()->perfil))}}</small></h3>                        
                        <div class="text-white">
                            <button type="button" class="btn btn-sm btn-danger btn-danger-ccls" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Cancelar citas de hoy" onclick="cancelarCitas()">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 10L15 16M15 10L9 16M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>                                
                            </button>
                            <a href="{{route('users.edit', auth()->user()->id)}}" class="btn btn-sm btn-primary btn-primary-ccls" role="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar">
                                <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.3764 20.0279L18.1628 8.66544C18.6403 8.0527 18.8101 7.3443 18.6509 6.62299C18.513 5.96726 18.1097 5.34377 17.5049 4.87078L16.0299 3.69906C14.7459 2.67784 13.1541 2.78534 12.2415 3.95706L11.2546 5.23735C11.1273 5.39752 11.1591 5.63401 11.3183 5.76301C11.3183 5.76301 13.812 7.76246 13.8651 7.80546C14.0349 7.96671 14.1622 8.1817 14.1941 8.43969C14.2471 8.94493 13.8969 9.41792 13.377 9.48242C13.1329 9.51467 12.8994 9.43942 12.7297 9.29967L10.1086 7.21422C9.98126 7.11855 9.79025 7.13898 9.68413 7.26797L3.45514 15.3303C3.0519 15.8355 2.91395 16.4912 3.0519 17.1255L3.84777 20.5761C3.89021 20.7589 4.04939 20.8879 4.24039 20.8879L7.74222 20.8449C8.37891 20.8341 8.97316 20.5439 9.3764 20.0279ZM14.2797 18.9533H19.9898C20.5469 18.9533 21 19.4123 21 19.9766C21 20.5421 20.5469 21 19.9898 21H14.2797C13.7226 21 13.2695 20.5421 13.2695 19.9766C13.2695 19.4123 13.7226 18.9533 14.2797 18.9533Z" fill="currentColor"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                 </div>
                @if(auth()->user()->perfil != 'superusuario')
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="row w-100">
                                <div class="col-md-6">
                                    <div class="card-body section-title my-2">
                                        <p class="card-text">Entidad asignada</p>
                                        @foreach ($estados as $estado)
                                            @if($estado->clave == auth()->user()->id_estado)
                                                <p class="card-text" id="_estado"><small class="text-muted">{{$estado->nombre}}</small></p>
                                            @endif
                                        @endforeach                                
                                        @foreach ($cclsUbicaciones as $ccl)
                                            @if($ccl->id == auth()->user()->id_ccls)
                                                <p class="card-text">Delegación asingada</p>
                                                <p class="card-text" id="_municipio"><small class="text-muted">{{$ccl->municipio}}</small></p>
                                                <p class="card-text">Ámbito</p>
                                                <p class="card-text" id="_ambito"><small class="text-muted">{{$ccl->ambito}}</small></p>
                                                <p class="card-text">Dirección</p>
                                                <p class="card-text" id="_oficina"><small class="text-muted">{{$ccl->direccion}}</small></p>
                                            @endif                                
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-start pt-2 pb-2">
                                    <div id="map-container" style="height: 400px; width: 400px"></div>
                                </div>
                            </div>
                        </div>            
                    </div>
                @endif               
            </div>
        </div>
    </div> 
</div>