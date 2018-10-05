<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Keytracker - Raktų valdymo sistema') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
    <div id="app" class="login-screen">
      <div class="container-fluid">
  <div class="row mx-0">
      <div class="col-md-8 col-md-offset-2 vcenter">
          <div class="panel panel-default">
              <div class="panel-heading">Prisijungimas</div>

              <div class="panel-body transparent-box">
                  <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                      {{ csrf_field() }}

                      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                          <label for="email" class="col-md-4 control-label">Vartotojo el. paštas</label>

                          <div class="col-md-6">
                              <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                              @if ($errors->has('email'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('email') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                          <label for="password" class="col-md-4 control-label">Slaptažodis</label>

                          <div class="col-md-6">
                              <input id="password" type="password" class="form-control" name="password" required>

                              @if ($errors->has('password'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('password') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-4">
                              <div class="checkbox">
                                  <label>
                                      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Likti prisijungus
                                  </label>
                              </div>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-8 col-md-offset-4">
                              <button type="submit" class="btn btn-primary">
                                  Prisijungti
                              </button>

                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
<footer class="admin-footer">
<div class="row mx-0">

<div class="col-md-offset-5 col-md-3">
    <span>Visos teisės saugomos <span class="fa fa-copyright" style = "font-size: 15px !important"></span> <a href ="http://keytracker.lt" style="color:white">KeyTracker</a> 2017</span>
</div>
<div class="col-md-2">
    <a href="mailto:info@KeyTracker.lt"><span class="fa fa-envelope-o" style = "font-size: 15px !important"></span>&#32;&#32;&#32;<span style="color:white">info@KeyTracker.lt</span></a>
</div>
<div class="col-md-2">
  <a href="tel:+37065443333"><span class="fa fa-phone" style = "font-size: 15px !important"></span>&#32;&#32;&#32;<span style="color:white">+37065443333</span></a>
</div>
</div>
</footer>

    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js"></script>
    <script src="{{ asset('js/sidebar.js')}}"></script>
    <script src="{{ asset('js/bootstrap.js')}}"></script>
    <script>
      $(".nav li a").on("click", function(){
   $(".li").find(".active").removeClass("active");
   $(this).parent().addClass("active");
});
    </script>
</body>
</html>
