<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Validation BAT - Marketing Keymex' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full flex flex-col">
        <header class="bg-white shadow-sm">
            <div class="mx-auto max-w-3xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-center">
                    <span class="text-xl font-bold text-keymex-red">KEYMEX</span>
                    <span class="ml-2 text-sm text-gray-500">Marketing</span>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200">
            <div class="mx-auto max-w-3xl px-4 py-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-400">
                    KEYMEX - Service Marketing
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
