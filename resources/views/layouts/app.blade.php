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

    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100">

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
                class="w-64 bg-gray-800 text-white fixed inset-y-0 left-0 z-20 flex flex-col">

                <div class="flex items-center justify-between p-4 border-b border-gray-700">
                    <a href="{{ route('dashboard') }}"><h2 class="text-lg font-bold">Menu Inventaris</h2></a>
                    <button @click="sidebarOpen = false" class="p-2 rounded-md hover:bg-gray-700 focus:outline-none">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="flex-grow">
                    <nav class="p-4">
                        <ul>
                            <li>
                                <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-6 mr-2"></i> Dashboard
                                </a>
                            </li>

                            <li class="mt-4">
                                <span class="block py-2 px-4 text-xs uppercase text-gray-400 font-semibold">Manajemen</span>
                                <ul class="ml-2 space-y-2">
                                    @can('manage-content')
                                    <li>
                                        <a href="{{ route('products.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('products.*') ? 'bg-gray-700' : '' }}">
                                            <i class="fas fa-box w-6 mr-2"></i> Produk
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('categories.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('categories.*') ? 'bg-gray-700' : '' }}">
                                            <i class="fa-solid fa-layer-group w-6 mr-2"></i> Kategori
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('suppliers.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('suppliers.*') ? 'bg-gray-700' : '' }}">
                                            <i class="fa-solid fa-truck-field w-6 mr-2"></i>Supplier
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('purchase-orders.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('purchase-orders.*') ? 'bg-gray-700' : '' }}">
                                            <i class="fa-solid fa-cart-shopping w-6 mr-2"></i> Pesanan Pembelian
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('reviews.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('reviews.*') ? 'bg-gray-700' : '' }}">
                                            <i class="fa-solid fa-clipboard-check w-6 mr-2"></i>Review Laporan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('stock-out.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('stock-out.create') ? 'bg-gray-700' : '' }}">
                                            <i class="fa-solid fa-arrow-right-from-bracket w-6 mr-2"></i> Barang Keluar
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>

                            @can('create-reports')
                            <li class="mt-4">
                                <span class="block py-2 px-4 text-xs uppercase text-gray-400 font-semibold">Laporan</span>
                                <ul class="ml-2 space-y-2">
                                    <li>
                                        <a href="{{ route('reports.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('reports.index') ? 'bg-gray-700' : '' }}">
                                            <i class="fa-solid fa-file-circle-plus w-6 mr-2"></i> Buat Laporan Baru
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('reports.my') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('reports.my') ? 'bg-gray-700' : '' }}">
                                            <i class="fa-solid fa-list-check w-6 mr-2"></i> Riwayat Laporan Saya
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endcan
                        </ul>
                    </nav>
                </div>

                <div class="p-4 border-t border-gray-700">
                    <nav>
                        <ul>
                            @can('manage-users')
                            <li>
                                <a href="{{ route('users.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('users.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-users w-6 mr-2"></i> Manajemen User
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logs.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('users.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fa-solid fa-timeline w-6 mr-2"></i>Log Aktivitas
                                </a>
                            </li>
                            @endcan

                        </ul>
                    </nav>
                </div>
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
        @stack('scripts')
    </body>
</html>
