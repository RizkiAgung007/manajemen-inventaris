<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                    </div>
                    <div class="text-blue-500">
                        <i class="fa-solid fa-boxes-stacked fa-3x"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Kategori</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalCategories }}</p>
                    </div>
                    <div class="text-green-500">
                        <i class="fa-solid fa-tags fa-3x"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Produk Stok Kritis</p>
                        <p class="text-3xl font-bold text-red-600">{{ $lowStockProducts }}</p>
                    </div>
                    <div class="text-red-500">
                        <i class="fa-solid fa-triangle-exclamation fa-3x"></i>
                    </div>
                </div>

                @if(auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin')
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                    </div>
                    <div class="text-purple-500">
                        <i class="fa-solid fa-users fa-3x"></i>
                    </div>
                </div>
                @endif

                @if(auth()->user()->role == 'superadmin')
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between col-span-1 md:col-span-2">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Nilai Inventaris</p>
                        <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($inventoryValue, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-yellow-500">
                        <i class="fa-solid fa-sack-dollar fa-3x"></i>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
