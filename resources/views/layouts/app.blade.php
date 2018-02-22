<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Merriweather" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Ca va dans config/app.php puis name et par défaut, prend la valeur 'Laravel' -->
    <title>{{ config('app.name', 'Hanif') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/iziModal/css/iziModal.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/iziToast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0-beta/katex.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('img/icon.png') }}" />

    @yield('more_css')

    <!-- Style site -->
    <link rel="stylesheet" href="{{ url('css/main.css') }}" type="text/css">

</head>
<body style="background: url('{{ asset('img/bg3.jpg') }}') no-repeat top center; background-size: cover" >

    <div id="app">
        @include('shared.menu')
        @yield('content')
    </div>

    <div id="bottom-pattern" class="container-fluid">

    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque id iure non reiciendis! Aspernatur cumque eligendi eum exercitationem fuga harum magni minima omnis? Commodi consequatur eaque ipsam quae vitae voluptatum.</div>
                <div class="col-12 col-md-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque id iure non reiciendis! Aspernatur cumque eligendi eum exercitationem fuga harum magni minima omnis? Commodi consequatur eaque ipsam quae vitae voluptatum.</div>
                <div class="col-12 col-md-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque id iure non reiciendis! Aspernatur cumque eligendi eum exercitationem fuga harum magni minima omnis? Commodi consequatur eaque ipsam quae vitae voluptatum.</div>
            </div>
        </div>
    </footer>

    <script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- iziModal -->
    <script src="{{ asset('plugins/iziModal/js/iziModal.min.js') }}"></script>
    <script src="{{ asset('plugins/iziToast/dist/js/iziToast.min.js') }}"></script>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0-beta/katex.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0-beta/contrib/auto-render.min.js"></script>

    <!-- Javascript personnel -->
    <script src="{{url('js/core.js')}}"></script>


    @yield('more_js')

    <script>
        renderMathInElement(document.body);
    </script>
</body>
</html>
