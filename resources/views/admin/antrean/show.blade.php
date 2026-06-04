<x-app-layout title="Detail Antrean">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Antrean</h2>
            <p class="text-sm text-gray-500">Lihat informasi lengkap antrean kendaraan.</p>
        </div>
        <a href="{{ route('admin.antrean.index') }}" class="btn-outline">← Kembali ke Daftar Antrean</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Nomor Antrean</p>
                <p class="text-3xl font-bold text-slate-900">{{ $antrean->nomor_antrean }}</p>
                <p class="text-sm text-gray-500">{{ $antrean->tanggal?->format('d F Y') ?? '-' }}</p>
            </div>

            <div class="grid gap-4">
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <p class="font-medium text-gray-800">{{ ucfirst($antrean->status) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Validasi Satpam</p>
                    <p class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $antrean->status_validasi_satpam ?? '-')) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Prioritas</p>
                    <p class="font-medium text-gray-800">{{ $antrean->is_prioritas ? 'Ya' : 'Tidak' }}</p>
                </div>
                @if($antrean->is_prioritas && $antrean->alasan_prioritas)
                    <div>
                        <p class="text-xs text-gray-500">Alasan Prioritas</p>
                        <p class="font-medium text-gray-800">{{ $antrean->alasan_prioritas }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-gray-500">Estimasi Menit</p>
                    <p class="font-medium text-gray-800">{{ $antrean->estimasi_menit ? $antrean->estimasi_menit . ' menit' : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Operator</p>
                    <p class="font-medium text-gray-800">{{ $antrean->operator?->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Kendaraan</h3>
            <div class="grid gap-4">
                <div>
                    <p class="text-xs text-gray-500">Nama Supir</p>
                    <p class="font-medium text-gray-800">{{ $antrean->kendaraan?->nama_supir ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">No HP Supir</p>
                    <p class="font-medium text-gray-800">{{ $antrean->kendaraan?->no_hp_supir ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">No Polisi</p>
                    <p class="font-medium text-gray-800">{{ $antrean->kendaraan?->nomor_polisi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Jenis Kendaraan</p>
                    <p class="font-medium text-gray-800">{{ $antrean->kendaraan?->jenis_kendaraan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Kapasitas Tangki</p>
                    <p class="font-medium text-gray-800">{{ $antrean->kendaraan?->kapasitas_tangki ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Perusahaan</p>
                    <p class="font-medium text-gray-800">{{ $antrean->kendaraan?->perusahaan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Tambahan</h3>
        <div class="grid gap-4">
            <div>
                <p class="text-xs text-gray-500">Waktu Daftar</p>
                <p class="font-medium text-gray-800">{{ $antrean->waktu_daftar?->format('d F Y H:i') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Waktu Dipanggil</p>
                <p class="font-medium text-gray-800">{{ $antrean->waktu_dipanggil?->format('d F Y H:i') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Waktu Selesai</p>
                <p class="font-medium text-gray-800">{{ $antrean->waktu_selesai?->format('d F Y H:i') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Jumlah Gas Liter</p>
                <p class="font-medium text-gray-800">{{ $antrean->jumlah_gas_liter ? number_format($antrean->jumlah_gas_liter, 0, ',', '.') . ' L' : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Catatan</p>
                <p class="font-medium text-gray-800">{{ $antrean->catatan_pengisian ?? $antrean->keterangan ?? '-' }}</p>
            </div>
        </div>
    </div>

</x-app-layout>
