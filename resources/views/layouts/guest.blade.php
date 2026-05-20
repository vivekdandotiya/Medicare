<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Medicare - Access Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen flex items-center justify-center py-10 px-4 relative bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/medical_background.png') }}');">
        <!-- Background Overlay -->
        <div class="absolute inset-0 bg-gradient-to-tr from-teal-950/80 via-teal-900/70 to-emerald-950/80 backdrop-blur-[3px]"></div>

        <div class="w-full max-w-md flex flex-col items-center relative z-10">
            
            <!-- Logo Section -->
            <div class="mb-8 flex flex-col items-center">
                <a href="/" class="flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/10 px-6 py-3 rounded-2xl shadow-xl">
                    <span class="w-9 h-9 rounded-xl bg-teal-500 flex items-center justify-center text-white font-bold text-lg shadow-md">M</span>
                    <span class="text-xl font-bold tracking-tight text-white">Medi<span class="text-orange-400">care</span></span>
                </a>
            </div>

            <!-- Card Body with frosted glass -->
            <div class="w-full bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl shadow-2xl border border-white/20 dark:border-gray-800 rounded-3xl p-8 overflow-hidden">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>
