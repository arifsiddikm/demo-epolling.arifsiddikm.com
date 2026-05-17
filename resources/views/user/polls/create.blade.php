@extends('layouts.user')
@section('title', 'Buat Polling Baru')
@section('page_title', 'Buat Polling Baru')
@section('page_subtitle', 'Isi detail polling Anda')

@section('main')
<form method="POST" action="{{ route('user.polls.store') }}" enctype="multipart/form-data" class="max-w-3xl">
    @csrf
    <div class="space-y-6">
        <!-- Basic Info -->
        <div class="card p-6">
            <h2 class="font-bold text-white mb-5 flex items-center gap-2 text-lg">
                <i class="fas fa-info-circle text-primary-500"></i> Informasi Polling
            </h2>
            <div class="space-y-5">
                <div>
                    <label class="form-label">Judul Polling <span class="text-primary-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-input" placeholder="Contoh: Pemilihan Ketua OSIS 2025" required>
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" class="form-textarea" placeholder="Deskripsi polling...">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Foto / Banner Polling</label>
                    <div class="flex items-center gap-4">
                        <label for="image" class="cursor-pointer flex items-center gap-3 rounded-xl px-5 py-4 transition-all duration-200 w-full form-file border-dashed">
                            <i class="fas fa-image text-primary-500 text-2xl"></i>
                            <div>
                                <p class="text-sm text-gray-300 font-medium">Klik untuk upload gambar</p>
                                <p class="text-xs text-gray-500">JPG, PNG, max 2MB</p>
                            </div>
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>
                    <img id="image-preview" src="" alt="Preview" class="mt-3 h-32 rounded-lg object-cover hidden">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="form-input">
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-700 peer-checked:bg-green-600 rounded-full transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-sm text-gray-300 font-medium">Aktifkan polling setelah dibuat</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Primary Key Settings -->
        <div class="card p-6">
            <h2 class="font-bold text-white mb-5 flex items-center gap-2 text-lg">
                <i class="fas fa-key text-primary-500"></i> Pengaturan Kunci Pemilih
            </h2>
            <p class="text-sm text-gray-400 mb-5">Tentukan jenis identitas unik yang harus dimasukkan pemilih sebelum memilih (NIM, NIK, ID, dsb.)</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Label Kunci <span class="text-primary-500">*</span></label>
                    <input type="text" name="primary_key_label" value="{{ old('primary_key_label', 'Nomor Induk (NIM/NIK)') }}" class="form-input" placeholder="Contoh: NIM Mahasiswa" required>
                    <p class="text-xs text-gray-500 mt-1">Nama field yang tampil ke pemilih</p>
                </div>
                <div>
                    <label class="form-label">Placeholder Input <span class="text-primary-500">*</span></label>
                    <input type="text" name="primary_key_placeholder" value="{{ old('primary_key_placeholder', 'Masukkan NIM Anda') }}" class="form-input" placeholder="Contoh: 2024XXXXXXXX" required>
                    <p class="text-xs text-gray-500 mt-1">Teks contoh di dalam input pemilih</p>
                </div>
            </div>
        </div>

        <!-- Poll Options -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-white flex items-center gap-2 text-lg">
                    <i class="fas fa-list-ul text-primary-500"></i> Pilihan / Kandidat
                    <span class="text-sm font-normal text-gray-500">(min. 2)</span>
                </h2>
                <button type="button" onclick="addOption()" class="btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Pilihan
                </button>
            </div>

            <div id="options-container" class="space-y-4">
                <!-- Initial 2 options -->
                <div class="option-item rounded-xl p-5 animate-fade-in-up" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-primary-400">Pilihan 1</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="form-label">Nama Pilihan <span class="text-primary-500">*</span></label>
                            <input type="text" name="options[0][name]" class="form-input" placeholder="Contoh: Kandidat A" required>
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="options[0][description]" class="form-input" placeholder="Deskripsi singkat">
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Foto Pilihan</label>
                            <input type="file" name="options[0][image]" accept="image/*" class="form-file">
                        </div>
                    </div>
                </div>
                <div class="option-item rounded-xl p-5 animate-fade-in-up" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-primary-400">Pilihan 2</span>
                        <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-400 text-sm transition-colors"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="form-label">Nama Pilihan <span class="text-primary-500">*</span></label>
                            <input type="text" name="options[1][name]" class="form-input" placeholder="Contoh: Kandidat B" required>
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="options[1][description]" class="form-input" placeholder="Deskripsi singkat">
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Foto Pilihan</label>
                            <input type="file" name="options[1][image]" accept="image/*" class="form-file">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary px-8 py-3 text-base">
                <i class="fas fa-save"></i> Buat Polling
            </button>
            <a href="{{ route('user.polls.index') }}" class="btn-secondary px-8 py-3 text-base">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </div>
