<x-app-layout title="Informasi Perusahaan">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Informasi Perusahaan</h2>
            <p class="text-sm text-gray-500">Data perusahaan untuk acuan prioritas antrean</p>
        </div>
        <a href="{{ route('admin.perusahaan.create') }}" class="btn-primary">+ Tambah Perusahaan</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Nama Perusahaan</th>
                        <th class="px-6 py-3 text-left">Prioritas</th>
                        <th class=\"px-6 py-3 text-left\">Volume (m³)</th>
                        <th class="px-6 py-3 text-left">Rencana Harian (L)</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Keterangan</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($perusahaan as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $item->nama_perusahaan }}</p>
                            <p class="text-xs text-gray-400">Oleh: {{ $item->createdBy?->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_prioritas)
                                <span class="badge bg-orange-100 text-orange-700">⚡ Prioritas</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-500">Normal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $item->volume ? number_format($item->volume, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $item->rencana_pengisian_harian ? number_format($item->rencana_pengisian_harian, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status === 'aktif')
                                <span class="badge bg-green-100 text-green-700">● Aktif</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-500">○ Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs max-w-48 truncate">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.perusahaan.edit', $item->id) }}"
                                   class="text-primary hover:underline text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.perusahaan.destroy', $item->id) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Hapus perusahaan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:underline text-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-3xl mb-2"><i class="fas fa-building"></i></p>
                            <p>Belum ada data perusahaan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $perusahaan->links() }}
        </div>
    </div>

</x-app-layout>