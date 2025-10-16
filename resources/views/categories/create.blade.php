<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Kategori Baru') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="p-6 max-w-lg mx-auto">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Kategori</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                   class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror">
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Ganti bagian checkbox dengan dropdown ini --}}
                        <div class="mt-6">
                            <label class="block font-medium text-sm text-gray-700">Pemasok yang Menyediakan Kategori Ini</label>
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 border rounded-md">
                                @forelse($suppliers as $supplier)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="suppliers[]" value="{{ $supplier->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ms-2 text-sm text-gray-600">{{ $supplier->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">Belum ada data pemasok. Silakan buat data pemasok terlebih dahulu.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 border-t flex justify-end items-center space-x-4">
                        <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fa-solid fa-save mr-2"></i>
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
