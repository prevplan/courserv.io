<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Document</title>

    @livewireStyles
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>
