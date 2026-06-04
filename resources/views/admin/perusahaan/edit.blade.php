<x-app-layout title="Edit Perusahaan">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.perusahaan.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-600 text-sm">Edit Perusahaan</span>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <form action="{{ route('admin.perusahaan.update', $perusahaan->id) }}" method="POST">
                @csrf @method('PUT')
                @include('admin.perusahaan._form', ['perusahaan' => $perusahaan])
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn-primary px-6">Simpan Perubahan</button>
                    <a href="{{ route('admin.perusahaan.index') }}" class="btn-outline px-6">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>