<x-app-layout title="Rekap Pengisian Gas">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Rekap Pengisian Gas</h2>
            <p class="text-sm text-gray-500">Riwayat distribusi gas ke kendaraan</p>
        </div>
        <form method="GET" action="{{ route('admin.export.pengisian') }}" class="flex gap-2 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" required
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" required
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <button type="submit" class="btn-outline flex items-center gap-2"><i class="fas fa-chart-bar"></i> Export Excel</button>
        </form>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-4 mb-5">
        <div class="stat-card">
            <p class="text-xs text-gray-500 mb-1">Total Pengisian</p>
            <p class="text-3xl font-bold text-primary">{{ $pengisian->total() }}</p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-gray-500 mb-1">Total Gas Tersalurkan</p>
            <p class="text-3xl font-bold text-primary">{{ number_format($totalLiter, 0, ',', '.') }} <span class="text-base font-normal text-gray-400">L</span></p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-gray-500 mb-1">Rata-rata per Pengisian</p>
            <p class="text-3xl font-bold text-primary">
                {{ $pengisian->total() > 0 ? number_format($totalLiter / $pengisian->total(), 0, ',', '.') : 0 }}
                <span class="text-base font-normal text-gray-400">L</span>
            </p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}"
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Operator</label>
                <select name="operator_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua Operator</option>
                    @foreach($operators as $op)
                    <option value="{{ $op->id }}" {{ request('operator_id') == $op->id ? 'selected' : '' }}>
                        {{ $op->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.pengisian.index') }}" class="btn-outline">Reset</a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">No. Antrean</th>
                        <th class="px-6 py-3 text-left">Kendaraan & Supir</th>
                        <th class="px-6 py-3 text-left">Perusahaan</th>
                        <th class="px-6 py-3 text-right">Gas (Liter)</th>
                        <th class="px-6 py-3 text-left">Durasi</th>
                        <th class="px-6 py-3 text-left">Prioritas</th>
                        <th class="px-6 py-3 text-left">Operator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pengisian as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-600 text-xs">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-bold text-primary">
                                {{ $item->antrean?->nomor_antrean }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $item->antrean?->kendaraan?->nama_supir }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $item->antrean?->kendaraan?->nomor_polisi }} •
                                {{ $item->antrean?->kendaraan?->jenis_kendaraan }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">
                            {{ $item->antrean?->kendaraan?->perusahaan ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-primary text-base">
                                {{ number_format($item->jumlah_gas_liter, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-gray-400"> L</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">
                            {{ $item->durasi_menit ? $item->durasi_menit . ' mnt' : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_prioritas)
                                <span class="badge bg-orange-100 text-orange-700">⚡</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">
                            {{ $item->operator?->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-3xl mb-2"><i class="fas fa-gas-pump"></i></p>
                            <p>Belum ada data pengisian</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $pengisian->withQueryString()->links() }}
        </div>
    </div>

</x-app-layout>