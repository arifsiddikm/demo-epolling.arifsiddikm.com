@extends('layouts.app')

@section('title', 'e-Polling — Platform Polling Online Terpercaya')
@section('meta_description', 'Buat polling online gratis, bagikan link, kumpulkan suara secara aman dan transparan. Cocok untuk pemilihan OSIS, kandidat, survei, dan lainnya.')

@section('content')
<!-- Navbar -->
<nav class="fixed top-0 w-full z-50 bg-black/80 backdrop-blur-md border-b border-white/5">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                <svg viewBox="0 0 24 24" fill="white" class="w-4 h-4"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xl font-bold">e-<span class="text-primary-500">Polling</span></span>
        </a>
        <div class="flex items-center gap-3">
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-primary btn-sm">Panel Admin</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn-primary btn-sm">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary btn-sm">Daftar Gratis</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="min-h-screen flex items-center pt-20 relative overflow-hidden bg-black">
    <!-- Background effects -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-primary-800/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-px h-full bg-gradient-to-b from-transparent via-primary-900/30 to-transparent"></div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-20 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-primary-900/40 border border-primary-800/50 text-primary-400 text-sm font-medium px-4 py-2 rounded-full mb-8">
                <span class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
                Platform Polling Online #1 Indonesia
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight mb-6">
                Polling Digital<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-primary-600">Mudah & Aman</span>
            </h1>

            <p class="text-lg text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Buat polling online dalam hitungan menit. Bagikan link, kumpulkan suara, lihat hasil real-time. 
                Cocok untuk pemilihan OSIS, kandidat organisasi, survei kampus, dan banyak lagi.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-primary px-8 py-3.5 text-base">
                    <i class="fas fa-rocket"></i> Mulai Gratis Sekarang
                </a>
                <a href="#features" class="btn-secondary px-8 py-3.5 text-base">
                    <i class="fas fa-play-circle"></i> Pelajari Lebih Lanjut
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-16 grid grid-cols-3 gap-8 max-w-lg mx-auto">
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-primary-500">100%</div>
                    <div class="text-xs text-gray-500 mt-1">Gratis</div>
                </div>
                <div class="text-center border-x border-white/5">
                    <div class="text-3xl font-extrabold text-primary-500">∞</div>
                    <div class="text-xs text-gray-500 mt-1">Pilihan</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-primary-500">Real</div>
                    <div class="text-xs text-gray-500 mt-1">Time Result</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section id="features" class="py-24 bg-gray-950">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Fitur Unggulan</h2>
            <p class="text-gray-400 max-w-xl mx-auto">Semua yang Anda butuhkan untuk polling online yang profesional dan terpercaya</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @php
            $features = [
                ['icon' => 'fa-link', 'color' => 'text-blue-400', 'bg' => 'bg-blue-900/20', 'title' => 'Link Unik', 'desc' => 'Setiap polling punya link unik yang bisa dibagikan ke siapa saja.'],
                ['icon' => 'fa-shield-alt', 'color' => 'text-green-400', 'bg' => 'bg-green-900/20', 'title' => 'Anti Double Vote', 'desc' => 'Sistem kunci primer mencegah pemilih yang sama memilih lebih dari sekali.'],
                ['icon' => 'fa-chart-bar', 'color' => 'text-yellow-400', 'bg' => 'bg-yellow-900/20', 'title' => 'Hasil Real-Time', 'desc' => 'Lihat perkembangan suara secara langsung dengan grafik yang informatif.'],
                ['icon' => 'fa-id-card', 'color' => 'text-purple-400', 'bg' => 'bg-purple-900/20', 'title' => 'Kunci Fleksibel', 'desc' => 'Gunakan NIM, NIK, atau ID apapun sebagai kunci unik pemilih.'],
                ['icon' => 'fa-file-download', 'color' => 'text-red-400', 'bg' => 'bg-red-900/20', 'title' => 'Export PDF & Excel', 'desc' => 'Unduh rekap hasil voting dalam format PDF atau Excel kapan saja.'],
                ['icon' => 'fa-toggle-on', 'color' => 'text-orange-400', 'bg' => 'bg-orange-900/20', 'title' => 'Kontrol Penuh', 'desc' => 'Aktifkan atau nonaktifkan polling kapan saja sesuai kebutuhan Anda.'],
            ];
            @endphp

            @foreach($features as $f)
            <div class="card p-6 hover:border-primary-800/50 transition-colors group">
                <div class="{{ $f['bg'] }} w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas {{ $f['icon'] }} {{ $f['color'] }} text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">{{ $f['title'] }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-24 bg-black">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Cara Kerja</h2>
            <p class="text-gray-400">3 langkah mudah untuk memulai polling Anda</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach([['1','Buat Akun & Polling','Daftar gratis, lalu buat polling dengan nama, deskripsi, pilihan, dan kunci unik pemilih.','fa-edit'],['2','Bagikan Link','Salin link polling dan bagikan ke peserta melalui WhatsApp, email, atau media sosial.','fa-share-alt'],['3','Lihat Hasil','Pantau perkembangan suara real-time dan cetak rekap hasil voting Anda.','fa-chart-pie']] as $step)
            <div class="text-center">
                <div class="w-14 h-14 bg-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-5 text-xl font-extrabold text-white shadow-xl shadow-red-900/30">{{ $step[0] }}</div>
                <h3 class="text-lg font-bold text-white mb-2">{{ $step[1] }}</h3>
                <p class="text-gray-400 text-sm">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-24 bg-gradient-to-br from-primary-900/40 to-black border-y border-primary-900/30">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-white mb-4">Siap Mulai Polling?</h2>
        <p class="text-gray-400 mb-8 text-lg">Daftar gratis dan buat polling pertama Anda dalam 2 menit.</p>
        <a href="{{ route('register') }}" class="btn-primary px-10 py-4 text-lg">
            <i class="fas fa-rocket"></i> Daftar Sekarang — Gratis!
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-black border-t border-white/5 py-8">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 bg-primary-600 rounded-lg flex items-center justify-center">
                <svg viewBox="0 0 24 24" fill="white" class="w-3.5 h-3.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="font-bold text-white">e-<span class="text-primary-500">Polling</span></span>
        </div>
        <p class="text-gray-600 text-sm">© {{ date('Y') }} e-Polling. Platform polling online terpercaya.</p>
        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-gray-500 hover:text-white text-sm transition-colors">Login</a>
            <a href="{{ route('register') }}" class="text-gray-500 hover:text-white text-sm transition-colors">Daftar</a>
        </div>
    </div>
</footer>
@endsection
