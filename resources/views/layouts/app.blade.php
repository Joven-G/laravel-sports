<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user" content="{{ Auth::user() }}">

  <title>{{ config('app.name', 'Canchas') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/myStyle.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
          Reservar
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          @if (request()->routeIs('cancha-*'))
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a href="{{ route('cancha-uno') }}"
                  class="nav-link {{ request()->routeIs('cancha-uno') ? 'active-bg text-white rounded' : '' }}">
                  Cancha Uno
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('cancha-dos') }}"
                  class="nav-link {{ request()->routeIs('cancha-dos') ? 'active-bg text-white rounded' : '' }}">
                  Cancha Dos
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('cancha-tres') }}"
                  class="nav-link {{ request()->routeIs('cancha-tres') ? 'active-bg text-white rounded' : '' }}">
                  Cancha Tres
                </a>
              </li>
            </ul>
          @endif

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
              <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
              </li>
              @if (Route::has('register'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
              @endif
            @else
              <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <a href="{{ route('cancha-uno') }}" class="dropdown-item">
                    {{ __('Reserve') }}
                  </a>
                  <a class="dropdown-item" href="{{ route('profile.edit') }}">
                      {{ __('Profile') }}
                  </a>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                </div>
              </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    <main class="container">
      @if (session()->has('flash'))
        <div class="alert alert-success">
          {{ session()->get('flash') }}
        </div>
      @endif

      @yield('content')
    </main>
  </div>
</body>
</html>
