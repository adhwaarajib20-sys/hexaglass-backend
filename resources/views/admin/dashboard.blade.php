<x-app-layout title="Dashboard Admin">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-xs text-gray-500">Hari ini</span>
            </div>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ $stats['total_antrean'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Antrean</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-yellow-500 font-medium">● Menunggu</span>
            </div>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ $stats['menunggu'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Kendaraan Menunggu</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-green-500 font-medium">● Selesai</span>
            </div>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ $stats['selesai'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Kendaraan Selesai</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-xs text-gray-500">m³</span>
            </div>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ number_format($stats['total_liter'], 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Gas Tersalurkan</p>
        </div>

    </div>

    {{-- Row 2 Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <div class="stat-card bg-red-50 border-red-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-700">{{ $stats['total_laporan'] }}</p>
                    <p class="text-sm text-red-500">Laporan Menunggu</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-purple-50 border-purple-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-700">{{ $stats['total_operator'] }}</p>
                    <p class="text-sm text-purple-500">Operator Aktif</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-orange-50 border-orange-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-orange-700">{{ $stats['dilayani'] }}</p>
                    <p class="text-sm text-orange-500">Sedang Dilayani</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik + Tabel --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

        {{-- Grafik --}}
        <x-card class="lg:col-span-2 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-gray-800">Aktivitas Pengisian</h3>
                    <p class="text-sm text-gray-500">7 hari terakhir</p>
                </div>
                <div class="flex gap-2">
                    <span class="badge bg-primary-light text-primary">● Antrean</span>
                    <span class="badge bg-accent-light text-accent">● Gas (m³)</span>
                </div>
            </div>
            <div id="chartHarian"></div>
            <div id="grafik-data" data-json='@json($grafikHarian)' class="hidden"></div>
        </x-card>

        {{-- Laporan Terbaru --}}
        <x-card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Laporan Masuk</h3>
                <a href="{{ route('admin.laporan.index') }}" class="text-xs text-primary hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse($laporanTerbaru as $laporan)
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 text-sm">
                        @switch($laporan->klasifikasi)
                            @case('keselamatan') <i class="fas fa-hard-hat text-red-600"></i> @break
                            @case('lingkungan')  <i class="fas fa-leaf text-green-600"></i> @break
                            @case('kualitas')    <i class="fas fa-bullseye text-blue-600"></i> @break
                            @case('prosedur')    <i class="fas fa-clipboard text-purple-600"></i> @break
                            @default             <i class="fas fa-thumbtack text-gray-600"></i>
                        @endswitch
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ $laporan->nama_pelapor }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $laporan->lokasi }}</p>
                        <p class="text-xs text-gray-400">{{ $laporan->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Tidak ada laporan baru</p>
                @endforelse
            </div>
        </x-card>
    </div>

    {{-- Tabel Antrean Hari Ini --}}
    <x-card class="overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div>
                <h3 class="font-bold text-gray-800">Antrean Hari Ini</h3>
                <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
            </div>
            <div class="flex gap-2">
                <form method="GET" action="{{ route('admin.export.antrean') }}" class="flex gap-2 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Periode Export</label>
                        <select name="period" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Pilih Periode</option>
                            <option value="hari">Hari Ini</option>
                            <option value="minggu">Minggu Ini</option>
                            <option value="bulan">Bulan Ini</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-outline flex items-center gap-2 h-fit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </button>
                </form>
                <a href="{{ route('admin.antrean.index') }}" class="btn-primary">Lihat Semua</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="tabelAntrean" class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">No. Antrean</th>
                        <th class="px-6 py-3 text-left">Supir</th>
                        <th class="px-6 py-3 text-left">Kendaraan</th>
                        <th class="px-6 py-3 text-left">Perusahaan</th>
                        <th class="px-6 py-3 text-left">Prioritas</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($antreanTerbaru as $antrean)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-mono font-medium text-primary text-xs">
                            {{ $antrean->nomor_antrean }}
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800">{{ $antrean->kendaraan?->nama_supir ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $antrean->kendaraan?->no_hp_supir ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800">{{ $antrean->kendaraan?->nomor_polisi ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $antrean->kendaraan?->jenis_kendaraan ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $antrean->kendaraan?->perusahaan ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($antrean->is_prioritas)
                                <span class="badge bg-orange-100 text-orange-700"><i class="fas fa-bolt"></i> Prioritas</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600">Normal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @switch($antrean->status)
                                @case('menunggu')
                                    <span class="badge bg-yellow-100 text-yellow-700">● Menunggu</span>
                                    @break
                                @case('dipanggil')
                                    <span class="badge bg-blue-100 text-blue-700">● Dipanggil</span>
                                    @break
                                @case('dilayani')
                                    <span class="badge bg-purple-100 text-purple-700">● Dilayani</span>
                                    @break
                                @case('selesai')
                                    <span class="badge bg-green-100 text-green-700">● Selesai</span>
                                    @break
                                @case('batal')
                                    <span class="badge bg-red-100 text-red-700">● Batal</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $antrean->waktu_daftar ? \Carbon\Carbon::parse($antrean->waktu_daftar)->format('H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Script Grafik --}}
    <script>
        // Data dari PHP (dibaca dari elemen data untuk menghindari parsing blade di dalam skrip)
        const grafikEl = document.getElementById('grafik-data');
        const grafikData = grafikEl ? JSON.parse(grafikEl.getAttribute('data-json')) : [];

        const options = {
            series: [
                {
                    name: 'Antrean',
                    type: 'bar',
                    data: grafikData.map(d => d.antrean)
                },
                {
                    name: 'Gas (m³)',
                    type: 'line',
                    data: grafikData.map(d => d.liter)
                }
            ],
            chart: {
                height: 280,
                type: 'line',
                toolbar: { show: false },
                fontFamily: 'inherit',
            },
            colors: ['#1a7a2e', '#e8650a'],
            plotOptions: {
                bar: { borderRadius: 6, columnWidth: '40%' }
            },
            dataLabels: { enabled: false },
            stroke: { width: [0, 3], curve: 'smooth' },
            xaxis: {
                categories: grafikData.map(d => d.label),
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: [
                { title: { text: 'Antrean', style: { fontSize: '11px' } } },
                { opposite: true, title: { text: 'm³', style: { fontSize: '11px' } } }
            ],
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
            legend: { position: 'top', horizontalAlign: 'right' },
            tooltip: { shared: true, intersect: false }
        };

        new ApexCharts(document.querySelector('#chartHarian'), options).render();

        // DataTables Configuration
        $(document).ready(function() {
            $('#tabelAntrean').DataTable({
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                ordering: false,
                searching: true,
                language: {
                    search: 'Cari:',
                    searchPlaceholder: 'Ketik untuk mencari...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ hingga _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(disaring dari _MAX_ total data)',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                },
                dom: '<"dataTables_top"lf>t<"dataTables_bottom"ip>',
                columnDefs: [
                    { orderable: false, targets: '_all' }
                ]
            });
        });
    </script>

</x-app-layout>