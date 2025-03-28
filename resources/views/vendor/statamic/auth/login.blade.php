@inject('str', 'Statamic\Support\Str')

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de sesión</title>
    @antlers
        {{ partial src="_styles" }}
    @endantlers

</head>

<body>

    <main class="main-content">
        <div class="wrapper">
            <section class="login-content">
                <div class="row m-0 align-items-center bg-white vh-md-100 vh-lg-100 ">
                    <div
                        class="col-md-6 col-12 bg-login-img d-block bg-primary p-0 mt-n1 vh-md-100 vh-lg-100 overflow-hidden">
                        <img src="{{ asset('assets/contenidos/imagen-fondo.jpeg') }}"
                            class="img-fluid bg-login-img gradient-main animated-scaleX" alt="images">
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="row justify-content-center">
                            
                            <div class="col-md-10">
                                <div
                                    class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                    <div class="card-body">
                                        <a href="#" class="navbar-brand d-flex align-items-center mb-3">
                                            <svg width="250" height="100" xmlns="http://www.w3.org/2000/svg">
                                                <image href="{{ asset('/assets/contenidos/logo.png') }}" height="100"
                                                    width="250" />
                                            </svg>
                                        </a>
                                        <h2 class="mb-2 text-center">Iniciar sesión</h2>
                                        <login inline-template :show-email-login="!{{ $str::bool($oauth) }}"
                                            :has-error="{{ $str::bool(count($errors) > 0) }}">
                                            @if (count($errors) > 0)
                                                <div class="alert alert-danger text-center" role="alert">
                                                    El correo o la contraseña es incorrecta
                                                </div>
                                            @endif
                                            <div>
                                                @if ($oauth)
                                                    <div class="login-oauth-providers">
                                                        @foreach ($providers as $provider)
                                                            <div class="provider mb-2">
                                                                <a href="{{ $provider->loginUrl() }}?redirect={{ parse_url(cp_route('index'))['path'] }}"
                                                                    class="w-full btn-primary">
                                                                    {{ __('Log in with :provider', ['provider' => $provider->label()]) }}
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    @if ($emailLoginEnabled)
                                                        <div class="text-center text-sm text-gray-700 py-6">&mdash;
                                                            {{ __('or') }} &mdash;</div>

                                                        <div class="login-with-email" v-if="! showEmailLogin">
                                                            <a class="btn w-full"
                                                                @click.prevent="showEmailLogin = true">
                                                                {{ __('Iniciar con correo') }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif

                                                <form method="POST" v-show="showEmailLogin"
                                                    class="email-login select-none"
                                                    @if ($oauth) v-cloak @endif>
                                                    {!! csrf_field() !!}

                                                    <input type="hidden" name="referer" value="{{ $referer }}" />

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="input-email" class="form-label">Correo
                                                                    electrónico</label>
                                                                <input id="input-email" type="email" name="email"
                                                                    value="{{ old('email') }}" class="form-control"
                                                                    placeholder="usuario@ejemplo.com" required autofocus>
                                                                <blade
                                                                    if|%20(%24hasError(%26%2339%3Bemail%26%2339%3B))%3Cdiv%20class%3D%26%2334%3Btext-red-500%20text-xs%20mt-2%26%2334%3B%3E%7B%7B%2524errors-%253Efirst(%2526%252339%253Bemail%2526%252339%253B)%7D%7D%3C%2Fdiv%3E%40endif>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="input-password"
                                                                    class="form-label">Contraseña</label>
                                                                <div class="input-group">
                                                                    <input id="input-password" class="form-control"
                                                                        type="password" placeholder="********"
                                                                        name="password" required>
                                                                    <div class="input-group-append">
                                                                        <button id="toggle-password"
                                                                            class="btn btn-outline-secondary"
                                                                            type="button">
                                                                            <i id="toggle-icon" class="fas fa-eye"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <blade if|
                                                                    (%24hasError(%26%2339%3Bpassword%26%2339%3B))%3Cdiv%20class%3D%26%2334%3Btext-red-500%20text-xs%20mt-2%26%2334%3B%3E%7B%7B%2524errors-%253Efirst(%2526%252339%253Bpassword%2526%252339%253B)%7D%7D%3C%2Fdiv%3E%40endif>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="flex justify-between items-center">
                                                                <label for="remember-me"
                                                                    class="flex items-center cursor-pointer">
                                                                    <input type="checkbox" name="remember"
                                                                        id="remember-me">
                                                                    <span class="rtl:mr-2 ltr:ml-2">Recordar
                                                                        sesión</span>
                                                                </label>
                                                                <br>
                                                                <div class="text-center">
                                                                    <button type="submit"
                                                                        class="btn-primary btn">Iniciar
                                                                        sesión</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </login>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
        </section>
        </div>
    </main>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('input-password');
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

        document.getElementById('input-password').addEventListener('focus', function () {
            this.placeholder = '';
        });

        document.getElementById('input-password').addEventListener('blur', function () {
            this.placeholder = '********';
        });

        document.getElementById('input-email').addEventListener('focus', function () {
            this.placeholder = '';
        });

        document.getElementById('input-email').addEventListener('blur', function () {
            this.placeholder = 'usuario@ejemplo.com';
        });
    </script>
</body>

</html>
