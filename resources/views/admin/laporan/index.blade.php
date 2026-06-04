<x-app-layout title="Laporan Ketidaksesuaian">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Laporan Ketidaksesuaian</h2>
            <p class="text-sm text-gray-500">Kelola dan verifikasi laporan operasional</p>
        </div>
        <form method="GET" action="{{ route('admin.export.laporan') }}" class="flex gap-2 items-end">
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
            <button type="submit" class="btn-outline flex items-center gap-2">
                <i class="fas fa-chart-bar"></i> Export Excel
            </button>
        </form>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua Status</option>
                    <option value="terkirim"     {{ request('status') == 'terkirim'     ? 'selected' : '' }}>Terkirim</option>
                    <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="ditolak"      {{ request('status') == 'ditolak'      ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Klasifikasi</label>
                <select name="klasifikasi" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua</option>
                    @foreach(['keselamatan','lingkungan','kualitas','prosedur','lainnya'] as $k)
                    <option value="{{ $k }}" {{ request('klasifikasi') == $k ? 'selected' : '' }}>
                        {{ ucfirst($k) }}
                    </option>
                    @endforeach
                </select>
            </div>
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
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.laporan.index') }}" class="btn-outline">Reset</a>
        </form>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center text-lg">
                <i class="fas fa-hourglass-end text-yellow-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-yellow-700">{{ $laporan->where('status','terkirim')->count() }}</p>
                <p class="text-xs text-yellow-600">Menunggu Verifikasi</p>
            </div>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-lg">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-700">{{ $laporan->where('status','diverifikasi')->count() }}</p>
                <p class="text-xs text-green-600">Diverifikasi</p>
            </div>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-lg">
                <i class="fas fa-times-circle text-red-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-700">{{ $laporan->where('status','ditolak')->count() }}</p>
                <p class="text-xs text-red-600">Ditolak</p>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Pelapor</th>
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
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $item->nama_pelapor }}</p>
                            <p class="text-xs text-gray-500">{{ $item->perusahaan ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $icons = [
                                    'keselamatan'=>'<i class="fas fa-hard-hat"></i>',
                                    'lingkungan'=>'<i class="fas fa-leaf"></i>',
                                    'kualitas'=>'<i class="fas fa-bullseye"></i>',
                                    'prosedur'=>'<i class="fas fa-clipboard"></i>',
                                    'lainnya'=>'<i class="fas fa-thumbtack"></i>'
                                ];
                            @endphp
                            <span class="flex items-center gap-1.5">
                                {!! $icons[$item->klasifikasi] ?? '<i class="fas fa-thumbtack"></i>' !!}
                                <span class="text-gray-700">{{ ucfirst($item->klasifikasi) }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs max-w-32 truncate">{{ $item->lokasi }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge bg-gray-100 text-gray-600">
                                <i class="fas fa-camera"></i> {{ $item->foto->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @switch($item->status)
                                @case('terkirim')     <span class="badge bg-yellow-100 text-yellow-700">● Menunggu</span> @break
                                @case('diverifikasi') <span class="badge bg-green-100 text-green-700">● Diverifikasi</span> @break
                                @case('ditolak')      <span class="badge bg-red-100 text-red-700">● Ditolak</span> @break
                                @default              <span class="badge bg-gray-100 text-gray-600">{{ $item->status }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.laporan.show', $item->id) }}"
                               class="text-primary hover:underline text-xs font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-3xl mb-2"><i class="fas fa-file-alt"></i></p>
                            <p>Tidak ada laporan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $laporan->withQueryString()->links() }}
        </div>
    </div>

</x-app-layout>