<!doctype html>
<html class="h-full bg-gray-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>{{ $attributes['metaTitle'] }} | {{ config('app.name') }}</title>

    @livewireStyles
    <script src="{{ asset('js/app.js') }}" defer></script>

    @if(config('umami.url') && config('umami.id'))
        <script async defer data-website-id="{{ config('umami.id') }}" src="{{ config('umami.url') }}"></script>
    @endif
</head>
<body class="h-full">

    {{ $slot }}

    @livewireScripts
    <script>
        window.livewire_app_url = "{{ \Illuminate\Support\Facades\Request::getSchemeAndHttpHost() }}/{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() }}"
    </script>

    <script>
        Livewire.on('resetCaptcha', () => {
            friendlyChallenge.autoWidget.reset();
        })

        Livewire.on('destroyCaptcha', () => {
            friendlyChallenge.autoWidget.destroy();
        })

    </script>
    <livewire:auth.logout />
</body>
</html>
