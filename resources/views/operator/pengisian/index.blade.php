<x-app-layout title="Data Pengisian">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Data Pengisian</h2>
            <p class="text-sm text-gray-500">Riwayat pengisian gas oleh operator.</p>
        </div>
        <div class="flex flex-wrap gap-2 items-center">
            <a href="{{ route('operator.dashboard') }}" class="btn-outline">Kembali ke Dashboard</a>
        </div>
    </div>

    <x-card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Antrean</th>
                        <th class="px-6 py-3 text-left">Kendaraan</th>
                        <th class="px-6 py-3 text-left">Jumlah (L)</th>
                        <th class="px-6 py-3 text-left">Durasi</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pengisian as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-600 text-xs">{{ optional($item->tanggal)->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-primary">#{{ $item->antrean?->nomor_antrean ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $item->antrean?->kendaraan?->nomor_polisi ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $item->antrean?->kendaraan?->nama_supir ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ number_format($item->jumlah_gas_liter, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-600 text-xs">{{ $item->durasi_menit ? $item->durasi_menit . ' menit' : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="badge {{ $item->status === 'selesai' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($item->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('operator.pengisian.show', $item->id) }}" class="text-primary hover:underline text-xs font-medium">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="space-y-2">
                                    <p class="text-lg">Tidak ada data pengisian</p>
                                    <p class="text-sm">Silakan selesaikan proses pengisian pada antrean yang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $pengisian->withQueryString()->links() }}
        </div>
    </x-card>

</x-app-layout>
