<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Produk') }}
            </h2>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ tab: 'detail' }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <button @click="tab = 'detail'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'detail', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'detail' }"
                                class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                            Detail Produk
                        </button>
                        <button @click="tab = 'history'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'history', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'history' }"
                                class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                            Riwayat Stok
                        </button>
                    </nav>
                </div>

                {{-- Panel untuk Tab Detail Produk --}}
                <div x-show="tab === 'detail'">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 p-6 md:p-8">
                        <div>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                    <div class="text-center"><i class="fa-solid fa-image fa-4x"></i><p class="mt-2 text-sm">Gambar tidak tersedia</p></div>
                                </div>
                            @endif

                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900">Disediakan Oleh</h3>
                                {{ $product->suppliers->pluck('name')->implode(', ') ?: 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex flex-wrap gap-2">
                                @forelse($product->categories as $category)
                                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Tanpa Kategori</span>
                                @endforelse
                            </div>

                            <h1 class="text-4xl font-bold text-gray-900 mt-3">{{ $product->name }}</h1>
                            <p class="text-3xl font-light text-gray-800 my-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                            <div class="flex items-center space-x-2 text-sm mb-6">
                                @if($product->stock == 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Stok Habis</span>
                                @elseif($product->stock < 10)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Stok Kritis ({{ $product->stock }})</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia ({{ $product->stock }})</span>
                                @endif
                            </div>

                            @can('manage-content')
                            <div class="flex items-center space-x-3 border-t border-b py-4">
                                <a href="{{ route('products.edit', $product->id) }}" class="w-full text-center inline-flex items-center justify-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                    <i class="fas fa-pencil-alt mr-2"></i>Edit Produk
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')" class="w-full">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        <i class="fas fa-trash-alt mr-2"></i>Hapus Produk
                                    </button>
                                </form>
                            </div>
                            @endcan
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900">Deskripsi</h3>
                                <div class="mt-2 text-gray-600 prose max-w-none">{{ $product->desc ?? 'Tidak ada deskripsi.' }}</div>
                            </div>

                            <div class="mt-6 text-xs text-gray-500">
                                <p>Ditambahkan pada: {{ $product->created_at->format('d M Y, H:i') }}</p>
                                <p>Terakhir diperbarui: {{ $product->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel untuk Tab Riwayat Stok --}}
                <div x-show="tab === 'history'" class="p-6">
                    <h3 class="text-lg font-medium mb-4">Log Pergerakan Stok</h3>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Perubahan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Oleh</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($stockMovements as $movement)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $movement->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <x-status-badge :status="$movement->type" />
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-bold whitespace-nowrap {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">{{ $movement->user->name ?? 'Sistem' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $movement->notes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12 text-gray-500">Belum ada riwayat pergerakan stok untuk produk ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $stockMovements->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
