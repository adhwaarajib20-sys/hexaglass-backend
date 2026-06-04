<x-app-layout title="Manajemen User">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manajemen User</h2>
            <p class="text-sm text-gray-500">Kelola akun pengguna sistem</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary flex items-center gap-2">
            + Tambah User
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama atau email..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Role</label>
                <select name="role" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua Role</option>
                    @foreach(['admin','operator','satpam','supir'] as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn-outline">Reset</a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Pengguna</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">No HP</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Bergabung</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0
                                    @switch($user->role)
                                        @case('admin')    bg-red-100 text-red-700 @break
                                        @case('operator') bg-blue-100 text-blue-700 @break
                                        @case('satpam')   bg-yellow-100 text-yellow-700 @break
                                        @default          bg-green-100 text-green-700
                                    @endswitch">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleConfig = [
                                    'admin'    => ['bg-red-100 text-red-700',    '👑 Admin'],
                                    'operator' => ['bg-blue-100 text-blue-700',  '👷 Operator'],
                                    'satpam'   => ['bg-yellow-100 text-yellow-700', '🛡️ Satpam'],
                                    'supir'    => ['bg-green-100 text-green-700', '🚛 Supir'],
                                ];
                                $rc = $roleConfig[$user->role] ?? ['bg-gray-100 text-gray-600', $user->role];
                            @endphp
                            <span class="badge {{ $rc[0] }}">{{ $rc[1] }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $user->no_hp ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($user->status === 'aktif')
                                <span class="badge bg-green-100 text-green-700">● Aktif</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-500">○ Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="text-primary hover:underline text-xs font-medium">Edit</a>

                                <form action="{{ route('admin.users.toggle-status', $user->id) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-xs {{ $user->status === 'aktif' ? 'text-red-500 hover:underline' : 'text-green-600 hover:underline' }}">
                                        {{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:underline">Hapus</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-3xl mb-2">👥</p>
                            <p>Tidak ada pengguna</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

</x-app-layout>