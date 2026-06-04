<x-app-layout title="Laporan Saya">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Laporan Ketidaksesuaian</h2>
            <p class="text-sm text-gray-500">Laporan yang Anda buat</p>
        </div>
        <a href="{{ route('operator.laporan.create') }}" class="btn-primary">+ Buat Laporan</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Klasifikasi</th>
                        <th class="px-6 py-3 text-left">Lokasi</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Foto</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($laporan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @php $icons = ['keselamatan','lingkungan','kualitas','prosedur','lainnya']; @endphp
                            <span class="flex items-center gap-2">
                                <span class="font-medium text-gray-700">{{ ucfirst($item->klasifikasi) }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs max-w-32 truncate">{{ $item->lokasi }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge bg-gray-100 text-gray-600">📷 {{ $item->foto->count() }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @switch($item->status)
                                @case('terkirim')     <span class="badge bg-yellow-100 text-yellow-700">● Menunggu</span> @break
                                @case('diverifikasi') <span class="badge bg-green-100 text-green-700">● Diverifikasi</span> @break
                                @case('ditolak')      <span class="badge bg-red-100 text-red-700">● Ditolak</span> @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('operator.laporan.show', $item->id) }}"
                               class="text-primary hover:underline text-xs font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-3xl mb-2"><i class="fas fa-pen-to-square"></i></p>
                            <p>Belum ada laporan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $laporan->links() }}
        </div>
    </div>

</x-app-layout>