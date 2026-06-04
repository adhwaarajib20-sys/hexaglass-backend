<x-app-layout title="Buat Laporan">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Buat Laporan Ketidaksesuaian</h2>
            <p class="text-sm text-gray-500">Isi laporan dengan detail kejadian agar dapat ditindaklanjuti.</p>
        </div>
        <a href="{{ route('operator.laporan.index') }}" class="btn-outline">Kembali</a>
    </div>

    @if(session('warning'))
        <div class="mb-4 rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-700">
            {{ session('warning') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card>
        <form method="POST" action="{{ route('operator.laporan.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelapor</label>
                    <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor', auth()->user()->name) }}" readonly
                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Perusahaan</label>
                    <input type="text" name="perusahaan" value="{{ old('perusahaan') }}"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kejadian</label>
                    <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian') }}"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Kejadian</label>
                    <input type="time" name="waktu_kejadian" value="{{ old('waktu_kejadian') }}"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kejadian</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Klasifikasi</label>
                    <select name="klasifikasi"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                        @foreach(['keselamatan','lingkungan','kualitas','prosedur','lainnya'] as $option)
                            <option value="{{ $option }}" {{ old('klasifikasi') === $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kejadian</label>
                <textarea name="deskripsi" rows="5"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi</label>
                <textarea name="rekomendasi" rows="4"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('rekomendasi') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti</label>
                <input type="file" name="foto[]" multiple accept="image/*"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" />
                <p class="text-xs text-gray-500 mt-2">Unggah foto bukti untuk memperjelas laporan (opsional, maksimal 5MB per file).</p>
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-gray-500">Semua laporan akan dikirim dengan status <strong>terkirim</strong> dan ditinjau oleh admin.</p>
                <button type="submit" class="btn-primary">Kirim Laporan</button>
            </div>
        </form>
    </x-card>

</x-app-layout>
