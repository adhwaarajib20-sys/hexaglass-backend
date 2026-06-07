<x-app-layout title="Dashboard Operator">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard Operator</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan antrean dan pengisian hari ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium text-gray-500">Antrean Menunggu</div>
                <div class="text-xs text-gray-400">Hari ini</div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['antrean_menunggu'] }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium text-gray-500">Sedang Diproses</div>
                <div class="text-xs text-gray-400">Hari ini</div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['sedang_diproses'] }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium text-gray-500">Pengisian Selesai</div>
                <div class="text-xs text-gray-400">Hari ini</div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['selesai_hari_ini'] }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium text-gray-500">Total m³</div>
                <div class="text-xs text-gray-400">Hari ini</div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_liter'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        <x-card class="p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Antrean Aktif</h2>
                    <p class="text-sm text-gray-500">Urutan antrean yang sedang menunggu dan diproses.</p>
                </div>
                <a href="{{ route('operator.antrean.index') }}" class="text-sm text-primary hover:underline">Lihat semua</a>
            </div>

            <div class="space-y-3">
                @forelse($antreanAktif as $antrean)
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-gray-100 transition-colors">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">#{{ $antrean->nomor_antrean }} - {{ $antrean->kendaraan?->nomor_polisi ?? '-' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $antrean->kendaraan?->nama_supir ?? 'Supir tidak diketahui' }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $antrean->waktu_daftar ? \Carbon\Carbon::parse($antrean->waktu_daftar)->format('H:i') : '-' }}</span>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <span class="badge bg-primary-light text-primary">{{ ucfirst($antrean->status) }}</span>
                            @if($antrean->is_prioritas)
                                <span class="badge bg-orange-100 text-orange-700">Prioritas</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500 bg-gray-50 rounded-2xl border border-gray-100">Tidak ada antrean aktif saat ini.</div>
                @endforelse
            </div>
        </x-card>

        <x-card class="p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Riwayat Pengisian</h2>
                    <p class="text-sm text-gray-500">Laporan pengisian terbaru oleh Anda.</p>
                </div>
                <a href="{{ route('operator.pengisian.index') }}" class="text-sm text-primary hover:underline">Lihat semua</a>
            </div>

            <div class="space-y-3">
                @forelse($riwayatPengisian as $pengisian)
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-gray-100 transition-colors">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $pengisian->antrean?->kendaraan?->nomor_polisi ?? 'Tanpa kendaraan' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $pengisian->antrean?->kendaraan?->nama_supir ?? 'Supir tidak diketahui' }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $pengisian->tanggal?->format('H:i') ?? '-' }}</span>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <span class="badge bg-green-100 text-green-700">{{ number_format($pengisian->jumlah_gas_liter, 0, ',', '.') }} m³</span>
                            <span class="badge bg-gray-100 text-gray-600">{{ ucfirst($pengisian->status ?? 'terkirim') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500 bg-gray-50 rounded-2xl border border-gray-100">Tidak ada riwayat pengisian hari ini.</div>
                @endforelse
            </div>
        </x-card>
    </div>

    {{-- Widget Perusahaan Prioritas --}}
@php
    $perusahaanPrioritas = \App\Models\InformasiPerusahaan::where('is_prioritas', true)
        ->where('status', 'aktif')
        ->get();
@endphp

@if($perusahaanPrioritas->count() > 0)
<div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-2xl p-5 mb-6">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <span class="text-xl">⚡</span>
            <div>
                <h3 class="font-bold text-orange-800">Perusahaan Prioritas Aktif</h3>
                <p class="text-xs text-orange-600">Dahulukan kendaraan dari perusahaan berikut</p>
            </div>
        </div>
        <a href="{{ route('operator.perusahaan') }}"
           class="text-xs text-orange-600 hover:underline font-medium">
            Lihat semua →
        </a>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($perusahaanPrioritas as $p)
        <div class="bg-white border border-orange-200 rounded-xl px-4 py-2 flex items-center gap-2">
            <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
            <span class="text-sm font-medium text-gray-800">{{ $p->nama_perusahaan }}</span>
            @if($p->rencana_pengisian_harian)
            <span class="text-xs text-orange-500 bg-orange-50 px-2 py-0.5 rounded-full">
                {{ number_format($p->rencana_pengisian_harian, 0, ',', '.') }} m³/hari
            </span>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

</x-app-layout>
