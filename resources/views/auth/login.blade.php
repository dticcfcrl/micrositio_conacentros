<x-guest-layout>
   <section class="login-content">
      <div class="row m-0 align-items-center bg-white vh-md-100 vh-lg-100 ">
         <div class="col-md-6 col-12 bg-login-img d-block bg-primary p-0 mt-n1 vh-md-100 vh-lg-100 overflow-hidden">
            <img src="{{asset('images/ccls/imagen-fondo.jpeg')}}" class="img-fluid gradient-main animated-scaleX" alt="images">
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
                        <h2 class="mb-2 text-center">Iniciar sesión</h2>
                        
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />
                        @if ($mensaje ?? false)
                           <div class="alert alert-danger">
                              {{ $mensaje }}
                           </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}" data-toggle="validator">
                            {{csrf_field()}}
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input id="email" type="email" name="email"  value="{{ old('email') }}"   class="form-control"  placeholder="usuario@ejemplo.com" required autofocus>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                       <input id="password" class="form-control" type="password" placeholder="********"  name="password" required autocomplete="current-password">
                                       <div class="input-group-append">
                                          <button id="toggle-password"
                                             class="btn btn-outline-secondary"
                                             type="button">
                                             <i id="toggle-icon" class="fas fa-eye"></i>
                                          </button>
                                    </div>
                                   </div>
                                 </div>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="customCheck1">
                                    <!-- <input type="checkbox" class="custom-control-input" id="customCheck1"> -->
                                    <label class="form-check-label" for="customCheck1">Recordar sesión</label>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                <!-- <a href="{{route('auth.recoverpw')}}"  class="float-end">Recuperar contraseña</a>-->
                              </div>
                           </div>
                           <div class="d-flex justify-content-center">
                              <button type="submit" class="btn btn-primary">Ingresar</button>
                           </div>                                                     
                        </form>
                     </div>
                  </div>
               </div>
            </div>            
         </div>
         
      </div>
   </section>
   <script>
      document.getElementById('toggle-password').addEventListener('click', function() {
          const passwordInput = document.getElementById('password');
          const toggleIcon = document.getElementById('toggle-icon');
          if (passwordInput.type === 'password') {
              passwordInput.type = 'text';
              toggleIcon.classList.remove('fa-eye');
              toggleIcon.classList.add('fa-eye-slash');
          } else {
              passwordInput.type = 'password';
              toggleIcon.classList.remove('fa-eye-slash');
              toggleIcon.classList.add('fa-eye');
          }
      });

      document.getElementById('password').addEventListener('focus', function () {
          this.placeholder = '';
      });

      document.getElementById('password').addEventListener('blur', function () {
          this.placeholder = '********';
      });

      document.getElementById('email').addEventListener('focus', function () {
          this.placeholder = '';
      });

      document.getElementById('email').addEventListener('blur', function () {
          this.placeholder = 'usuario@ejemplo.com';
      });
  </script>
</x-guest-layout>
