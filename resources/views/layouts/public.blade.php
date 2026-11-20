<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>@yield('title', 'Home') - {{ config('app.name', 'Carola') }}</title>

    <link href="{{ asset('carola/assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/fontawesome-all.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/iconfont.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/global.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/elements-css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/elements-css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/elements-css/booking-form.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('carola/assets/css/custom.css') }}" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('carola/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('carola/assets/images/favicon.png') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body class="boxed_wrapper">

    <x-public-header />

    @yield('content')

    <x-public-footer />

    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="icon-13"></i>
    </button>

    <script src="{{ asset('carola/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('carola/assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('carola/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('carola/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('carola/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('carola/assets/js/appear.js') }}"></script>
    <script src="{{ asset('carola/assets/js/wow.js') }}"></script>
    <script src="{{ asset('carola/assets/js/validation.js') }}"></script>
    <script src="{{ asset('carola/assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
