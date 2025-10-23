<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
                {{ __('Detail Pemasok') }}
            </h2>
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-300">{{ $supplier->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-300">{{ $supplier->contact_person }}</p>
                        </div>
                        @can('manage-content')
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                <i class="fas fa-pencil-alt mr-2"></i>Edit
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                    <i class="fas fa-trash-alt mr-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                        @endcan
                    </div>

                    <dl class="border-t border-gray-200 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-100">Email</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-300">{{ $supplier->email ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-100">Telepon</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-300">{{ $supplier->phone ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-gray-500 dark:text-gray-100">Alamat</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-300">{{ $supplier->address ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-300">
                    <h3 class="text-lg font-medium mb-4">Produk dari Pemasok Ini ({{ $supplier->products->count() }})</h3>
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-800 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Harga</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @forelse ($supplier->products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-300">{{ $product->name }}</div>
                                            <!-- <div class="text-sm text-gray-500">{{ $product->category->name ?? '-' }}</div> -->
                                        </td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-300">{{ $product->stock }} unit</td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-300">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('products.show', $product->id) }}" class="p-2 text-gray-400 hover:text-blue-600 rounded-md hover:bg-blue-100 dark:hover:bg-gray-700" title="Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-12 text-gray-500">
                                            Belum ada produk yang terhubung dengan pemasok ini.
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
