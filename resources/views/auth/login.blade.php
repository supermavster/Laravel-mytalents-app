<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/all.js') }}" defer></script>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles-login.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

</head>
<body>
<div class="container-fluid login">
    <div class="row justify-content-center">
        <img class="logo-login" src="{{ asset('images/Logo.png') }}" />
    </div>
    <div class="row justify-content-center" id="form-login">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header title-login">{{ __('Inicio de Sesión') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="row justify-content-center">
                            <div class="col-md-8">                                
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            </div>
                        </div>
                        <br>    
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-7">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Recordarme') }}
                                    </label>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-5">
                                  <a class="btn forgot_password" href="{{ route('password.request') }}">
                                    {{ __('Olvidaste la contraseña?') }}
                                    </a>
                            </div>
                        </div>                        
                        <div class="row justify-content-center">
                            <div class="col-md-8">                
                                <button type="submit" id="boton-login" class="form-control">
                                    {{ __('Login') }}
                                </button>                                
                            </div>
                        </div>                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

