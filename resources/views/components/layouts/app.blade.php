<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        @livewireStyles
    </head>
    <body>
    <div id="global-livewire-loader" style="display:none;">
        <div class="fixed top-0 left-0 w-full h-1 bg-blue-500 z-50 animate-pulse"></div>
    </div>
    <livewire:header />
    <div class="container mx-auto px-4 mt-2">
        {{ $slot }}
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            window.addEventListener('livewire:request-start', () => {
                document.getElementById('global-livewire-loader').style.display = 'block';
                console.log("tets")
            });
            window.addEventListener('livewire:request-finished', () => {
                document.getElementById('global-livewire-loader').style.display = 'none';
            });
        });
    </script>
    @livewireScripts
    </body>
</html>
