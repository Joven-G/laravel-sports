<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Canchas') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  {{-- <script src="{{ asset('js/landing-page.js') }}" defer></script> --}}

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/myStyle.css') }}" rel="stylesheet">
  <link href="{{ asset('css/landing-page.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
  <div id="app">
    <nav id="navbar" class="navbar navbar-expand-md navbar-light fixed-top navbar-dark">
      <div class="container nav-color-movil">
        <a class="navbar-brand text-white font-weight-bolder" href="{{ url('/') }}">
          Logo
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a href="#"
                class="nav-link">
                Inicio
              </a>
            </li>
            <li class="nav-item">
              <a href="#nosotros"
                class="nav-link">
                Nosotros
              </a>
            </li>
            <li class="nav-item">
              <a href="#precios"
                class="nav-link">
                Precios
              </a>
            </li>
          </ul>

          {{-- Right Side Of Navbar --}}
          <ul class="navbar-nav ">
            <!-- Authentication Links -->
            @guest
              <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
              </li>
              @if (Route::has('register'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">
                    {{ trans('validation.attributes.register') }}
                  </a>
                </li>
              @endif
            @else
              <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <a href="{{ route('cancha-uno') }}" class="dropdown-item text-secondary">
                    {{ trans('validation.attributes.reserve') }}
                  </a>
                  <a class="dropdown-item text-secondary" href="{{ route('profile.edit') }}">
                      {{ trans('validation.attributes.profile') }}
                  </a>
                  <a class="dropdown-item text-secondary" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                    {{ trans('validation.attributes.logout') }}
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

    @include('partials.landing-page')

{{--     <main class="container">
      @if (session()->has('flash'))
        <div class="alert alert-success">
          {{ session()->get('flash') }}
        </div>
      @endif

      @yield('content')
    </main> --}}
  </div>
</body>
</html>
