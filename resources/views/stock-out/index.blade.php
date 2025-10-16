<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Barang Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 ...">Produk</th>
                                    <th class="px-6 py-3 ...">Tipe</th>
                                    <th class="px-6 py-3 ...">Jumlah</th>
                                    <th class="px-6 py-3 ...">Status</th>
                                    <th class="px-6 py-3 ...">Tanggal</th>
                                    <th class="px-6 py-3 ...">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($stockOuts as $movement)
                                    <tr class="hover:bg-gray-50 {{ $movement->status === 'cancelled' ? 'bg-gray-50' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $movement->product->name ?? 'Produk Dihapus' }}</div>
                                            <div class="text-sm text-gray-500">{{ $movement->notes }}</div>
                                        </td>
                                        <td class="px-6 py-4"><x-status-badge :status="$movement->type" /></td>
                                        <td class="px-6 py-4 text-center font-bold text-red-600">{{ $movement->quantity }}</td>
                                        <td class="px-6 py-4">
                                            {{-- [PERBAIKAN] Tampilkan Status Transaksi --}}
                                            <x-status-badge :status="$movement->status" />
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $movement->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            {{-- [PERBAIKAN] Logika Tombol Aksi --}}
                                            @if($movement->status === 'completed')
                                                <form action="{{ route('stock-out.cancel', $movement->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan transaksi ini? Stok akan dikembalikan.')">
                                                    @csrf
                                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 font-semibold text-xs">Batalkan</button>
                                                </form>
                                            @elseif($movement->status === 'cancelled')
                                                <form action="{{ route('stock-out.destroy', $movement->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus catatan ini? Aksi ini tidak bisa dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs">Hapus</button>
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
