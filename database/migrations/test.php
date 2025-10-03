<td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
    <a href="{{ route('users.show', $user->id) }}" class="text-green-600 hover:text-green-900 mr-4" title="Detail">
        <i class="fas fa-eye"></i>
    </a>

    <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
        <i class="fas fa-pencil-alt"></i>
    </a>

    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Apakah Anda yakin?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</td>
