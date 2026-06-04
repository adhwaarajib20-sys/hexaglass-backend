<x-app-layout title="Proses Antrean">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('operator.antrean.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i> Kembali</a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-600 text-sm">{{ $antrean->nomor_antrean }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Info Kendaraan --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <span class="font-mono text-lg font-bold text-primary">{{ $antrean->nomor_antrean }}</span>
                        @if($antrean->is_prioritas)
                            <span class="badge bg-orange-100 text-orange-600 ml-2"><i class="fas fa-bolt"></i> Prioritas</span>
                        @endif
                    </div>
                    @switch($antrean->status)
                        @case('menunggu')  <span class="badge bg-yellow-100 text-yellow-700 text-sm"><i class="fas fa-circle"></i> Menunggu</span> @break
                        @case('dipanggil') <span class="badge bg-blue-100 text-blue-700 text-sm"><i class="fas fa-circle"></i> Dipanggil</span> @break
                        @case('dilayani')  <span class="badge bg-purple-100 text-purple-700 text-sm"><i class="fas fa-circle"></i> Dilayani</span> @break
                        @case('selesai')   <span class="badge bg-green-100 text-green-700 text-sm"><i class="fas fa-circle"></i> Selesai</span> @break
                    @endswitch
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div><p class="text-xs text-gray-500">Nama Supir</p><p class="font-medium">{{ $antrean->kendaraan?->nama_supir }}</p></div>
                    <div><p class="text-xs text-gray-500">No HP</p><p class="font-medium">{{ $antrean->kendaraan?->no_hp_supir }}</p></div>
                    <div><p class="text-xs text-gray-500">No Polisi</p><p class="font-medium font-mono">{{ $antrean->kendaraan?->nomor_polisi }}</p></div>
                    <div><p class="text-xs text-gray-500">Jenis Kendaraan</p><p class="font-medium">{{ $antrean->kendaraan?->jenis_kendaraan }}</p></div>
                    <div><p class="text-xs text-gray-500">Kapasitas</p><p class="font-medium">{{ $antrean->kendaraan?->kapasitas_tangki }}</p></div>
                    <div><p class="text-xs text-gray-500">Perusahaan</p><p class="font-medium">{{ $antrean->kendaraan?->perusahaan ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Waktu Daftar</p><p class="font-medium">{{ $antrean->waktu_daftar ? \Carbon\Carbon::parse($antrean->waktu_daftar)->format('H:i') : '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Waktu Dipanggil</p><p class="font-medium">{{ $antrean->waktu_dipanggil ? \Carbon\Carbon::parse($antrean->waktu_dipanggil)->format('H:i') : '-' }}</p></div>
                </div>

                @if($antrean->alasan_prioritas)
                <div class="mt-4 p-3 bg-orange-50 rounded-xl">
                    <p class="text-xs text-orange-600 font-medium"><i class="fas fa-bolt"></i> Alasan Prioritas:</p>
                    <p class="text-sm text-orange-700 mt-1">{{ $antrean->alasan_prioritas }}</p>
                </div>
                @endif
            </div>

            {{-- Input Pengisian (hanya jika dilayani) --}}
            @if($antrean->status === 'dilayani' && !$antrean->laporanPengisian)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4"><i class="fas fa-gas-pump"></i> Input Hasil Pengisian</h3>
                <form action="{{ route('operator.pengisian.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="antrean_id" value="{{ $antrean->id }}">

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jumlah Gas (Liter) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah_gas_liter" step="0.01" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Contoh: 5000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Estimasi (Menit)</label>
                            <input type="number" name="estimasi_menit" value="{{ $antrean->estimasi_menit }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Estimasi waktu">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Pengisian</label>
                        <textarea name="catatan" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Catatan tambahan pengisian..."></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-primary text-white py-3 rounded-xl font-semibold text-sm hover:bg-primary-dark transition-colors">
                            <i class="fas fa-check"></i> Simpan & Selesaikan Pengisian
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- Hasil Pengisian (jika sudah selesai) --}}
            @if($antrean->laporanPengisian)
            <div class="bg-green-50 border border-green-100 rounded-2xl p-6">
                <h3 class="font-bold text-green-800 mb-4"><i class="fas fa-check-circle"></i> Hasil Pengisian</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-green-600 text-xs">Jumlah Gas</p>
                        <p class="font-bold text-green-800 text-xl">{{ number_format($antrean->laporanPengisian->jumlah_gas_liter, 0, ',', '.') }} L</p>
                    </div>
                    <div>
                        <p class="text-green-600 text-xs">Durasi</p>
                        <p class="font-bold text-green-800 text-xl">{{ $antrean->laporanPengisian->durasi_menit ?? '-' }} mnt</p>
                    </div>
                </div>
                @if($antrean->laporanPengisian->catatan)
                <p class="text-sm text-green-700 mt-3"><i class="fas fa-sticky-note"></i> {{ $antrean->laporanPengisian->catatan }}</p>
                @endif
            </div>
            @endif
        </div>

        {{-- Sidebar Actions --}}
        <div class="space-y-4">

            {{-- Update Status --}}
            @if(!in_array($antrean->status, ['selesai','batal']))
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4"><i class="fas fa-bolt"></i> Update Status</h3>
                <div class="space-y-2">
                    @if($antrean->status === 'menunggu')
                    <form action="{{ route('operator.antrean.panggil', $antrean->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-500 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-blue-600 transition-colors">
                            <i class="fas fa-bullhorn"></i> Panggil Antrean
                        </button>
                    </form>
                    @endif

                    @if($antrean->status === 'dipanggil')
                    <form action="{{ route('operator.antrean.status', $antrean->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="dilayani">
                        <button type="submit" class="w-full bg-purple-500 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-purple-600 transition-colors">
                            <i class="fas fa-cog"></i> Mulai Layani
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('operator.antrean.status', $antrean->id) }}" method="POST"
                          onsubmit="return confirm('Batalkan antrean ini?')">
                        @csrf
                        <input type="hidden" name="status" value="batal">
                        <button type="submit" class="w-full border border-red-300 text-red-500 py-2.5 rounded-xl text-sm font-medium hover:bg-red-50 transition-colors">
                            <i class="fas fa-times-circle"></i> Batalkan Antrean
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Set Prioritas & Estimasi --}}
            @if(!in_array($antrean->status, ['selesai','batal']))
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4"><i class="fas fa-cog"></i> Prioritas & Estimasi</h3>
                <form action="{{ route('operator.antrean.prioritas', $antrean->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Estimasi Waktu (menit)</label>
                        <input type="number" name="estimasi_menit" value="{{ $antrean->estimasi_menit }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Menit">
                    </div>

                    <div class="mb-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_prioritas" value="1"
                                {{ $antrean->is_prioritas ? 'checked' : '' }}
                                class="w-4 h-4 text-primary rounded"
                                x-model="isPrioritas">
                            <span class="text-sm font-medium text-gray-700">Tandai sebagai Prioritas</span>
                        </label>
                    </div>

                    <div class="mb-3" x-show="isPrioritas" x-data="{ isPrioritas: {{ $antrean->is_prioritas ? 'true' : 'false' }} }">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Alasan Prioritas</label>
                        <textarea name="alasan_prioritas" rows="2"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Alasan...">{{ $antrean->alasan_prioritas }}</textarea>
                    </div>

                    {{-- Info Perusahaan --}}
