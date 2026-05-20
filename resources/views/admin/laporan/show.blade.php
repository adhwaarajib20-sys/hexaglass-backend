<x-app-layout title="Detail Laporan">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.laporan.index') }}" class="text-gray-400 hover:text-gray-600">
            ← Kembali
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-600 text-sm">Detail Laporan</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Detail Utama --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Info Laporan --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            @php
                                $icons = ['keselamatan'=>'⛑️','lingkungan'=>'🌿','kualitas'=>'🎯','prosedur'=>'📋','lainnya'=>'📌'];
                            @endphp
                            {{ $icons[$laporan->klasifikasi] ?? '📌' }}
                            {{ ucfirst($laporan->klasifikasi) }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            📅 {{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d F Y') }}
                            🕐 {{ $laporan->waktu_kejadian }}
                        </p>
                    </div>
                    @switch($laporan->status)
                        @case('terkirim')     <span class="badge bg-yellow-100 text-yellow-700 text-sm px-3 py-1">● Menunggu Verifikasi</span> @break
                        @case('diverifikasi') <span class="badge bg-green-100 text-green-700 text-sm px-3 py-1">● Diverifikasi</span> @break
                        @case('ditolak')      <span class="badge bg-red-100 text-red-700 text-sm px-3 py-1">● Ditolak</span> @break
                    @endswitch
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Pelapor</p>
                        <p class="font-medium text-gray-800">{{ $laporan->nama_pelapor }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Perusahaan</p>
                        <p class="font-medium text-gray-800">{{ $laporan->perusahaan ?? '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Lokasi Kejadian</p>
                        <p class="font-medium text-gray-800">📍 {{ $laporan->lokasi }}</p>
                    </div>
                </div>

                <div class="mb-5">
                    <p class="text-xs text-gray-500 mb-2">Deskripsi Kejadian</p>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $laporan->deskripsi }}</p>
                    </div>
                </div>

                @if($laporan->rekomendasi)
                <div>
                    <p class="text-xs text-gray-500 mb-2">Rekomendasi</p>
                    <div class="bg-primary-light rounded-xl p-4">
                        <p class="text-sm text-primary-dark leading-relaxed">{{ $laporan->rekomendasi }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Foto Bukti --}}
            @if($laporan->foto->count() > 0)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4">📷 Foto Bukti ({{ $laporan->foto->count() }})</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($laporan->foto as $foto)
                    <div class="group relative aspect-square rounded-xl overflow-hidden bg-gray-100 cursor-pointer"
                         onclick="openFoto('{{ $foto->url }}', '{{ $foto->keterangan_foto }}')">
                        <img src="{{ $foto->url }}"
                             alt="Foto bukti"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                            <span class="text-white opacity-0 group-hover:opacity-100 text-2xl">🔍</span>
                        </div>
                        @if($foto->keterangan_foto)
                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 p-2">
                            <p class="text-white text-xs truncate">{{ $foto->keterangan_foto }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">

            {{-- Verifikasi --}}
            @if($laporan->status === 'terkirim')
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4">⚡ Tindakan</h3>

                {{-- Verifikasi --}}
                <form action="{{ route('admin.laporan.verifikasi', $laporan->id) }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="status" value="diverifikasi">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Catatan (opsional)</label>
                        <textarea name="catatan_admin" rows="2"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Catatan verifikasi..."></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-green-600 text-white py-2.5 rounded-xl font-medium text-sm hover:bg-green-700 transition-colors">
                        ✅ Verifikasi Laporan
                    </button>
                </form>

                {{-- Tolak --}}
                <form action="{{ route('admin.laporan.verifikasi', $laporan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="ditolak">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Alasan penolakan</label>
                        <textarea name="catatan_admin" rows="2"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Alasan penolakan..."></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-red-500 text-white py-2.5 rounded-xl font-medium text-sm hover:bg-red-600 transition-colors">
                        ❌ Tolak Laporan
                    </button>
                </form>
            </div>
            @endif

            {{-- Info Pelapor --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4">👤 Info Pelapor</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">{{ strtoupper(substr($laporan->pelapor?->name ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $laporan->pelapor?->name ?? $laporan->nama_pelapor }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $laporan->pelapor?->role ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        <p>📧 {{ $laporan->pelapor?->email ?? '-' }}</p>
                        <p class="mt-1">📱 {{ $laporan->pelapor?->no_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Riwayat Verifikasi --}}
            @if($laporan->status !== 'terkirim')
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4">📋 Riwayat Verifikasi</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-medium">{{ ucfirst($laporan->status) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Oleh</span>
                        <span class="font-medium">{{ $laporan->verifikator?->name ?? '-' }}</span>
                    </div>
                    @if($laporan->catatan_admin)
                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Catatan:</p>
                        <p class="text-sm text-gray-700">{{ $laporan->catatan_admin }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Modal Foto --}}
    <div id="modalFoto" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4"
         onclick="closeFoto()">
        <button class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">✕</button>
        <img id="modalFotoImg" src="" alt="" class="max-w-full max-h-full rounded-xl object-contain">
        <p id="modalFotoKet" class="absolute bottom-4 text-white text-sm bg-black/50 px-4 py-2 rounded-lg"></p>
    </div>

    <script>
        function openFoto(url, ket) {
            document.getElementById('modalFotoImg').src = url;
            document.getElementById('modalFotoKet').textContent = ket || '';
            document.getElementById('modalFoto').classList.remove('hidden');
            document.getElementById('modalFoto').classList.add('flex');
        }
        function closeFoto() {
            document.getElementById('modalFoto').classList.add('hidden');
            document.getElementById('modalFoto').classList.remove('flex');
        }
    </script>

</x-app-layout>