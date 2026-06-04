<x-app-layout title="Antrean Aktif">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Antrean Aktif</h2>
            <p class="text-sm text-gray-500">{{ now()->format('d F Y') }} — {{ $antrean->count() }} antrean</p>
        </div>
        <button onclick="location.reload()"
            class="btn-outline flex items-center gap-2">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>

    @if($antrean->isEmpty())
    <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
        <p class="text-5xl mb-4"><i class="fas fa-check-circle text-green-500"></i></p>
        <p class="text-lg font-medium text-gray-600">Tidak ada antrean aktif</p>
        <p class="text-sm text-gray-400 mt-1">Semua antrean sudah selesai dilayani</p>
    </div>
    @else
    <div class="grid gap-4">
        @foreach($antrean as $item)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden
            {{ $item->is_prioritas ? 'border-l-4 border-l-orange-400' : '' }}">
            <div class="p-5 flex items-start gap-4">

                {{-- Nomor --}}
                <div class="w-16 h-16 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $item->is_prioritas ? 'bg-orange-100' : 'bg-primary-light' }}">
                    <div class="text-center">
                        @if($item->is_prioritas)
                            <i class="fas fa-bolt text-xl text-orange-500"></i>
                        @else
                            <i class="fas fa-truck text-xl text-primary"></i>
                        @endif
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-mono text-sm font-bold text-primary">{{ $item->nomor_antrean }}</span>
                        @if($item->is_prioritas)
                            <span class="badge bg-orange-100 text-orange-600">⚡ Prioritas</span>
                        @endif
                        @switch($item->status)
                            @case('menunggu')  <span class="badge bg-yellow-100 text-yellow-700">● Menunggu</span> @break
                            @case('dipanggil') <span class="badge bg-blue-100 text-blue-700">● Dipanggil</span> @break
                            @case('dilayani')  <span class="badge bg-purple-100 text-purple-700">● Dilayani</span> @break
                        @endswitch
                    </div>

                    <p class="font-semibold text-gray-800">{{ $item->kendaraan?->nama_supir }}</p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-car"></i> {{ $item->kendaraan?->nomor_polisi }} •
                        {{ $item->kendaraan?->jenis_kendaraan }} •
                        {{ $item->kendaraan?->kapasitas_tangki }}
                    </p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-building"></i> {{ $item->kendaraan?->perusahaan ?? '-' }} •
                        <i class="fas fa-phone"></i> {{ $item->kendaraan?->no_hp_supir }}
                    </p>

                    @if($item->estimasi_menit)
                    <p class="text-xs text-primary mt-1"><i class="fas fa-stopwatch"></i> Estimasi: {{ $item->estimasi_menit }} menit</p>
                    @endif
                    @if($item->alasan_prioritas)
                    <p class="text-xs text-orange-600 mt-1"><i class="fas fa-bolt"></i> {{ $item->alasan_prioritas }}</p>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2 flex-shrink-0">
                    <a href="{{ route('operator.antrean.show', $item->id) }}"
                       class="btn-primary text-center text-xs">Detail & Proses</a>

                    @if($item->status === 'menunggu')
                    <form action="{{ route('operator.antrean.panggil', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-500 text-white px-3 py-2 rounded-lg text-xs hover:bg-blue-600 transition-colors flex items-center justify-center gap-1">
                            <i class="fas fa-bullhorn"></i> Panggil
                        </button>
                    </form>
                    @endif

                    @if($item->status === 'dipanggil')
                    <form action="{{ route('operator.antrean.status', $item->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="dilayani">
                        <button type="submit" class="w-full bg-purple-500 text-white px-3 py-2 rounded-lg text-xs hover:bg-purple-600 transition-colors flex items-center justify-center gap-1">
                            <i class="fas fa-cog"></i> Mulai Layani
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</x-app-layout>