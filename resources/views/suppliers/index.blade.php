<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pemasok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Sukses</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="flex justify-end mb-4">
                        @can('manage-content')
                            <a href="{{ route('suppliers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Pemasok
                            </a>
                        @endcan
                    </div>

                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-800 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Pemasok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($suppliers as $supplier)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $supplier->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">{{ $supplier->contact_person }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $supplier->email }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">{{ $supplier->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">

                                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="p-2 text-gray-400 hover:text-blue-600 rounded-md hover:bg-blue-100 dark:hover:bg-gray-700" title="Detail">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                @can('manage-content')
                                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="p-2 text-gray-400 hover:text-yellow-600 rounded-md hover:bg-yellow-100 dark:hover:bg-gray-700" title="Edit">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemasok ini?');" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-md hover:bg-red-100 dark:hover:bg-gray-700" title="Hapus">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-16">
                                            <div class="text-center text-gray-400">
                                                <i class="fa-solid fa-truck-field fa-3x mb-3"></i>
                                                <p class="text-lg">Belum Ada Data Pemasok</p>
                                                <p class="text-sm">Silakan tambahkan pemasok baru.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
