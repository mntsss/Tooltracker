<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->

    <link rel='shortcut icon' href='{{asset('media/favicon.ico')}}' type='image/x-icon' />

    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
  <script>
  (function($) {
    $(function() {
      var activeurl = window.location + '';
      var active = activeurl.split('?');
      $('a[href="'+active[0]+'"]').addClass('active');
    });
  })(jQuery);
  </script>
</head>
<div class="container-fluid remove-all-padding h-100" id="app">
{{--
  <nav class="navbar navbar-expand-sm navbar-keytracker remove-top-padding w-100 align-self-top">
    <a class="navbar-brand " href="{{route('home')}}"><img src="{{asset('media/logo.svg')}}" alt="logo"/></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Išskleisti meniu">
      <i class="fas fa-bars text-warning" style="font-size: 30px"></i>
    </button>

    <div class="collapse navbar-collapse row" id="navbar">
      <ul class="navbar-nav mr-auto col-lg-6">
        <li class="nav-item">
          <a class="nav-link" href="#">Pradžia</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Darbuotojai</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Objektai</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Statistika</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Istorija</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto col-lg-6">
        <form class="form-inline my-2 my-md-0 nav-search">
        <div class="input-group ">
          <input class="form-control py-2" type="search" name="query" id="search" placeholder="Paieška...">
          <span class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">
              <i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </form>
      <li class="nav-item dropdown">
                      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                          {{ Auth::user()->Username }} <span class="caret"></span>
                      </a>

        <div class="dropdown-menu" aria-labelledby="dropdown-user">
            <a class="dropdown-item" href="{{route('user.settings.password')}}" role="button">Keisti slaptažodį</a>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Atsijungti</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
        </div>
      </li>
      </ul>

    </div>
  </nav>

          @include('include.messages')
            @yield('content')
 --}}

</div>


    <!-- Scripts -->
    <script src="{{asset('js/app.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
