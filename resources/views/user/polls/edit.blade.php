@extends('layouts.user')
@section('title', 'Edit Polling')
@section('page_title', 'Edit Polling')
@section('page_subtitle', $poll->title)

@section('main')
<form method="POST" action="{{ route('user.polls.update', $poll) }}" enctype="multipart/form-data" class="max-w-3xl">
    @csrf @method('PUT')
    <div class="space-y-6">
        <div class="card p-6">
            <h2 class="font-bold text-white mb-5 flex items-center gap-2 text-lg">
                <i class="fas fa-edit text-primary-500"></i> Edit Informasi Polling
            </h2>
            <div class="space-y-5">
                <div>
                    <label class="form-label">Judul Polling <span class="text-primary-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $poll->title) }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" class="form-textarea">{{ old('description', $poll->description) }}</textarea>
                </div>
                @if($poll->image)
                <div>
                    <p class="form-label">Gambar Saat Ini</p>
                    <img src="{{ $poll->image_url }}" class="h-28 rounded-lg object-cover mb-2">
                </div>
                @endif
                <div>
                    <label class="form-label">Ganti Gambar (opsional)</label>
                    <input type="file" name="image" accept="image/*" class="form-file">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Kunci Label <span class="text-primary-500">*</span></label>
                        <input type="text" name="primary_key_label" value="{{ old('primary_key_label', $poll->primary_key_label) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Placeholder <span class="text-primary-500">*</span></label>
                        <input type="text" name="primary_key_placeholder" value="{{ old('primary_key_placeholder', $poll->primary_key_placeholder) }}" class="form-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="datetime-local" name="start_date" value="{{ old('start_date', $poll->start_date?->format('Y-m-d\TH:i')) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="datetime-local" name="end_date" value="{{ old('end_date', $poll->end_date?->format('Y-m-d\TH:i')) }}" class="form-input">
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $poll->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-700 peer-checked:bg-green-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm text-gray-300 font-medium">Polling Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary px-8 py-3 text-base">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('user.polls.show', $poll) }}" class="btn-secondary px-8 py-3 text-base">
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
</script>
@endpush
