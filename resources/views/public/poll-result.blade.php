@extends('layouts.app')
@section('title', 'Hasil — ' . $poll->title)

@section('content')
<div class="min-h-screen bg-black flex flex-col">
    <nav class="bg-black/80 backdrop-blur border-b border-white/5 px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-primary-600 rounded-lg flex items-center justify-center">
                <svg viewBox="0 0 24 24" fill="white" class="w-3.5 h-3.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="font-bold text-white">e-<span class="text-primary-500">Polling</span></span>
        </a>
        <a href="{{ route('poll.show', $poll->slug) }}" class="btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Polling
        </a>
    </nav>

    <div class="flex-1 p-6 max-w-3xl mx-auto w-full">
        <!-- Header -->
        <div class="text-center mb-8 mt-4">
            @if($poll->image)
            <img src="{{ $poll->image_url }}" alt="{{ $poll->title }}" class="w-full h-48 object-cover rounded-2xl mb-6">
            @endif
            <h1 class="text-3xl font-extrabold text-white mb-2">Hasil Polling</h1>
            <h2 class="text-xl text-primary-400 font-semibold">{{ $poll->title }}</h2>
            <p class="text-gray-500 mt-2 text-sm">Total {{ $poll->total_votes }} suara masuk</p>
        </div>

        <!-- Results -->
        @php
            $winner = $poll->options->sortByDesc(fn($o) => $o->vote_count)->first();
        @endphp

        @if($poll->total_votes > 0)
        <!-- Winner banner -->
        <div class="card border-primary-800/50 bg-gradient-to-r from-primary-900/30 to-transparent p-6 mb-6 flex items-center gap-5">
            @if($winner->image)
            <img src="{{ $winner->image_url }}" class="w-20 h-20 rounded-xl object-cover ring-4 ring-primary-600">
            @else
            <div class="w-20 h-20 bg-primary-700 rounded-xl flex items-center justify-center ring-4 ring-primary-600">
                <i class="fas fa-trophy text-3xl text-yellow-400"></i>
            </div>
            @endif
            <div>
                <p class="text-xs font-bold text-primary-400 uppercase tracking-wider mb-1">
                    <i class="fas fa-crown mr-1 text-yellow-400"></i> Unggul Sementara
                </p>
                <h3 class="text-2xl font-extrabold text-white">{{ $winner->name }}</h3>
                <p class="text-gray-400 text-sm mt-1">{{ $winner->vote_count }} suara ({{ $winner->percentage }}%)</p>
            </div>
        </div>
        @endif

        <!-- All results -->
        <div class="space-y-4 mb-8">
            @foreach($poll->options->sortByDesc(fn($o) => $o->vote_count) as $i => $option)
            <div class="card p-5">
                <div class="flex items-center gap-4">
                    @if($option->image)
                    <img src="{{ $option->image_url }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                    @else
                    <div class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0 text-xl font-bold text-gray-600">#{{ $i+1 }}</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-bold text-white">{{ $option->name }}</h4>
                            <div class="text-right flex-shrink-0 ml-3">
                                <span class="text-2xl font-extrabold {{ $i === 0 ? 'text-primary-400' : 'text-white' }}">{{ $option->percentage }}%</span>
                                <p class="text-xs text-gray-500">{{ $option->vote_count }} suara</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-800 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all duration-1000 {{ $i === 0 ? 'bg-primary-600' : 'bg-gray-600' }}" 
                                 style="width: 0%" data-width="{{ $option->percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Doughnut chart -->
        @if($poll->total_votes > 0)
        <div class="card p-6 text-center mb-6">
            <h3 class="font-bold text-white mb-4">Distribusi Suara</h3>
            <div class="w-56 h-56 mx-auto">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
        @else
        <div class="card p-10 text-center mb-6">
            <i class="fas fa-hourglass-half text-4xl text-gray-700 mb-3 block"></i>
            <p class="text-gray-500">Belum ada suara masuk. Jadilah yang pertama memilih!</p>
            <a href="{{ route('poll.show', $poll->slug) }}" class="btn-primary mt-4">Ikut Memilih</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Animate bars
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('[data-width]').forEach(el => {
            el.style.width = el.dataset.width;
        });
    }, 200);
});

@if($poll->total_votes > 0)
const ctx = document.getElementById('pieChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($poll->options->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($poll->options->map(fn($o) => $o->vote_count)) !!},
            backgroundColor: ['#dc2626','#ef4444','#f87171','#fca5a5','#fecaca'],
            borderColor: '#111827', borderWidth: 3
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#9ca3af', font: { size: 12 } } } }
    }
});
@endif
</script>
@endpush
