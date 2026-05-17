@extends('layouts.app')
@section('title', $poll->title . ' — Polling Tidak Aktif')

@section('content')
<div class="min-h-screen bg-black flex flex-col items-center justify-center p-6">
    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-10">
        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
            <svg viewBox="0 0 24 24" fill="white" class="w-4 h-4"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <span class="text-xl font-bold">e-<span class="text-primary-500">Polling</span></span>
    </a>
    <div class="card p-10 text-center max-w-md w-full">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-lock text-3xl text-gray-400"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Polling Tidak Aktif</h2>
        <p class="text-gray-500 mb-2">Polling <strong class="text-white">{{ $poll->title }}</strong> saat ini sedang tidak aktif atau belum dibuka oleh penyelenggara.</p>
        <p class="text-gray-600 text-sm mb-6">Silakan hubungi penyelenggara untuk informasi lebih lanjut.</p>
        <a href="{{ route('home') }}" class="btn-primary w-full justify-center">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
