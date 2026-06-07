<x-app-layout title="Detail Pengisian">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Pengisian</h2>
            <p class="text-sm text-gray-500">Informasi lengkap untuk laporan pengisian nomor #{{ $pengisian->id }}.</p>
        </div>
        <div class="flex flex-wrap gap-2 items-center">
            <a href="{{ route('operator.pengisian.index') }}" class="btn-outline">Kembali ke Daftar Pengisian</a>
            <a href="{{ route('operator.dashboard') }}" class="btn-secondary">Dashboard</a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
        <x-card class="p-6">
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Antrean</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">#{{ $pengisian->antrean?->nomor_antrean ?? '-' }}</p>
                        <p class="text-sm text-slate-500">{{ $pengisian->antrean?->status ?? 'Belum tersedia' }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Kendaraan</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $pengisian->antrean?->kendaraan?->nomor_polisi ?? '-' }}</p>
                        <p class="text-sm text-slate-500">{{ $pengisian->antrean?->kendaraan?->nama_supir ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Operator</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $pengisian->operator?->name ?? auth()->user()->name }}</p>
                        <p class="text-sm text-slate-500">{{ $pengisian->operator?->email ?? '-' }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tanggal Pengisian</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ optional($pengisian->tanggal)->format('d M Y') ?? '-' }}</p>
                        <p class="text-sm text-slate-500">{{ optional($pengisian->tanggal)->format('H:i') ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Jumlah Gas</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($pengisian->jumlah_gas_liter, 0, ',', '.') }} m³</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Durasi</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $pengisian->durasi_menit ? $pengisian->durasi_menit . ' menit' : '-' }}</p>
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-50 border border-slate-100 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Status</p>
                            <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ ucfirst($pengisian->status) }}</h3>
                        </div>
                        <span class="badge {{ $pengisian->status === 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($pengisian->status) }}
                        </span>
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Catatan</p>
                    <p class="mt-3 text-sm leading-6 text-slate-700">{{ $pengisian->catatan ?? 'Tidak ada catatan tambahan.' }}</p>
                </div>
            </div>
        </x-card>

        <div class="space-y-6">
            <x-card class="p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Ringkasan Antrean</h3>
                <dl class="grid gap-4">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <dt class="text-xs uppercase tracking-[0.2em] text-slate-400">Estimasi</dt>
                        <dd class="mt-2 text-sm text-slate-800">{{ $pengisian->estimasi_menit ? $pengisian->estimasi_menit . ' menit' : '-' }}</dd>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <dt class="text-xs uppercase tracking-[0.2em] text-slate-400">Prioritas</dt>
                        <dd class="mt-2 text-sm text-slate-800">{{ $pengisian->is_prioritas ? 'Ya' : 'Tidak' }}</dd>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <dt class="text-xs uppercase tracking-[0.2em] text-slate-400">Alasan Prioritas</dt>
                        <dd class="mt-2 text-sm text-slate-800">{{ $pengisian->alasan_prioritas ?? '-' }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card class="p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Tindakan</h3>
                <p class="text-sm text-slate-600">Gunakan tombol di atas untuk kembali atau mengelola antrean lainnya.</p>
            </x-card>
        </div>
    </div>

</x-app-layout>
