<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0d6efd">
        <link rel="manifest" href="/manifest.json">

        <title>{{ config('app.name', 'Amiga Gracia Travel Service') }}</title>

        <link rel="icon" href="{{ asset('images/amiga-logo.jpg') }}" type="image/jpeg">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-slate-50 text-slate-900 min-h-screen">
        @yield('content')

        @livewireScripts
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function () {
                    navigator.serviceWorker.register('/sw.js').catch(function (error) {
                        console.warn('Service worker registration failed:', error);
                    });
                });
            }
        </script>
    </body>
</html>
