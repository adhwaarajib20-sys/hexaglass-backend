<x-app-layout title="Data Antrean">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Data Antrean</h2>
            <p class="text-sm text-gray-500">Kelola semua data antrean kendaraan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.export.antrean') }}"
               class="btn-outline flex items-center gap-2">
                📊 Export Excel
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal', today()->format('Y-m-d')) }}"
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ request('status') == 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipanggil" {{ request('status') == 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="dilayani"  {{ request('status') == 'dilayani'  ? 'selected' : '' }}>Dilayani</option>
                    <option value="selesai"   {{ request('status') == 'selesai'   ? 'selected' : '' }}>Selesai</option>
                    <option value="batal"     {{ request('status') == 'batal'     ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.antrean.index') }}" class="btn-outline">Reset</a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="tabelAntrean" class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">No. Antrean</th>
                        <th class="px-6 py-3 text-left">Supir & Kendaraan</th>
                        <th class="px-6 py-3 text-left">Perusahaan</th>
                        <th class="px-6 py-3 text-left">Prioritas</th>
                        <th class="px-6 py-3 text-left">Estimasi</th>
                        <th class="px-6 py-3 text-left">Gas (Liter)</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Operator</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($antrean as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-bold text-primary">{{ $item->nomor_antrean }}</span>
                            <p class="text-xs text-gray-400 mt-0.5">{{ Carbon\Carbon::parse($item->waktu_daftar)->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $item->kendaraan?->nama_supir ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $item->kendaraan?->nomor_polisi }} • {{ $item->kendaraan?->jenis_kendaraan }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $item->kendaraan?->perusahaan ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($item->is_prioritas)
                                <span class="badge bg-orange-100 text-orange-700">⚡ Prioritas</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-500">Normal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">
                            {{ $item->estimasi_menit ? $item->estimasi_menit . ' menit' : '-' }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $item->jumlah_gas_liter ? number_format($item->jumlah_gas_liter, 0, ',', '.') . ' L' : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @switch($item->status)
                                @case('menunggu')  <span class="badge bg-yellow-100 text-yellow-700">● Menunggu</span> @break
                                @case('dipanggil') <span class="badge bg-blue-100 text-blue-700">● Dipanggil</span> @break
                                @case('dilayani')  <span class="badge bg-purple-100 text-purple-700">● Dilayani</span> @break
                                @case('selesai')   <span class="badge bg-green-100 text-green-700">● Selesai</span> @break
                                @case('batal')     <span class="badge bg-red-100 text-red-700">● Batal</span> @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">{{ $item->operator?->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.antrean.show', $item->id) }}"
                               class="text-primary hover:underline text-xs font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-3xl mb-2">📋</p>
                            <p>Tidak ada data antrean</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $antrean->withQueryString()->links() }}
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabelAntrean').DataTable({
                pageLength: 20,
                searching: true,
                paging: false,
                info: false,
                language: { search: 'Cari dalam tabel:' }
            });
        });
    </script>

</x-app-layout>