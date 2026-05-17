@extends('layouts.app')
@section('title', 'Login — e-Polling')

@section('content')
<div class="min-h-screen bg-black flex">
    <!-- Left decorative panel -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-primary-900/80 to-black items-center justify-center p-12">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/3 left-1/3 w-64 h-64 bg-primary-600/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10 text-center">
            <div class="w-20 h-20 bg-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-2xl">
                <svg viewBox="0 0 24 24" fill="white" class="w-10 h-10"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-4xl font-extrabold text-white mb-4">e-Polling</h2>
            <p class="text-gray-400 text-lg max-w-xs mx-auto">Platform polling online yang aman, transparan, dan mudah digunakan.</p>
        </div>
    </div>

    <!-- Right login form -->
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 lg:hidden mb-6">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="white" class="w-4 h-4"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xl font-bold">e-<span class="text-primary-500">Polling</span></span>
                </a>
                <h1 class="text-3xl font-extrabold text-white mb-2">Selamat Datang</h1>
                <p class="text-gray-500">Masuk ke akun e-Polling Anda</p>
            </div>

            @if($errors->any())
            <div class="alert-error mb-4">
                <i class="fas fa-exclamation-circle text-red-400 mt-0.5 flex-shrink-0"></i><div>@foreach($errors->all() as $error)<p class="text-red-300 text-sm">{{ $error }}</p>@endforeach</div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@contoh.com" required autofocus>
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="form-input pr-12" placeholder="••••••••" required>
                        <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition-colors">
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="form-check">
                        <span class="text-sm text-gray-400">Ingat saya</span>
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full justify-center py-3 text-base">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <!-- Admin autofill -->
            <div class="mt-4 p-3 bg-gray-900 border border-white/10 rounded-lg">
                <p class="text-xs text-gray-500 mb-2 text-center font-medium">⚡ Demo Login Cepat</p>
                <div class="flex gap-2">
                    <button onclick="autoFillAdmin()" class="flex-1 btn-xs" style="background:rgba(220,38,38,.15);color:#f87171;border:1px solid rgba(220,38,38,.3);">
                        <i class="fas fa-shield-alt mr-1"></i> Admin
                    </button>
                    <button onclick="autoFillUser()" class="flex-1 btn-xs" style="background:rgba(255,255,255,.05);color:#9ca3af;border:1px solid rgba(255,255,255,.1);">
                        <i class="fas fa-user mr-1"></i> User Demo
                    </button>
                </div>
            </div>

            <p class="text-center text-gray-500 text-sm mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-primary-400 hover:text-primary-300 font-semibold transition-colors">Daftar gratis</a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePass() {
    const p = document.getElementById('password');
    const i = document.getElementById('eye-icon');
    if (p.type === 'password') { p.type = 'text'; i.className = 'fas fa-eye-slash'; }
    else { p.type = 'password'; i.className = 'fas fa-eye'; }
}
function autoFillAdmin() {
    document.querySelector('input[name="email"]').value = 'admin@epolling.com';
    document.querySelector('input[name="password"]').value = 'admin123';
}
function autoFillUser() {
    document.querySelector('input[name="email"]').value = 'user@epolling.com';
    document.querySelector('input[name="password"]').value = 'user123';
}
</script>
@endpush
@endsection
