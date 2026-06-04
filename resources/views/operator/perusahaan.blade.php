<x-app-layout title="Informasi Perusahaan">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Informasi Perusahaan</h2>
        <p class="text-sm text-gray-500">
            Daftar perusahaan dan status prioritas — dikelola oleh Admin
        </p>
    </div>

    {{-- Banner Prioritas --}}
    @php $prioritas = $perusahaan->where('is_prioritas', true); @endphp
    @if($prioritas->count() > 0)
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 mb-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                <span class="text-xl">⚡</span>
            </div>
            <div>
                <h3 class="font-bold text-orange-800">Perusahaan Prioritas</h3>
                <p class="text-xs text-orange-600">
                    {{ $prioritas->count() }} perusahaan perlu didahulukan dalam antrean
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($prioritas as $item)
            <div class="bg-white rounded-xl p-4 border border-orange-200">
                <div class="flex items-start justify-between mb-2">
                    <p class="font-bold text-gray-800">{{ $item->nama_perusahaan }}</p>
                    <span class="badge bg-orange-100 text-orange-700 ml-2 flex-shrink-0">⚡ Prioritas</span>
                </div>
                @if($item->volume)
                <p class="text-xs text-gray-500">
                    📦 Volume: <strong>{{ number_format($item->volume, 0, ',', '.') }} L</strong>
                </p>
                @endif
                @if($item->rencana_pengisian_harian)
                <p class="text-xs text-gray-500">
                    📅 Target Harian: <strong>{{ number_format($item->rencana_pengisian_harian, 0, ',', '.') }} L</strong>
                </p>
                @endif
                @if($item->keterangan)
                <p class="text-xs text-orange-600 mt-2 bg-orange-50 rounded-lg p-2">
                    💬 {{ $item->keterangan }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Semua Perusahaan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Semua Perusahaan Terdaftar</h3>
            <span class="badge bg-gray-100 text-gray-600">{{ $perusahaan->count() }} perusahaan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Nama Perusahaan</th>
                        <th class="px-6 py-3 text-left">Prioritas</th>
                        <th class="px-6 py-3 text-left">Volume (L)</th>
                        <th class="px-6 py-3 text-left">Target Harian (L)</th>
                        <th class="px-6 py-3 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($perusahaan as $item)
                    <tr class="hover:bg-gray-50 transition-colors
                        {{ $item->is_prioritas ? 'bg-orange-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($item->is_prioritas)
                                <div class="w-2 h-2 bg-orange-500 rounded-full flex-shrink-0"></div>
                                @else
                                <div class="w-2 h-2 bg-gray-300 rounded-full flex-shrink-0"></div>
                                @endif
                                <p class="font-medium text-gray-800">{{ $item->nama_perusahaan }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_prioritas)
                                <span class="badge bg-orange-100 text-orange-700">⚡ Prioritas</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-500">Normal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $item->volume ? number_format($item->volume, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $item->rencana_pengisian_harian
                                ? number_format($item->rencana_pengisian_harian, 0, ',', '.')
                                : '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs max-w-64">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-3xl mb-2"><i class="fas fa-building"></i></p>
                            <p>Belum ada data perusahaan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3">
        <span class="text-blue-500 text-lg flex-shrink-0">ℹ️</span>
        <div>
            <p class="text-sm font-medium text-blue-800">Panduan Prioritas Antrean</p>
            <p class="text-xs text-blue-600 mt-1">
                Perusahaan dengan tanda ⚡ <strong>Prioritas</strong> harus didahulukan dalam antrean pengisian gas.
                Saat ada kendaraan dari perusahaan prioritas, gunakan fitur <strong>"Tandai sebagai Prioritas"</strong>
                di halaman detail antrean dan isi alasan prioritasnya.
            </p>
        </div>
    </div>

</x-app-layout>