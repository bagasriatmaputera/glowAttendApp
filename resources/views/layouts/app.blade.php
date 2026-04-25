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

        {{-- Decorative purple gradient blobs (background accents) --}}
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full bg-gradient-to-br from-purple-200 via-violet-100 to-transparent opacity-60 blur-3xl"></div>
            <div class="absolute top-1/2 -left-48 w-[400px] h-[400px] rounded-full bg-gradient-to-tr from-purple-100 via-fuchsia-100 to-transparent opacity-50 blur-3xl"></div>
            <div class="absolute -bottom-32 right-1/3 w-[450px] h-[450px] rounded-full bg-gradient-to-tl from-violet-200 via-purple-50 to-transparent opacity-50 blur-3xl"></div>
        </div>

        <div class="min-h-screen selection:bg-purple-500 selection:text-white">

            {{-- Navigation --}}
            <livewire:layout.navigation />

            {{-- Page Heading --}}
            @if (isset($header))
                <header class="bg-white/80 backdrop-blur-sm border-b border-purple-100 shadow-sm shadow-purple-100/50">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            {{-- Purple accent bar --}}
                            <span class="block w-1 h-7 rounded-full bg-gradient-to-b from-purple-400 to-violet-600"></span>
                            <div class="text-gray-800 font-semibold text-lg">
                                {{ $header }}
                            </div>
                        </div>
                    </div>
                </header>
            @endif

            {{-- Page Content --}}
            <main class="py-6">
                {{ $slot }}
            </main>
        </div>

    </body>
</html>