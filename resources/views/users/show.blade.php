<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pengguna: ') . $user->name }}
            </h2>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ tab: 'info' }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <button @click="tab = 'info'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'info', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'info' }"
                                class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                            Informasi Pengguna
                        </button>
                        <button @click="tab = 'log'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'log', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'log' }"
                                class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                            Log Aktivitas
                        </button>
                    </nav>
                </div>

                <div x-show="tab === 'info'" class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Data Diri Pengguna</h3>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                <i class="fas fa-pencil-alt mr-2"></i>Edit
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                    <i class="fas fa-trash-alt mr-2"></i>Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <dl>
                    <dl class="border-t border-gray-200 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-gray-900 font-semibold">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Role</dt>
                            <dd class="mt-1"><x-role-badge :role="$user->role" /></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal Bergabung</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->created_at->format('d F Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div x-show="tab === 'log'" class="p-6">
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                                    <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th> -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($activities as $log)
                                    <tr>
                                        <td class="px-6 py-4">{{ $log->activity }}</td>
                                        <!-- <td class="px-6 py-4 text-sm text-gray-500">{{ $log->ip_address }}</td> -->
                                        <td class="px-6 py-4 text-sm text-gray-500" title="{{ $log->created_at->format('d F Y, H:i:s') }}">
                                            {{ $log->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">Pengguna ini belum memiliki aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $activities->links('vendor.pagination.tailwind', ['pageName' => 'activities_page']) }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
