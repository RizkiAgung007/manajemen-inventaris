<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Barang Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>{{ session('success') }}</p></div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p>{{ session('error') }}</p></div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('stock-out.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fa-solid fa-plus mr-2"></i> Catat Barang Keluar
                        </a>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-800 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 dark:text-gray-300 text-left">Produk</th>
                                    <th class="px-6 py-3 dark:text-gray-300 text-left">Tipe</th>
                                    <th class="px-6 py-3 dark:text-gray-300 text-left">Jumlah</th>
                                    <th class="px-6 py-3 dark:text-gray-300 text-left">Status</th>
                                    <th class="px-6 py-3 dark:text-gray-300 text-left">Tanggal</th>
                                    <th class="px-6 py-3 dark:text-gray-300 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($stockOuts as $movement)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $movement->status === 'cancelled' ? 'bg-gray-50 dark:bg-gray-800' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-300">{{ $movement->product->name ?? 'Produk Dihapus' }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">{{ $movement->notes }}</div>
                                        </td>
                                        <td class="px-6 py-4 dark:text-gray-300"><x-status-badge :status="$movement->type" /></td>
                                        <td class="px-6 py-4 text-center font-bold text-red-600">{{ $movement->quantity }}</td>
                                        <td class="px-6 py-4">
                                            <x-status-badge :status="$movement->status" />
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $movement->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if($movement->status === 'completed')
                                                <form action="{{ route('stock-out.cancel', $movement->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan transaksi ini? Stok akan dikembalikan.')">
                                                    @csrf
                                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400 font-semibold text-xs">Batalkan</button>
                                                </form>
                                            @elseif($movement->status === 'cancelled')
                                                <form action="{{ route('stock-out.destroy', $movement->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus catatan ini? Aksi ini tidak bisa dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:hover:text-red-400 font-semibold text-xs">Hapus</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-16">
                                            <div class="text-center text-gray-400">
                                                <i class="fa-solid fa-box-open fa-3x mb-3"></i>
                                                <p class="text-lg">Belum Ada Riwayat Barang Keluar</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $stockOuts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
