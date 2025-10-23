<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Laporan #{{ $stockOpname->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Informasi Laporan --}}
                <div class="p-6 border-b">
                    <h3 class="text-lg font-medium">Informasi Laporan</h3>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <p><strong class="text-gray-600 dark:text-gray-300">ID Laporan:</strong> #{{ $stockOpname->id }}</p>
                        <p><strong class="text-gray-600 dark:text-gray-300">Status:</strong>
                            <x-status-badge :status="$stockOpname->status" />
                        </p>
                        <p><strong class="text-gray-600 dark:text-gray-300">Dibuat Oleh:</strong> {{ $stockOpname->user->name }}</p>
                        <p><strong class="text-gray-600 dark:text-gray-300">Tanggal:</strong> {{ $stockOpname->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Bagian Tabel Detail Perbandingan Stok --}}
                <div class="p-6">
                     <h3 class="text-lg font-medium mb-4">Detail Perbandingan Stok</h3>
                     <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stok Sistem</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stok Fisik</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Selisih</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($stockOpname->details as $detail)
                                    @php $variance = $detail->physical_stock - $detail->system_stock; @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $detail->product->name }}</td>
                                        <td class="px-6 py-4 text-center">{{ $detail->system_stock }}</td>
                                        <td class="px-6 py-4 text-center font-bold">{{ $detail->physical_stock }}</td>
                                        <td class="px-6 py-4 text-center font-bold {{ $variance > 0 ? 'text-green-600' : ($variance < 0 ? 'text-red-600' : '') }}">
                                            {{ $variance > 0 ? '+' : '' }}{{ $variance }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-12 text-gray-500">
                                            Laporan ini tidak memiliki detail produk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                     </div>
                </div>

                <div class="p-6 bg-gray-50 border-t flex justify-between items-center">
                    {{-- Tombol Download PDF (selalu tampil) --}}
                    <a href="{{ route('reviews.pdf', $stockOpname->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF
                    </a>

                    {{-- Grup Tombol Aksi (hanya tampil jika status 'pending') --}}
                    @if($stockOpname->status == 'pending')
                    <div class="flex items-center space-x-4">
                        <form action="{{ route('reviews.reject', $stockOpname->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK laporan ini? Stok tidak akan diubah.')">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                <i class="fa-solid fa-times mr-2"></i> Tolak
                            </button>
                        </form>
                        <form action="{{ route('reviews.approve', $stockOpname->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI laporan ini? Stok produk akan diperbarui.')">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                <i class="fa-solid fa-check mr-2"></i> Setujui & Sinkronkan
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
