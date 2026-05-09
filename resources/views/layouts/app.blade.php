@php
    $isHomeAttendPage = request()->is('home') || request()->is('home/*');
    $isNotManagerPage = request()->is('leave-form-page') || request()->is('leave-form/*');
@endphp

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
    <body class="font-sans antialiased text-gray-800">

        <!-- Decorative gradient background -->
        <div class="fixed inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 -z-10"></div>
        <div class="fixed top-0 right-0 w-96 h-96 bg-gradient-to-bl from-blue-200/30 to-transparent rounded-full blur-3xl -z-10"></div>
        <div class="fixed bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-purple-200/30 to-transparent rounded-full blur-3xl -z-10"></div>

        <div class="min-h-screen selection:bg-indigo-500 selection:text-white relative">

            <!-- Navigation -->
            @if (!$isHomeAttendPage || $isNotManagerPage)
                <livewire:layout.navigation />
                
                <!-- Page Heading -->
                @if (isset($header))
                <header class=relative z-10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-4">
                            <!-- Accent bar with gradient -->
                            <span class="block w-2 h-8 rounded-full bg-gradient-to-b from-indigo-400 to-purple-600 shadow-lg"></span>
                            <div class="text-gray-800 font-bold text-xl">
                                {{ $header }}
                            </div>
                        </div>
                    </div>
                </header>
                @endif
            @endif

            <!-- Page Content -->
            <main class="relative z-0">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>