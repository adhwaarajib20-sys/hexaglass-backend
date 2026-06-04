<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">
            Nama Perusahaan <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nama_perusahaan"
            value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan ?? '') }}" required
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Nama perusahaan">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Volume (Liter)</label>
            <input type="number" name="volume" step="0.01"
                value="{{ old('volume', $perusahaan->volume ?? '') }}"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="Total volume">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Rencana Pengisian Harian (L)</label>
            <input type="number" name="rencana_pengisian_harian" step="0.01"
                value="{{ old('rencana_pengisian_harian', $perusahaan->rencana_pengisian_harian ?? '') }}"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="Target harian">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
        <textarea name="keterangan" rows="3"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Keterangan tambahan...">{{ old('keterangan', $perusahaan->keterangan ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Prioritas</label>
            <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-orange-50 transition-colors">
                <input type="checkbox" name="is_prioritas" value="1"
                    {{ old('is_prioritas', $perusahaan->is_prioritas ?? false) ? 'checked' : '' }}
                    class="w-4 h-4 text-orange-500 rounded">
                <span class="text-sm text-gray-700">⚡ Tandai sebagai Prioritas</span>
            </label>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
            <select name="status"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="aktif"    {{ old('status', $perusahaan->status ?? 'aktif') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status', $perusahaan->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    </div>
</div>