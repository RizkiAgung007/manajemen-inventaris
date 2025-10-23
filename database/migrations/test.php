<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- Menambahkan class dark: untuk teks header --}}
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Produk') }}
            </h2>
            {{-- Menambahkan class dark: untuk tombol kembali --}}
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Menambahkan class dark: untuk container utama --}}
            <div x-data="{ tab: 'detail' }" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Menambahkan class dark: untuk border tab --}}
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        {{-- Menambahkan class dark: untuk state aktif dan hover pada tab --}}
                        <button @click="tab = 'detail'"
                                :class="{ 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400': tab === 'detail', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': tab !== 'detail' }"
                                class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                            Detail Produk
                        </button>
                        <button @click="tab = 'history'"
                                :class="{ 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400': tab === 'history', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': tab !== 'history' }"
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
                                {{-- Menambahkan class dark: untuk placeholder gambar --}}
                                <div class="w-full h-96 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 dark:text-gray-500">
                                    <div class="text-center"><i class="fa-solid fa-image fa-4x"></i><p class="mt-2 text-sm">Gambar tidak tersedia</p></div>
                                </div>
                            @endif

                            <div class="mt-6 text-gray-800 dark:text-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Disediakan Oleh</h3>
                                {{ $product->suppliers->pluck('name')->implode(', ') ?: 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex flex-wrap gap-2">
                                @forelse($product->categories as $category)
                                    {{-- Menambahkan class dark: untuk badge kategori --}}
                                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-gray-700 dark:text-gray-300">Tanpa Kategori</span>
                                @endforelse
                            </div>

                            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-3">{{ $product->name }}</h1>
                            <p class="text-3xl font-light text-gray-800 dark:text-gray-200 my-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                            <div class="flex items-center space-x-2 text-sm mb-6">
                                {{-- Menambahkan class dark: untuk badge stok --}}
                                @if($product->stock == 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Stok Habis</span>
                                @elseif($product->stock < 10)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Stok Kritis ({{ $product->stock }})</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Tersedia ({{ $product->stock }})</span>
                                @endif
                            </div>

                            @can('manage-content')
                            {{-- Menambahkan class dark: untuk border pemisah --}}
                            <div class="flex items-center space-x-3 border-t border-b border-gray-200 dark:border-gray-700 py-4">
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
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Deskripsi</h3>
                                {{-- Menambahkan class dark:prose-invert untuk styling teks dari plugin typography --}}
                                <div class="mt-2 text-gray-600 dark:text-gray-300 prose dark:prose-invert max-w-none">{!! $product->desc ?? 'Tidak ada deskripsi.' !!}</div>
                            </div>

                            <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                                <p>Ditambahkan pada: {{ $product->created_at->format('d M Y, H:i') }}</p>
                                <p>Terakhir diperbarui: {{ $product->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel untuk Tab Riwayat Stok --}}
                <div x-show="tab === 'history'" class="p-6">
                    <h3 class="text-lg font-medium mb-4 dark:text-gray-100">Log Pergerakan Stok</h3>
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Perubahan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Oleh</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($stockMovements as $movement)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $movement->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            {{-- PENTING: Anda perlu menambahkan class dark mode di dalam komponen x-status-badge Anda --}}
                                            <x-status-badge :status="$movement->type" />
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-bold whitespace-nowrap {{ $movement->quantity > 0 ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                                            {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $movement->user->name ?? 'Sistem' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $movement->notes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12 text-gray-500 dark:text-gray-400">Belum ada riwayat pergerakan stok untuk produk ini.</td>
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
```

### Catatan Penting: Komponen `x-status-badge`

Di dalam tabel Riwayat Stok, Anda menggunakan komponen Blade kustom `<x-status-badge />`. Kode di atas **tidak bisa** secara otomatis menerapkan *dark mode* pada komponen tersebut.

Anda perlu membuka file komponen itu (kemungkinan di `resources/views/components/status-badge.blade.php`) dan menambahkan *class dark mode* secara manual, sama seperti yang telah kita lakukan pada *badge* stok lainnya.

**Contoh**, jika isi `status-badge.blade.php` Anda seperti ini:
```html
{{-- Contoh isi status-badge.blade.php SEBELUM diubah --}}
@props(['status'])

@php
    $colorClasses = [
        'in' => 'bg-green-100 text-green-800',
        'out' => 'bg-red-100 text-red-800',
        'adjustment' => 'bg-yellow-100 text-yellow-800',
    ];
@endphp

<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
    {{ ucfirst($status) }}
</span>
