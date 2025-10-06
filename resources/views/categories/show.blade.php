<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Kategori') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="mb-4">
                        <p class="font-bold text-lg">Nama Kategori:</p>
                        <p>{{ $category->name }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="font-bold text-lg">Dibuat pada:</p>
                        <p>{{ $category->created_at->format('d F Y, H:i') }}</p>
                    </div>

                    <div>
                        <p class="font-bold text-lg">Terakhir diperbarui:</p>
                        <p>{{ $category->updated_at->format('d F Y, H:i') }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
