<!DOCTYPE html>
<html lang="ES">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/all.js') }}" defer></script>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" id="main-nav">
            <div class="container">
                @guest
                @else
                    <a class="navbar-brand" href="{{ route('user.list') }}">
                        <img class="logo-main" src="{{ asset('images/Logo_1.png') }}" />
                    </a>
                @endguest
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto" style="padding-top:0px;padding-bottom: 0px;margin: 0px">
                        @guest
                        @else
                            <li>
                                &nbsp;
                            </li>
                            <li>
                                @if (Auth::user()->profile_photo)
                                    <img class="photo-admin" src="{{asset('images/'.Auth::user()->name.'_'.Auth::user()->id.'/'.Auth::user()->profile_photo)}}" />
                                @endif
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4" id="py-4">
            @guest
            @else
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel" id="mainMenu">
                <div class="container">
                    <a class="navbar-brand" href="{{route('user.list')}}">
                        <i class="fas fa-user fa-1x"></i>
                        Usuarios
                    </a>
                    <a class="navbar-brand" href="{{route('publication.list')}}">
                        <i class="far fa-list-alt fa-1x"></i>
                        Publicaciones
                    </a>
                    <a class="navbar-brand" href="{{route('comment.list')}}">
                        <i class="fas fa-comment-dots fa-1x"></i>
                        Comentarios
                    </a>
                    <a class="navbar-brand" href="{{ route('notification.list') }}">
                        <i class="fas fa-bell fa-1x"></i>
                        Notificaciones
                    </a>
                </div>
            </nav>
            @endguest
            @yield('content')
                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>                
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
                

            @yield('scripts')
        </main>
    </div>
    @include('includes.alerts')
</body>
</html>
