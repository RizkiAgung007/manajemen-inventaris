<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Produk Baru') }}
            </h2>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            {{-- Kolom Kiri: Info Dasar --}}
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nama Produk</label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="block mt-1 w-full rounded-md shadow-sm border-gray-300 @error('name') border-red-500 @enderror">
                                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="categories" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kategori</label>
                                    <select id="categories" name="categories[]" multiple placeholder="Pilih satu atau lebih kategori..." autocomplete="off" class="block w-full @error('categories') border-red-500 @enderror">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ (collect(old('categories'))->contains($category->id)) ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('categories') <p class="mt-2 text-sm text-red-600">Kolom kategori wajib diisi.</p> @enderror
                                </div>

                                <div>
                                    <label for="suppliers" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Pemasok (Opsional)</label>
                                    <select id="suppliers" name="suppliers[]" multiple placeholder="Pilih satu atau lebih pemasok..." autocomplete="off" class="block w-full @error('suppliers') border-red-500 @enderror">
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ (collect(old('suppliers'))->contains($supplier->id)) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('suppliers') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="desc" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Deskripsi</label>
                                    <textarea id="desc" name="desc" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('desc') }}</textarea>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Inventaris & Gambar --}}
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="price" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Harga</label>
                                        <input id="price" type="number" name="price" step="100" value="{{ old('price') }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 @error('price') border-red-500 @enderror">
                                        @error('price') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="stock" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Stok Awal</label>
                                        <input id="stock" type="number" name="stock" value="{{ old('stock', 0) }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 @error('stock') border-red-500 @enderror">
                                        @error('stock') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div x-data="{ imageUrl: null }">
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Gambar Produk</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <template x-if="imageUrl">
                                                <img :src="imageUrl" class="mx-auto h-32 w-auto object-cover rounded-md shadow-sm">
                                            </template>
                                            <template x-if="!imageUrl">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </template>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image" class="relative cursor-pointerrounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                    <span class="dark:text-gray-100">Upload gambar</span>
                                                    <input id="image" name="image" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                                                </label>
                                                <p class="pl-1 dark:text-gray-300">atau tarik dan lepas</p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-300">PNG, JPG, GIF hingga 10MB</p>
                                        </div>
                                    </div>
                                    @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t flex justify-end items-center space-x-4">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#categories',{
                plugins: ['remove_button'],
                create: false,
            });

            new TomSelect('#suppliers',{
                plugins: ['remove_button'],
                create: false,
            });
        });
    </script>
    @endpush
</x-app-layout>
