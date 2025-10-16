<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Lupa Password</title>

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
                <div class="w-full max-w-sm">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Lupa Password?</h2>
                    <p class="mb-6 text-sm text-gray-600 text-center">
                        Tidak masalah. Masukkan email Anda dan kami akan mengirimkan link untuk mereset password Anda.
                    </p>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Kirim Link Reset Password') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <p class="mt-8 text-center text-sm text-gray-600">
                        Ingat password Anda?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Kembali ke Login
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </body>
</html>
