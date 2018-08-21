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

</head>
<div class="container-fluid remove-all-padding h-100" id="app">

</div>


    <!-- Scripts -->
    <script src="{{asset('js/app.js')}}"></script>
  </body>
</html>
