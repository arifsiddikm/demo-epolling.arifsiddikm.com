@extends('layouts.admin')
@section('title', 'Akun Admin')
@section('page_title', 'Akun Admin')
@section('page_subtitle', 'Kelola data akun administrator')

@section('main')
<div class="max-w-2xl space-y-6">
    <!-- Profile info card -->
    <div class="card p-6">
        <h3 class="font-bold text-white mb-5 flex items-center gap-2 text-lg">
            <i class="fas fa-user-shield text-primary-500"></i> Informasi Akun
        </h3>
        <form method="POST" action="{{ route('admin.account.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div class="flex items-center gap-5 mb-2">
                <img src="{{ $user->avatar_url }}" id="avatar-preview" class="w-16 h-16 rounded-full object-cover ring-4 ring-primary-700">
                <div>
                    <label for="avatar" class="btn-secondary btn-sm cursor-pointer">
                        <i class="fas fa-camera"></i> Ganti Foto
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                    <p class="text-xs text-gray-600 mt-1">JPG, PNG, max 2MB</p>
                </div>
            </div>
            <div>
                <label class="form-label">Nama <span class="text-primary-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Email <span class="text-primary-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- Change password -->
    <div class="card p-6">
        <h3 class="font-bold text-white mb-5 flex items-center gap-2 text-lg">
            <i class="fas fa-lock text-primary-500"></i> Ubah Password
        </h3>
        <form method="POST" action="{{ route('admin.account.password') }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="form-label">Password Saat Ini <span class="text-primary-500">*</span></label>
                <input type="password" name="current_password" class="form-input" required>
                @error('current_password')<p class="form-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Password Baru <span class="text-primary-500">*</span></label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Konfirmasi Password Baru <span class="text-primary-500">*</span></label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-key"></i> Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
