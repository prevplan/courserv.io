<!doctype html>
<html class="h-full bg-gray-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @if(!$attributes['index'])
        <meta name="robots" content="noindex">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>{{ $attributes['metaTitle'] }} | {{ config('app.name') }}</title>

    <link rel="canonical" href="{{ canonical_url() }}" />

    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, canonical_url(), [], true) }}" />
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ preg_replace( '/\/' . LaravelLocalization::getDefaultLocale() . '/', '', LaravelLocalization::getLocalizedURL(LaravelLocalization::getDefaultLocale(), canonical_url()), 1) }}" />

    @livewireStyles
    <script src="{{ asset('js/app.js') }}" defer></script>

    @if(config('umami.url') && config('umami.id') && $attributes['umami'])
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
