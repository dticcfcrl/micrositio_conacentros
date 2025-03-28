<div class="row col-md-12 col-lg-12 dorada">
    <!-- Citas del día -->
    <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up">
 
         <p class="card-text">Estado de las citas agendadas para hoy</p>
         <div class="list-inline m-0 p-0 mb-2">
            <div class="card" data-aos="fade-up">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="progress-detail">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 17V9M16 13.0011L8 13M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <label>Atendidas:</label>
                            <label class="text-black">{{ $citasAtendidas }}</label>

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
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 9V14M12 17H12.01M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
 
                             <label>Pendientes:</label>
                             <label class="text-black">{{ $citasPendientes }}</label>
 
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
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 10L15 16M15 10L9 16M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <label>Canceldas:</label>
                            <label class="text-black">{{ $citasCanceladas }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
 
    </div>

    <!-- Citas en el año -->
    <div class="col-md-6 d-slider1 overflow-hidden mb-3" data-aos="fade-up">
 
        <p class="card-text">Estado de las citas agendadas para la semana</p>
        <div class="list-inline m-0 p-0 mb-2">
           <div class="card" data-aos="fade-up">
               <div class="card-body">
                   <div class="progress-widget">
                       <div class="progress-detail">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 17V9M16 13.0011L8 13M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                           <label>Atendidas:</label>
                           <label class="text-black">{{ $citasAtendidasSemana }}</label>

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
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 9V14M12 17H12.01M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <label>Pendientes:</label>
                            <label class="text-black">{{ $citasPendientesSemana }}</label>

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
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 10L15 16M15 10L9 16M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                           <label>Canceldas:</label>
                           <label class="text-black">{{ $citasCanceladasSemana }}</label>
                       </div>
                   </div>
               </div>
           </div>
       </div>    

    </div>
    <!-- botón para agendar citas-->
    <div class="p-2 flex-grow-1 justify-content-star text-center" data-aos="fade-up">
        <a class="btn btn-primary btn-primary-ccls rounded-pill" href="{{route('atencioncitas')}}">Atención a citas</a>
    </div>
</div>