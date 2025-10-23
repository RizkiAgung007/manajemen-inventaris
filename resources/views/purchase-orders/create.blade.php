<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Buat Pesanan Pembelian Baru') }}
            </h2>
            <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div x-data="{
                    products: [ { product_id: '', quantity: 1, price: 0 } ],
                    addProduct() {
                        this.products.push({ product_id: '', quantity: 1, price: 0 });
                    },
                    removeProduct(index) {
                        this.products.splice(index, 1);
                    },
                    fetchProductPrice(index) {
                        const productId = this.products[index].product_id;
                        if (!productId) return;
                        fetch(`/api/products/${productId}`)
                            .then(response => response.json())
                            .then(data => {
                                this.products[index].price = data.price;
                            });
                    }
                }">
                    <form method="POST" action="{{ route('purchase-orders.store') }}">
                        @csrf
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="supplier_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Pemasok</label>
                                    <select name="supplier_id" id="supplier_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Pilih Pemasok</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Catatan (Opsional)</label>
                                    <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                </div>
                            </div>

                            {{-- Dynamic Product Rows --}}
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300">Detail Produk</h3>
                                <div class="mt-4 space-y-4">
                                    <template x-for="(product, index) in products" :key="index">
                                        <div class="flex items-center space-x-4 p-4 border rounded-md">
                                            {{-- Product Selection --}}
                                            <div class="flex-1">
                                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Produk</label>
                                                <select :name="`products[${index}][product_id]`" x-model="product.product_id" @change="fetchProductPrice(index)" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                                    <option value="">Pilih Produk</option>
                                                    @foreach(App\Models\Product::orderBy('name')->get() as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Quantity --}}
                                            <div class="w-24">
                                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Jumlah</label>
                                                <input type="number" :name="`products[${index}][quantity]`" x-model="product.quantity" min="1" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                            </div>
                                            {{-- Price --}}
                                            <div class="w-40">
                                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Harga/Unit</label>
                                                <input type="number" :name="`products[${index}][price]`" x-model="product.price" step="0.01" min="0" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                            </div>
                                            {{-- Remove Button --}}
                                            <div class="pt-6">
                                                <button type="button" @click="removeProduct(index)" x-show="products.length > 1" class="text-red-500 hover:text-red-700">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="addProduct()" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 dark:text-gray-300 hover:text-blue-800 dark:hover:text-gray-500">
                                    <i class="fa-solid fa-plus mr-2"></i> Tambah Produk Lain
                                </button>
                            </div>
                        </div>

                        {{-- Form Footer --}}
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t flex justify-end items-center space-x-4">
                            <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <i class="fa-solid fa-save mr-2"></i> Simpan Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
