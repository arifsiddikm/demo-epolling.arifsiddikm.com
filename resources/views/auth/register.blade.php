@extends('layouts.app')
@section('title', 'Daftar Akun — e-Polling')

@section('content')
<div class="min-h-screen bg-black flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                    <svg viewBox="0 0 24 24" fill="white" class="w-4 h-4"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xl font-bold">e-<span class="text-primary-500">Polling</span></span>
            </a>
            <h1 class="text-3xl font-extrabold text-white mb-2">Buat Akun Baru</h1>
            <p class="text-gray-500">Daftar gratis dan mulai buat polling Anda</p>
        </div>

        @if($errors->any())
        <div class="alert-error mb-4">
            <i class="fas fa-exclamation-circle text-red-400 mt-0.5 flex-shrink-0"></i><div>@foreach($errors->all() as $error)<p class="text-red-300 text-sm">{{ $error }}</p>@endforeach</div>
        </div>
        @endif

        <div class="card p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="form-label">Nama Lengkap <span class="text-primary-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Nama Anda" required>
                </div>
                <div>
                    <label class="form-label">Email <span class="text-primary-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@contoh.com" required>
                </div>
                <div>
                    <label class="form-label">No. Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="form-label">Password <span class="text-primary-500">*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password <span class="text-primary-500">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn-primary w-full justify-center py-3 text-base mt-2">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>
        </div>

        <p class="text-center text-gray-500 text-sm mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary-400 hover:text-primary-300 font-semibold transition-colors">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection
