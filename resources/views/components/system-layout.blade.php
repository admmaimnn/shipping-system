<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Leading INTL</title>

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-poppins text-[#0a0a0a] items-center lg:justify-center min-h-screen flex-col">

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

        <header>
            @include('layouts.navbar')
        </header>

        <main>
            {{ $slot }}
        </main>

    </body>
</html>
