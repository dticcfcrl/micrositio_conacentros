<x-guest-layout>
   <section class="login-content">
      <div class="row m-0 align-items-center bg-white vh-md-100 vh-lg-100 ">
         <div class="col-md-6 col-12 bg-login-img d-block bg-primary p-0 mt-n1 vh-md-100 vh-lg-100 overflow-hidden">
            <img src="{{asset('images/51030260001_e3560e7ca4_o (1).jpg')}}" class="img-fluid gradient-main animated-scaleX" alt="images">
         </div>
         <div class="col-md-6 col-12">
            <div class="row justify-content-center">
               <div class="col-md-10">
                  <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                     <div class="card-body">
                        <a href="{{route('dashboard')}}" class="navbar-brand d-flex align-items-center mb-3">
                           <svg width="250" height="100" xmlns="http://www.w3.org/2000/svg">
                              <image href="{{asset('images/ccls/logo.png')}}"  height="100" width="250" />
                            </svg>                           
                        </a>
                  <h2 class="mb-2">Restablecer Contraseña</h2>
                  <p>Ingresa a tu correo electrónico para seguir las instrucciones para restablecer tu contraseña</p>
                  <x-auth-validation-errors class="mb-4" :errors="$errors" />
                  <form method="POST" action="{{route('password.email')}}">
                     @csrf
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="floating-label form-group">
                              <label for="email" class="form-label">Correo Eléctronico</label>
                              <input type="email" class="form-control" name="email" id="email" aria-describedby="email" placeholder=" ">
                           </div>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-primary btn-block">  {{ __('Enviar') }}</button>
                  </form>
               </div>
            </div>               
            <div class="sign-bg sign-bg-right">
               <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g opacity="0.05">
                  <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF"/>
                  <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF"/>
                  <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#3B8AFF"/>
                  <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF"/>
                  </g>
               </svg>
            </div>
         </div>
      </div>
   </section>
</x-guest-layout>
