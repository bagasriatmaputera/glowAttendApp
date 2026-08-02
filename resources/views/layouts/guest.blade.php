<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Glowttend') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- WireUI -->
        <wireui:scripts />
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-gradient-to-br from-indigo-600 via-purple-600 to-purple-800 selection:bg-purple-500 selection:text-white relative overflow-hidden">
            <!-- Decorative blur circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-300/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 right-1/4 w-48 h-48 bg-purple-300/20 rounded-full blur-2xl"></div>
            <div class="absolute bottom-1/4 left-1/5 w-40 h-40 bg-pink-300/10 rounded-full blur-2xl"></div>

            <div class="relative flex flex-col items-center w-full max-w-md">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 shadow-lg ring-1 ring-white/20">
                    <x-application-logo class="w-16 h-16" />
                </div>

                <div class="w-full mt-6 px-6 py-8 bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <x-toast />
    </body>
</html>
