<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- @import di app.css lebih disarankan --}}
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: true }" class="min-h-screen bg-gray-100">

            <button
                x-show="!sidebarOpen"
                @click="sidebarOpen = true"
                class="fixed top-4 left-4 z-30 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <aside
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="w-64 bg-gray-800 text-white fixed inset-y-0 left-0 h-full z-20 flex flex-col">

                <div class="flex items-center justify-between p-4 border-b border-gray-700">
                    <h2 class="text-lg font-bold">Menu Inventaris</h2>
                    <button @click="sidebarOpen = false" class="p-2 rounded-md hover:bg-gray-700 focus:outline-none">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <nav class="flex-1 p-4">
                    <ul>
                        <li>
                            <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700">
                                <i class="fas fa-tachometer-alt w-6 mr-2"></i> Dashboard
                            </a>
                        </li>

                        <li class="mt-4">
                            <span class="block py-2 px-4 text-xs uppercase text-gray-400 font-semibold">Manajemen</span>
                            <ul class="ml-2">
                                {{-- Menu Produk untuk Admin & Superadmin --}}
                                @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                                    <li>
                                        <a href="{{ route('products.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700">
                                            <i class="fas fa-box w-6 mr-2"></i> Produk
                                        </a>
                                    </li>
                                @endif

                                {{-- Menu Pengguna HANYA untuk Superadmin --}}
                                @if(Auth::user()->role == 'superadmin')
                                    <li>
                                        <a href="{{ route('users.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700">
                                            <i class="fas fa-users w-6 mr-2"></i> Pengguna
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </nav>
            </aside>

            <div class="flex-1 transition-all duration-300" :class="{ 'ml-64': sidebarOpen, 'ml-0': !sidebarOpen }">
                @include('layouts.navigation')

                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
