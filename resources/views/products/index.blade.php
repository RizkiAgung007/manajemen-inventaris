<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ filterOpen: false }" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 dark:bg-green-900 dark:text-green-200 dark:border-green-600" role="alert">
                            <p class="font-bold">Sukses</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        {{-- tombol filter --}}
                        <button @click="filterOpen = !filterOpen" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-filter mr-2"></i>
                            <span x-show="!filterOpen">Tampilkan Filter</span>
                            <span x-show="filterOpen">Sembunyikan Filter</span>
                        </button>

                        @can('manage-content')
                            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Produk
                            </a>
                        @endcan
                    </div>

                    <div x-show="filterOpen" x-transition class="border-t border-b border-gray-200 dark:border-gray-700 py-6 mb-4">
                        <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Produk</label>
                                    <input type="text" id="search" name="search" placeholder="Cari nama produk..."
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           value="{{ request('search') }}">
                                </div>
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                                    <select name="category" id="category" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Urutkan</label>
                                    <select name="sort" id="sort" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Terlama</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                                        <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stok Terendah</option>
                                        <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Tabel Produk --}}
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    @if ($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded-md">
                                                    @else
                                                        <div class="h-12 w-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-md text-gray-400 dark:text-gray-500">
                                                            <i class="fa-solid fa-image fa-lg"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</div>

                                                    <div class="text-sm text-gray-500 dark:text-gray-400 flex flex-wrap gap-1 mt-1">
                                                        @forelse($product->categories as $category)
                                                            <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">{{ $category->name }}</span>
                                                        @empty
                                                            <span class="text-xs text-gray-400">-</span>
                                                        @endforelse
                                                    </div>

                                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                        Supplier:
                                                        @forelse($product->suppliers as $supplier)
                                                            {{ $supplier->name }}{{ !$loop->last ? ',' : '' }}
                                                        @empty
                                                            N/A
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            @if($product->stock == 0)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Habis</span>
                                            @elseif($product->stock < 10)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">{{ $product->stock }} unit</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">{{ $product->stock }} unit</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 font-medium align-middle">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium align-middle">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('products.show', $product->id) }}" class="p-2 text-gray-400 hover:text-blue-600 rounded-md hover:bg-blue-100 dark:hover:bg-gray-700" title="Detail">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                @can('manage-content')
                                                    <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-gray-400 hover:text-yellow-600 rounded-md hover:bg-yellow-100 dark:hover:bg-gray-700" title="Edit">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-md hover:bg-red-100 dark:hover:bg-gray-700" title="Hapus">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-16">
                                            <div class="text-center text-gray-400 dark:text-gray-500">
                                                <i class="fa-solid fa-magnifying-glass fa-3x mb-3"></i>
                                                <p class="text-lg">Produk Tidak Ditemukan</p>
                                                <p class="text-sm">Coba ubah kata kunci pencarian atau filter Anda.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $products->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterForm = document.getElementById('filter-form');
                const inputs = filterForm.querySelectorAll('input, select');
                let debounceTimer;

                inputs.forEach(function (input) {
                    input.addEventListener('input', function () {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            filterForm.submit();
                        }, 500);
                    });
                });
            });
        </script>
    @endpush

</x-app-layout>
