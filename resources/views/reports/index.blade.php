<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Stok Opname') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('reports.pdf') }}" method="POST">
                    @csrf
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium">Formulir Stok Opname</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Isi kolom "Stok Fisik" sesuai dengan hasil perhitungan di lapangan. Kosongkan jika tidak dihitung.
                        </p>

                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Sistem</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase" style="width: 150px;">Stok Fisik (Input)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" name="physical_stock[{{ $product->id }}]"
                                                       class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                       min="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 border-t flex justify-end items-center space-x-4">
                         <a href="{{ route('reports.excel') }}" class="inline-flex items-center text-sm text-green-600 hover:text-green-800">
                            <i class="fa-solid fa-file-excel mr-2"></i> Download Data Excel (Kosong)
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            <i class="fa-solid fa-file-pdf mr-2"></i> Buat & Download Laporan PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
