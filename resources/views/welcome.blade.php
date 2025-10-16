<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
        <div class="relative min-h-screen md:flex">

            <div class="md:w-1/2 bg-gray-800 text-white flex flex-col justify-center items-center p-8">
                <div class="text-center">
                    <i class="fa-solid fa-boxes-stacked fa-5x text-blue-400 mb-6"></i>
                    <h1 class="text-4xl font-bold mb-2">Sistem Manajemen Inventaris</h1>
                    <p class="text-gray-300">Solusi terintegrasi untuk mengelola stok Anda secara efisien.</p>
                </div>
            </div>

            <div class="md:w-1/2 bg-gray-100 flex flex-col justify-center items-center p-8">
                <div class="w-full max-w-sm text-center">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Selamat Datang</h2>
                    @if (Route::has('login'))
                        <div class="space-y-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="block w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700">
                                    Masuk ke Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="block w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700">
                                    Log In
                                </a>
                                <!-- @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block w-full px-4 py-3 bg-white text-gray-800 font-semibold rounded-lg shadow-sm border hover:bg-gray-50">
                                        Register
                                    </a>
                                @endif -->
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </body>
</html>
