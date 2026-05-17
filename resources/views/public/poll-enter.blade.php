@extends('layouts.app')
@section('title', $poll->title . ' — e-Polling')
@section('meta_description', $poll->description ?? 'Ikuti polling: ' . $poll->title)

@section('content')
<div class="min-h-screen bg-black flex flex-col">
    <!-- Top nav -->
    <nav class="bg-black/80 backdrop-blur border-b border-white/5 px-6 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2 w-fit">
            <div class="w-7 h-7 bg-primary-600 rounded-lg flex items-center justify-center">
                <svg viewBox="0 0 24 24" fill="white" class="w-3.5 h-3.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="font-bold text-white">e-<span class="text-primary-500">Polling</span></span>
        </a>
    </nav>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <!-- Poll card -->
            <div class="card overflow-hidden mb-6">
                @if($poll->image)
                <img src="{{ $poll->image_url }}" alt="{{ $poll->title }}" class="w-full h-44 object-cover">
                @else
                <div class="w-full h-32 bg-gradient-to-br from-primary-900/50 to-gray-900 flex items-center justify-center">
                    <i class="fas fa-poll text-5xl text-primary-700"></i>
                </div>
                @endif
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="badge-active"><span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Polling Aktif</span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white mb-2">{{ $poll->title }}</h1>
                    @if($poll->description)
                    <p class="text-gray-400 text-sm leading-relaxed">{{ strip_tags($poll->description) }}</p>
                    @endif
                    <div class="mt-4 pt-4 border-t border-white/5 flex items-center gap-4 text-sm text-gray-500">
                        <span><i class="fas fa-list mr-1"></i>{{ $poll->options->count() }} pilihan</span>
                    </div>
                </div>
            </div>

            <!-- Already voted alert -->
            @if(session('already_voted'))
            <div class="alert-warning mb-4">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5"></i>
                <div>
                    <p class="text-yellow-400 font-semibold text-sm">Sudah Pernah Memilih!</p>
                    <p class="text-yellow-500 text-xs mt-1">{{ $poll->primary_key_label }} <strong>{{ session('voter_key') }}</strong> sudah pernah memberikan suara pada polling ini.</p>
                    <a href="{{ route('poll.result', $poll->slug) }}" class="text-yellow-400 underline text-xs mt-1 inline-block hover:text-yellow-300">Lihat hasil polling →</a>
                </div>
            </div>
            @endif

            <!-- Key entry form -->
            <div class="card p-6">
                <h2 class="font-bold text-white mb-1 text-lg">Masukkan Identitas Anda</h2>
                <p class="text-gray-500 text-sm mb-5">Masukkan <span class="text-primary-400 font-medium">{{ $poll->primary_key_label }}</span> untuk melanjutkan ke halaman pemilihan.</p>

                <form method="POST" action="{{ route('poll.check', $poll->slug) }}">
                    @csrf
                    @if($errors->any())
                    <div class="alert-error mb-4">
                        @foreach($errors->all() as $e)
                        <p class="text-red-300 text-sm">{{ $e }}</p>
                        @endforeach
                    </div>
                    @endif
                    <div class="mb-5">
                        <label class="form-label">{{ $poll->primary_key_label }} <span class="text-primary-500">*</span></label>
                        <input type="text" name="voter_key" value="{{ old('voter_key') }}" class="form-input text-center text-lg tracking-widest font-mono" placeholder="{{ $poll->primary_key_placeholder }}" required autofocus>
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center py-3 text-base">
                        <i class="fas fa-arrow-right"></i> Lanjutkan ke Pemilihan
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-white/5 text-center">
                    <a href="{{ route('poll.result', $poll->slug) }}" class="text-sm text-gray-500 hover:text-gray-400 transition-colors">
                        <i class="fas fa-chart-bar mr-1"></i> Lihat hasil sementara
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
