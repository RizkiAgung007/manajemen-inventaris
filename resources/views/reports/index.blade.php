<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Stok Opname') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('reports.store') }}" method="POST">
                    @csrf
                    <div class="p-6 text-gray-900">
                        @if (session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        <h3 class="text-lg font-medium dark:text-gray-100">Formulir Stok Opname</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Isi kolom "Stok Fisik" sesuai dengan hasil perhitungan di lapangan. Kosongkan jika tidak dihitung.
                        </p>

                        <div class="mt-6 overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stok Sistem</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 150px;">Stok Fisik (Input)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($products as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">{{ $product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">{{ $product->stock }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">
                                                <input type="number" name="physical_stock[{{ $product->id }}]"
                                                       class="w-full dark:text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                       min="0">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-12 text-gray-500 dark:text-gray-300">
                                                <p>Tidak ada produk untuk ditampilkan.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t flex justify-end items-center space-x-4">
                         <!-- <a href="{{ route('reports.excel') }}" class="inline-flex items-center text-sm text-green-600 hover:text-green-800">
                            <i class="fa-solid fa-file-excel mr-2"></i> Download Data Excel (Kosong)
                        </a> -->
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Laporan Untuk Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
