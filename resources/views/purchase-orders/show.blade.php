<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pesanan Pembelian #' . $purchaseOrder->id) }}
            </h2>
            <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Kartu Informasi Utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Informasi Pesanan</h3>
                        </div>
                        {{-- Tombol aksi hanya muncul jika status 'pending' --}}
                        @if($purchaseOrder->status == 'pending')
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600"">
                                <i class="fas fa-pencil-alt mr-2"></i>Edit
                            </a>
                            <form action="{{ route('purchase-orders.destroy', $purchaseOrder->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                    <i class="fas fa-trash-alt mr-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    <dl class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Nomor Pesanan</dt>
                            <dd class="mt-1 text-gray-900 font-semibold">#{{ $purchaseOrder->id }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Pemasok</dt>
                            <dd class="mt-1 text-gray-900">{{ $purchaseOrder->supplier->name }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Status</dt>
                            <dd class="mt-1"><x-status-badge :status="$purchaseOrder->status" /></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Dibuat Oleh</dt>
                            <dd class="mt-1 text-gray-900">{{ $purchaseOrder->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal</dt>
                            <dd class="mt-1 text-gray-900">{{ $purchaseOrder->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                         <div class="sm:col-span-3">
                            <dt class="font-medium text-gray-500">Catatan</dt>
                            <dd class="mt-1 text-gray-900">{{ $purchaseOrder->notes ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                @if($purchaseOrder->status == 'pending')
                <div class="p-6 bg-gray-50 border-t flex justify-end">
                    <form action="{{ route('purchase-orders.receive', $purchaseOrder->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menandai pesanan ini sebagai DITERIMA? Stok produk akan ditambahkan secara otomatis.')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <i class="fa-solid fa-check-double mr-2"></i> Tandai sebagai Diterima & Tambah Stok
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- Kartu Detail Produk --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Produk dalam Pesanan</h3>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga/Unit</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $grandTotal = 0; @endphp
                                @foreach ($purchaseOrder->products as $product)
                                    @php
                                        $subtotal = $product->pivot->quantity * $product->pivot->price;
                                        $grandTotal += $subtotal;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4">{{ $product->name }}</td>
                                        <td class="px-6 py-4 text-center">{{ $product->pivot->quantity }}</td>
                                        <td class="px-6 py-4 text-right">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                             <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right font-bold text-gray-700 uppercase">Total Keseluruhan</td>
                                    <td class="px-6 py-3 text-right font-bold text-gray-900">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
