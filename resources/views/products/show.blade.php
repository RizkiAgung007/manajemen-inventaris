<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Produk') }}
            </h2>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">

                        <div>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                    <div class="text-center">
                                        <i class="fa-solid fa-image fa-4x"></i>
                                        <p class="mt-2 text-sm">Gambar tidak tersedia</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div>
                            <span class="bg-blue-100 text-blue-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">
                                {{ $product->category?->name ?? 'Tanpa Kategori' }}
                            </span>

                            <h1 class="text-4xl font-bold text-gray-900 mt-3">
                                {{ $product->name }}
                            </h1>

                            <p class="text-3xl font-light text-gray-800 my-4">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>

                            <div class="flex items-center space-x-2 text-sm mb-6">
                                @if($product->stock > 0)
                                    <span class="h-3 w-3 bg-green-500 rounded-full"></span>
                                    <span class="text-green-700 font-medium">Tersedia</span>
                                    <span class="text-gray-500"> ({{ $product->stock }} unit)</span>
                                @else
                                    <span class="h-3 w-3 bg-red-500 rounded-full"></span>
                                    <span class="text-red-700 font-medium">Stok Habis</span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-3 border-t border-b py-4">
                                <a href="{{ route('products.edit', $product->id) }}" class="w-full text-center inline-flex items-center justify-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                    <i class="fas fa-pencil-alt mr-2"></i>Edit Produk
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        <i class="fas fa-trash-alt mr-2"></i>Hapus Produk
                                    </button>
                                </form>
                            </div>

                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900">Deskripsi</h3>
                                <div class="mt-2 text-gray-600 prose max-w-none" style="white-space: pre-wrap;">
                                    {{ $product->desc ?? 'Tidak ada deskripsi.' }}
                                </div>
                            </div>

                            <div class="mt-6 text-xs text-gray-500">
                                <p>Ditambahkan pada: {{ $product->created_at->format('d M Y, H:i') }}</p>
                                <p>Terakhir diperbarui: {{ $product->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
