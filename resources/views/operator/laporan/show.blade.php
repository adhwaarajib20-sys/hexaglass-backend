<x-app-layout title="Detail Laporan">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Laporan Ketidaksesuaian</h2>
            <p class="text-sm text-gray-500">Detail laporan yang Anda kirimkan.</p>
        </div>
        <div class="flex flex-wrap gap-2 items-center">
            <a href="{{ route('operator.laporan.index') }}" class="btn-outline">Kembali</a>
        </div>
    </div>

    <x-card>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-2">
                <p class="text-xs text-gray-500 uppercase tracking-[0.12em]">Nama Pelapor</p>
                <p class="text-gray-800">{{ $laporan->nama_pelapor }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs text-gray-500 uppercase tracking-[0.12em]">Perusahaan</p>
                <p class="text-gray-800">{{ $laporan->perusahaan ?? '-' }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs text-gray-500 uppercase tracking-[0.12em]">Tanggal Kejadian</p>
                <p class="text-gray-800">{{ optional($laporan->tanggal_kejadian)->format('d/m/Y') }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs text-gray-500 uppercase tracking-[0.12em]">Waktu Kejadian</p>
                <p class="text-gray-800">{{ $laporan->waktu_kejadian }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs text-gray-500 uppercase tracking-[0.12em]">Klasifikasi</p>
                <p class="text-gray-800">{{ ucfirst($laporan->klasifikasi) }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs text-gray-500 uppercase tracking-[0.12em]">Status</p>
                <p class="font-semibold">
                    @if($laporan->status === 'terkirim')
                        <span class="badge bg-yellow-100 text-yellow-700">Menunggu</span>
                    @elseif($laporan->status === 'diverifikasi')
                        <span class="badge bg-green-100 text-green-700">Diverifikasi</span>
                    @elseif($laporan->status === 'ditolak')
                        <span class="badge bg-red-100 text-red-700">Ditolak</span>
                    @else
                        <span class="badge bg-gray-100 text-gray-600">{{ ucfirst($laporan->status) }}</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Deskripsi Kejadian</p>
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-gray-700 text-sm whitespace-pre-line">{{ $laporan->deskripsi }}</div>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Rekomendasi</p>
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-gray-700 text-sm whitespace-pre-line">{{ $laporan->rekomendasi ?? '-' }}</div>
            </div>

            @if($laporan->foto->count() > 0)
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Foto Bukti</p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($laporan->foto as $foto)
                    <button type="button" onclick="openFoto('{{ Storage::url($foto->path_foto) }}')"
                            class="overflow-hidden rounded-2xl bg-gray-100 hover:ring-2 hover:ring-primary transition-all">
                        <img src="{{ Storage::url($foto->path_foto) }}" alt="Foto laporan"
                             class="w-full h-32 object-cover" />
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </x-card>

    <div id="modalFoto" class="fixed inset-0 hidden items-center justify-center bg-black/80 p-4 z-50"
         onclick="closeFoto()">
        <img id="modalFotoImg" src="" alt="Foto bukti" class="max-w-full max-h-full rounded-2xl shadow-2xl" />
    </div>

    <script>
        function openFoto(url) {
            const modal = document.getElementById('modalFoto');
            document.getElementById('modalFotoImg').src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeFoto() {
            const modal = document.getElementById('modalFoto');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('modalFotoImg').src = '';
        }
    </script>

</x-app-layout>
