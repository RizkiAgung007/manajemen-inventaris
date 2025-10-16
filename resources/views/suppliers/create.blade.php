<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Pemasok Baru') }}
            </h2>
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('suppliers.store') }}">
                    @csrf
                    <div class="p-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Kolom Kiri --}}
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Pemasok</label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                           class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact_person" class="block font-medium text-sm text-gray-700">Contact Person</label>
                                    <input id="contact_person" type="text" name="contact_person" value="{{ old('contact_person') }}"
                                           class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('contact_person') border-red-500 @enderror">
                                    @error('contact_person')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="space-y-6">
                                <div>
                                    <label for="email" class="block font-medium text-sm text-gray-700">Alamat Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                                           class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block font-medium text-sm text-gray-700">Nomor Telepon</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                           class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Input Alamat --}}
                        <div class="mt-6">
                            <label for="address" class="block font-medium text-sm text-gray-700">Alamat</label>
                            <textarea id="address" name="address" rows="3"
                                      class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="mt-6 col-span-1 md:col-span-2">
                            <label class="block font-medium text-sm text-gray-700">Kategori yang Disediakan</label>
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-4 border rounded-md">
                                @forelse($categories as $category)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ms-2 text-sm text-gray-600">{{ $category->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada data kategori. Silakan buat kategori terlebih dahulu.</p>
                                @endforelse
                            </div>
                            @error('categories')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer Form --}}
                    <div class="p-6 bg-gray-50 border-t flex justify-end items-center space-x-4">
                        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fa-solid fa-save mr-2"></i>
                            Simpan Pemasok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