</form>
@endsection


@push('styles')
<style>
/* CKEditor dark mode override */
.ck.ck-editor__main > .ck-editor__editable {
    background: rgba(255,255,255,.04) !important;
    color: #fff !important;
    border: 1px solid rgba(255,255,255,.10) !important;
    border-radius: 0 0 0.75rem 0.75rem !important;
    min-height: 120px;
}
.ck.ck-editor__main > .ck-editor__editable:focus {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(239,68,68,.15) !important;
}
.ck.ck-toolbar {
    background: rgba(255,255,255,.06) !important;
    border: 1px solid rgba(255,255,255,.10) !important;
    border-bottom: none !important;
    border-radius: 0.75rem 0.75rem 0 0 !important;
}
.ck.ck-toolbar .ck-button {
    color: #9ca3af !important;
}
.ck.ck-toolbar .ck-button:hover {
    background: rgba(255,255,255,.1) !important;
    color: #fff !important;
}
.ck.ck-toolbar .ck-button.ck-on {
    background: rgba(220,38,38,.2) !important;
    color: #ef4444 !important;
}
.ck.ck-toolbar__separator {
    background: rgba(255,255,255,.1) !important;
}
.ck.ck-editor__editable p,
.ck.ck-editor__editable li,
.ck.ck-editor__editable h2,
.ck.ck-editor__editable h3 {
    color: #fff !important;
}
.ck.ck-list {
    background: #1a1a2e !important;
    border: 1px solid rgba(255,255,255,.1) !important;
}
.ck.ck-list__item .ck-button {
    color: #e5e7eb !important;
}
.ck.ck-list__item .ck-button:hover {
    background: rgba(220,38,38,.2) !important;
}
.ck.ck-dropdown__panel {
    background: #1a1a2e !important;
    border: 1px solid rgba(255,255,255,.1) !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
ClassicEditor.create(document.querySelector('#description'), {
    toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','|','undo','redo'],
}).catch(console.error);

let optionCount = 2;

function addOption() {
    const container = document.getElementById('options-container');
    const idx = optionCount++;
    const div = document.createElement('div');
    div.className = 'option-item rounded-xl p-5 animate-fade-in-up'; div.style.cssText = 'background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);';
    div.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-semibold text-primary-400">Pilihan ${idx + 1}</span>
            <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-400 text-sm transition-colors"><i class="fas fa-times"></i></button>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="form-label">Nama Pilihan <span class="text-primary-500">*</span></label>
                <input type="text" name="options[${idx}][name]" class="form-input" placeholder="Nama pilihan" required>
            </div>
            <div class="col-span-2">
                <label class="form-label">Deskripsi</label>
                <input type="text" name="options[${idx}][description]" class="form-input" placeholder="Deskripsi singkat">
            </div>
            <div class="col-span-2">
                <label class="form-label">Foto Pilihan</label>
                <input type="file" name="options[${idx}][image]" accept="image/*" class="form-file">
            </div>
        </div>`;
    container.appendChild(div);
}

function removeOption(btn) {
    const items = document.querySelectorAll('.option-item');
    if (items.length <= 2) {
        Swal.fire({ icon: 'warning', title: 'Minimal 2 pilihan!', background: '#1f2937', color: '#fff', confirmButtonColor: '#dc2626' });
        return;
    }
    btn.closest('.option-item').remove();
}

function previewImage(input) {
    const preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
