<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Kategori') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-300">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Detail informasi mengenai kategori.</p>
                        </div>

                        @can('manage-content')
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="{{ route('categories.edit', $category->id) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                <i class="fas fa-pencil-alt mr-2"></i>Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                    <i class="fas fa-trash-alt mr-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                        @endcan
                    </div>

                    <dl class="border-t border-gray-200 pt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-300">Jumlah Produk</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-50 font-semibold">{{ $category->products->count() }} Produk</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-300">Total Stok Unit</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-50 font-semibold">{{ $category->products->sum('stock') }} Unit</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-300">Dibuat Pada</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-50">{{ $category->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-300">Terakhir Diperbarui</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-50">{{ $category->updated_at->format('d M Y, H:i') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-gray-500 dark:text-gray-300">Disediakan Oleh</dt>
                            <dd class="mt-1 flex flex-wrap gap-2">
                                @forelse($category->suppliers as $supplier)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                        {{ $supplier->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-900">N/A</span>
                                @endforelse
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

\            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-300">
                    <h3 class="text-lg font-medium mb-4">Produk dalam Kategori "{{ $category->name }}"</h3>
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-800 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-50">
                                @forelse ($category->products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-300">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-300">Pemasok: {{ $product->supplier->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm align-middle">{{ $product->stock }} unit</td>
                                        <td class="px-6 py-4 text-sm align-middle">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right align-middle">
                                            <a href="{{ route('products.show', $product->id) }}" class="p-2 text-gray-400 hover:text-blue-600 rounded-md hover:bg-blue-100 dark:hover:bg-gray-700" title="Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-16">
                                            <div class="text-center text-gray-400">
                                                <i class="fa-solid fa-box-open fa-3x mb-3"></i>
                                                <p class="text-lg">Tidak Ada Produk</p>
                                                <p class="text-sm">Belum ada produk yang terhubung dengan kategori ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
