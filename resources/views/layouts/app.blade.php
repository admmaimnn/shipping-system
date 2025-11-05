<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Leading INTL</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/calendar.css', 'resources/js/app.js', 'resources/js/date.js', 'resources/js/calendar.js', 'resources/js/events.js'])
        @livewireStyles
    </head>
    <body class="font-poppins antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- SIDEBAR -->
            <div class="lg:flex min-h-screen">
                <div class="hidden md:block">
                    @if (Auth::check() && Auth::user()->role === 'admin')
                    @include('layouts.asidebar')
                @endif
                </div>
                
                <!-- MAIN CONTENT -->
                <div class="flex-1 sm:p-8">
                    <div class="max-w-7xl mx-auto">
                            {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        @stack('scripts')
        @livewireScripts
    </body>
</html>


