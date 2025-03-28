<div class="row col-md-12 col-lg-12 dorada">
    <!-- Oficinas registradas -->
    <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up">
 
         <p class="card-text">Oficinas</p>
         <div class="list-inline m-0 p-0 mb-2">
            <div class="card" data-aos="fade-up">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="progress-detail">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5 4C4.44772 4 4 4.44772 4 5V19C4 19.5523 4.44772 20 5 20H12H13C13.5523 20 14 19.5523 14 19V5C14 4.44772 13.5523 4 13 4H5ZM5 22H12H13H19C20.6569 22 22 20.6569 22 19V9C22 7.34315 20.6569 6 19 6H16V5C16 3.34315 14.6569 2 13 2H5C3.34315 2 2 3.34315 2 5V19C2 20.6569 3.34315 22 5 22ZM19 20H15.8293C15.9398 19.6872 16 19.3506 16 19V8H19C19.5523 8 20 8.44772 20 9V19C20 19.5523 19.5523 20 19 20ZM7 14H5V16H7V14ZM8 14H10V16H8V14ZM13 14H11V16H13V14ZM17 14H19V16H17V14ZM19 10H17V12H19V10ZM5 10H7V12H5V10ZM10 10H8V12H10V10ZM11 10H13V12H11V10ZM7 6H5V8H7V6ZM8 6H10V8H8V6ZM13 6H11V8H13V6Z" fill="currentColor"/>
                            </svg>

                            <label>Registradas:</label>
                            <label class="text-black">{{ $totalOficinas }}</label>

                        </div>
                    </div>
                </div>
            </div>
        </div>
          
         <div class="list-inline m-0 p-0 mb-2">
             <div class="card" data-aos="fade-up">
                 <div class="card-body">
                     <div class="progress-widget">
                         <div class="progress-detail">
                            <svg fill="currentColor" width="24px" height="24px" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 0h192v192H0z" style="fill:none"/><path d="M145.93 176h-28.88c-4.25 0-8.22-2.28-10.36-5.95l-15.2-26.01a12.016 12.016 0 0 1-.01-12.09l15.11-25.99a12.05 12.05 0 0 1 10.38-5.97h28.47c4.16 0 8.09 2.21 10.26 5.77l15.61 25.69c2.27 3.74 2.33 8.41.15 12.21l-15.11 26.31a12.022 12.022 0 0 1-10.41 6.03Zm-10.88-32.04L123.9 164h22.02l11.65-20.29-22.52.25Zm-21.61-25.91-11.59 19.94 11.68 19.98 11.06-19.89-11.15-20.04Zm10.37-6.05 11.1 19.96 22.5-.25-11.98-19.72h-21.62Zm-48.88 22.1H46.05c-4.25 0-8.22-2.28-10.36-5.95L20.5 102.16c-2.19-3.74-2.18-8.39.01-12.13l15.28-26.01c2.15-3.65 6.11-5.92 10.35-5.92h28.38c4.17 0 8.11 2.22 10.27 5.79l15.52 25.68c2.26 3.73 2.31 8.4.14 12.18l-15.11 26.32a12.022 12.022 0 0 1-10.41 6.03Zm-10.88-32.04L52.9 122.1h22.02l11.64-20.28-22.52.25Zm-33.2-5.95 11.67 19.97L53.63 96.1 42.55 76.19l-11.7 19.92M52.9 70.1 64 90.06l22.43-.25-11.92-19.72H52.9ZM146.02 92h-28.88c-4.25 0-8.22-2.28-10.36-5.94l-15.2-26.01a11.998 11.998 0 0 1-.01-12.08l15.11-25.99a12.05 12.05 0 0 1 10.38-5.97h28.47c4.17 0 8.09 2.21 10.26 5.77l15.61 25.69c2.27 3.74 2.33 8.42.15 12.21l-15.11 26.31a12.022 12.022 0 0 1-10.41 6.03Zm-10.98-32.04L123.89 80h22.12l11.64-20.29-22.62.25Zm-21.56-25.82-11.54 19.85 11.63 19.9 11.01-19.8-11.1-19.95ZM123.8 28l11.1 19.96 22.6-.25-11.98-19.72H123.8Z"/>
                            </svg>
 
                             <label>Listas para recibir citas:</label>
                             <label class="text-black">{{ $listasCitas }}</label>
 
                         </div>
                     </div>
                 </div>
             </div>
         </div> 

         <div class="list-inline m-0 p-0 mb-2">
            <div class="card" data-aos="fade-up">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="progress-detail">
                            <svg fill="currentColor" height="24px" width="24px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                            viewBox="0 0 297 297" xml:space="preserve">
                            <path d="M250.929,276.619h-17.463v-31.301c0-31.668-17.379-60.777-48.936-81.967c-3.924-2.635-6.363-8.324-6.363-14.852
                            s2.439-12.215,6.365-14.852c31.555-21.188,48.934-50.297,48.936-81.965V20.381h17.461c5.627,0,10.189-4.562,10.189-10.191
                            C261.118,4.561,256.556,0,250.929,0H46.071c-5.627,0-10.19,4.561-10.19,10.189c0,5.629,4.563,10.191,10.19,10.191h17.463v31.303
                            c0,31.668,17.377,60.777,48.936,81.965c3.924,2.637,6.363,8.324,6.363,14.852c0,6.529-2.439,12.217-6.365,14.854
                            c-31.557,21.188-48.934,50.297-48.934,81.965v31.301H46.071c-5.627,0-10.19,4.563-10.19,10.191c0,5.629,4.563,10.19,10.19,10.19
                            h204.857c5.627,0,10.189-4.56,10.189-10.19C261.118,281.182,256.556,276.619,250.929,276.619z M83.915,245.318
                            c0-33.275,25.006-55.035,39.914-65.043c9.633-6.467,15.385-18.346,15.385-31.775c0-13.428-5.752-25.307-15.385-31.773
                            c-14.908-10.008-39.914-31.768-39.914-65.043V20.381h129.17l-0.002,31.303c0,33.275-25.006,55.035-39.912,65.041
                            c-9.635,6.469-15.387,18.348-15.387,31.775c0,13.43,5.752,25.307,15.385,31.775c14.908,10.008,39.914,31.768,39.916,65.043v31.301
                            H83.915V245.318z"/>
                       </svg>

                            <label>Pendientes para recibir citas:</label>
                            <label class="text-black">{{ $totalOficinas - $listasCitas }}</label>

                        </div>
                    </div>
                </div>
            </div>
        </div> 

         <!-- botón para Oficinas-->
    <div class="p-2 flex-grow-1 justify-content-star text-center" data-aos="fade-up">
        <a class="btn btn-primary btn-primary-ccls rounded-pill" href="{{route('listaroficinas')}}">Oficinas</a>
    </div>
 
    </div>

    <!-- Usuarios -->
    <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up">
 
        <p class="card-text">Usuarios</p>
        <div class="list-inline m-0 p-0 mb-2">
           <div class="card" data-aos="fade-up">
               <div class="card-body">
                   <div class="progress-widget">
                       <div class="progress-detail">
                        <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.294 7.29105C17.294 10.2281 14.9391 12.5831 12 12.5831C9.0619 12.5831 6.70601 10.2281 6.70601 7.29105C6.70601 4.35402 9.0619 2 12 2C14.9391 2 17.294 4.35402 17.294 7.29105ZM12 22C7.66237 22 4 21.295 4 18.575C4 15.8539 7.68538 15.1739 12 15.1739C16.3386 15.1739 20 15.8789 20 18.599C20 21.32 16.3146 22 12 22Z" fill="currentColor"></path>
                        </svg>

                           <label>Administradores:</label>
                           <label class="text-black">{{ $usuariosAdmin }}</label>

                       </div>
                   </div>
               </div>
           </div>
       </div>
         
        <div class="list-inline m-0 p-0 mb-2">
            <div class="card" data-aos="fade-up">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="progress-detail">
                            <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.294 7.29105C17.294 10.2281 14.9391 12.5831 12 12.5831C9.0619 12.5831 6.70601 10.2281 6.70601 7.29105C6.70601 4.35402 9.0619 2 12 2C14.9391 2 17.294 4.35402 17.294 7.29105ZM12 22C7.66237 22 4 21.295 4 18.575C4 15.8539 7.68538 15.1739 12 15.1739C16.3386 15.1739 20 15.8789 20 18.599C20 21.32 16.3146 22 12 22Z" fill="currentColor"></path>
                            </svg>                            

                            <label>Conciliadores:</label>
                            <label class="text-black">{{ $usuariosCs }}</label>

                        </div>
                    </div>
                </div>
            </div>
        </div>        
        
        <div class="list-inline m-0 p-0 mb-2">
           <div class="card" data-aos="fade-up">
               <div class="card-body">
                   <div class="progress-widget">
                       <div class="progress-detail">
                            <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.294 7.29105C17.294 10.2281 14.9391 12.5831 12 12.5831C9.0619 12.5831 6.70601 10.2281 6.70601 7.29105C6.70601 4.35402 9.0619 2 12 2C14.9391 2 17.294 4.35402 17.294 7.29105ZM12 22C7.66237 22 4 21.295 4 18.575C4 15.8539 7.68538 15.1739 12 15.1739C16.3386 15.1739 20 15.8789 20 18.599C20 21.32 16.3146 22 12 22Z" fill="currentColor"></path>
                            </svg>  

                           <label>Auxiliares:</label>
                           <label class="text-black">{{ $usuariosAux }}</label>
                       </div>
                   </div>
               </div>
           </div>
       </div>    

       <!-- botón para Oficinas-->
    <div class="p-2 flex-grow-1 justify-content-star text-center" data-aos="fade-up">
        <a class="btn btn-primary btn-primary-ccls rounded-pill" href="{{route('users.index')}}">Usuarios</a>
    </div>

    </div>    
</div>