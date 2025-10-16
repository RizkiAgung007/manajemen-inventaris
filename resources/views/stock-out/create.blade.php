<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catat Barang Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('stock-out.store') }}">
                    @csrf
                    <div class="p-6 max-w-xl mx-auto">
                        @if (session('error'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif {{-- <-- [PERBAIKAN] Menambahkan @endif yang hilang --}}

                        <div class="space-y-6">
                            <div>
                                <label for="product_id" class="block font-medium text-sm text-gray-700">Produk</label>
                                <select id="product_id" name="product_id" class="block mt-1 w-full" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Stok: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="quantity" class="block font-medium text-sm text-gray-700">Jumlah Keluar</label>
                                <input id="quantity" type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @error('quantity') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="type" class="block font-medium text-sm text-gray-700">Tipe Transaksi</label>
                                <select id="type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="penjualan" {{ old('type') == 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                                    <option value="barang_rusak" {{ old('type') == 'barang_rusak' ? 'selected' : '' }}>Barang Rusak</option>
                                    <option value="pemakaian_internal" {{ old('type') == 'pemakaian_internal' ? 'selected' : '' }}>Pemakaian Internal</option>
                                </select>
                                @error('type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="notes" class="block font-medium text-sm text-gray-700">Catatan (Opsional)</label>
                                <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 border-t flex justify-end items-center space-x-4">
                        <a href="{{ route('stock-out.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fa-solid fa-check mr-2"></i> Catat & Kurangi Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#product_id',{ create: false });
        });
    </script>
    @endpush
</x-app-layout>
