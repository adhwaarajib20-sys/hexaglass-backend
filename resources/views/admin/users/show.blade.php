<x-app-layout title="Detail User">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-600 text-sm">Detail User: {{ $user->name }}</span>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-6">Data Pengguna</h3>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-xs text-gray-500">Nama Lengkap</p>
                    <p class="font-medium text-gray-800 mt-1">{{ $user->name }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Email</p>
                    <p class="font-medium text-gray-800 mt-1">{{ $user->email }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">No HP</p>
                    <p class="font-medium text-gray-800 mt-1">{{ $user->no_hp ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Role</p>
                    <p class="font-medium text-gray-800 mt-1">
                        @if($user->roles->isNotEmpty())
                            <span class="badge bg-primary-light text-primary">{{ ucfirst($user->roles->first()->name) }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <p class="font-medium text-gray-800 mt-1">
                        @if($user->status === 'aktif')
                            <span class="badge bg-green-100 text-green-700">Aktif</span>
                        @else
                            <span class="badge bg-red-100 text-red-700">Nonaktif</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Terdaftar</p>
                    <p class="font-medium text-gray-800 mt-1">{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary px-6">Edit</a>
                <a href="{{ route('admin.users.index') }}" class="btn-outline px-6">Kembali</a>
            </div>
        </div>
    </div>

</x-app-layout>