@php
    $infoPrioritas = \App\Models\InformasiPerusahaan::where('status', 'aktif')
        ->where('nama_perusahaan', 'like', '%'.($antrean->kendaraan?->perusahaan ?? '').'%')
        ->first();
@endphp

@if($infoPrioritas)
<div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
    <h3 class="font-bold text-orange-800 mb-3 flex items-center gap-2">
        <i class="fas fa-bolt"></i> Info Perusahaan
    </h3>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-orange-600">Nama</span>
            <span class="font-bold text-orange-800">{{ $infoPrioritas->nama_perusahaan }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-orange-600">Status</span>
            @if($infoPrioritas->is_prioritas)
                <span class="badge bg-orange-100 text-orange-700"><i class="fas fa-bolt"></i> Prioritas</span>
            @else
                <span class="badge bg-gray-100 text-gray-600">Normal</span>
            @endif
        </div>
        @if($infoPrioritas->volume)
        <div class="flex justify-between">
            <span class="text-orange-600">Volume</span>
            <span class="font-medium">{{ number_format($infoPrioritas->volume, 0, ',', '.') }} L</span>
        </div>
        @endif
        @if($infoPrioritas->rencana_pengisian_harian)
        <div class="flex justify-between">
            <span class="text-orange-600">Target Harian</span>
            <span class="font-medium">{{ number_format($infoPrioritas->rencana_pengisian_harian, 0, ',', '.') }} L</span>
        </div>
        @endif
        @if($infoPrioritas->keterangan)
        <div class="mt-2 p-2 bg-orange-100 rounded-lg">
            <p class="text-xs text-orange-700">{{ $infoPrioritas->keterangan }}</p>
        </div>
        @endif
    </div>
</div>
@endif

                    <button type="submit" class="w-full btn-primary">Simpan</button>
                </form>
            </div>
            @endif

        </div>
    </div>

</x-app-layout>