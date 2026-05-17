<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') . 'testinng mobile' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100">

    <div class="max-w-[480px] mx-auto bg-white min-h-screen relative">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>