<x-app-layout>
    <x-slot name="header">
        {{-- Menambahkan dark:text-gray-200 --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Bagian Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

                {{-- Kartu Total Produk --}}
                <a href="{{ route('products.index') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalProducts }}</p>
                        </div>
                        <div class="text-blue-500">
                            <i class="fa-solid fa-boxes-stacked fa-3x opacity-70"></i>
                        </div>
                    </div>
                </a>

                {{-- Kartu Total Kategori --}}
                <a href="{{ route('categories.index') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kategori</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalCategories }}</p>
                        </div>
                        <div class="text-green-500">
                            <i class="fa-solid fa-tags fa-3x opacity-70"></i>
                        </div>
                    </div>
                </a>

                {{-- Kartu Stok Kritis --}}
                <a href="{{ route('products.index', ['sort' => 'stock_asc']) }}" class="block bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk Stok Kritis</p>
                            <p class="text-3xl font-bold text-red-600">{{ $lowStockProductsCount }}</p> {{-- Warna merah tetap menonjol --}}
                        </div>
                        <div class="text-red-500">
                            <i class="fa-solid fa-triangle-exclamation fa-3x opacity-70"></i>
                        </div>
                    </div>
                </a>

                {{-- Kartu Total Pengguna --}}
                @can('manage-users')
                <a href="{{ route('users.index') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
                        </div>
                        <div class="text-purple-500">
                            <i class="fa-solid fa-users fa-3x opacity-70"></i>
                        </div>
                    </div>
                </a>
                @endcan
            </div>

            {{-- Kartu Nilai Inventaris --}}
            @can('manage-users')
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Nilai Inventaris</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($inventoryValue, 0, ',', '.') }}</p>
                </div>
                <div class="text-yellow-500">
                    <i class="fa-solid fa-sack-dollar fa-3x opacity-70"></i>
                </div>
            </div>
            @endcan

            {{-- Layout 2 kolom untuk grafik dan tabel --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri: Grafik Pai --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Distribusi Produk per Kategori</h3>
                        <div class="h-96">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Tabel Produk Terbaru --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Produk Baru Ditambahkan</h3>
                        <ul class="space-y-4">
                            @forelse($recentProducts as $product)
                                <li class="flex items-center space-x-4 pb-4 border-b dark:border-gray-700 last:border-b-0">
                                    <div class="flex-shrink-0">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded-md">
                                        @else
                                            <div class="h-10 w-10 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-md text-gray-400 dark:text-gray-500"><i class="fa-solid fa-image"></i></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('products.show', $product) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate hover:text-blue-600 dark:hover:text-blue-400">{{ $product->name }}</a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $product->categories->pluck('name')->implode(', ') }}</p>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 self-start">{{ $product->created_at->diffForHumans() }}</div>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">Belum ada produk yang ditambahkan.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Grafik Batang --}}
                <div class="lg:col-span-3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">5 Produk dengan Stok Terendah</h3>
                        <div class="h-80">
                            <canvas id="lowestStockChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDarkMode = JSON.parse(localStorage.getItem('darkMode')) === true ||
                               (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

            const chartTextColor = isDarkMode ? '#9ca3af' : '#6b7280';
            const chartGridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            const pieBorderColor = isDarkMode ? '#1f2937' : '#fff';

            Chart.defaults.color = chartTextColor;
            Chart.defaults.borderColor = chartGridColor;

            // Grafik Pai Kategori
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'pie',
                data: {
                    labels: @json($categoryLabels),
                    datasets: [{
                        label: 'Jumlah Produk',
                        data: @json($categoryData),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(249, 115, 22, 0.7)'
                        ],
                        borderColor: pieBorderColor,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: chartTextColor
                            }
                        }
                    }
                }
            });

            // Grafik Batang Stok Terendah
            const stockCtx = document.getElementById('lowestStockChart').getContext('2d');
            new Chart(stockCtx, {
                type: 'bar',
                data: {
                    labels: @json($lowestStockLabels),
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: @json($lowestStockData),
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(220, 38, 38, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: chartTextColor
                            },
                            grid: {
                                color: chartGridColor
                            }
                        },
                        y: {
                            ticks: {
                                color: chartTextColor
                            },
                            grid: {
                                color: chartGridColor
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
