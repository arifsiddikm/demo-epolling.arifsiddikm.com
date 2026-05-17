@extends('layouts.user')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang, ' . Auth::user()->name)

@section('main')
<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="card p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-primary-900/40 rounded-xl flex items-center justify-center">
            <i class="fas fa-poll text-primary-500 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Polling</p>
            <p class="text-3xl font-extrabold text-white">{{ $totalPolls }}</p>
        </div>
    </div>
    <div class="card p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-900/30 rounded-xl flex items-center justify-center">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Polling Aktif</p>
            <p class="text-3xl font-extrabold text-white">{{ $activePolls }}</p>
        </div>
    </div>
    <div class="card p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-900/30 rounded-xl flex items-center justify-center">
            <i class="fas fa-vote-yea text-blue-500 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Suara Masuk</p>
            <p class="text-3xl font-extrabold text-white">{{ $totalVotes }}</p>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-5 gap-6">
    <!-- Chart -->
    <div class="lg:col-span-3 card p-6">
        <h3 class="font-bold text-white mb-4 flex items-center gap-2">
            <i class="fas fa-chart-bar text-primary-500"></i> Suara per Polling (5 Terbaru)
        </h3>
        <canvas id="pollChart" height="200"></canvas>
    </div>

    <!-- Recent polls -->
    <div class="lg:col-span-2 card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white flex items-center gap-2"><i class="fas fa-list text-primary-500"></i> Polling Terbaru</h3>
            <a href="{{ route('user.polls.index') }}" class="text-xs text-primary-400 hover:text-primary-300">Lihat semua</a>
        </div>
        @forelse($recentPolls as $poll)
        <div class="flex items-center justify-between py-3 border-b border-white/5 last:border-0 group">
            <div class="flex-1 min-w-0 mr-3">
                <a href="{{ route('user.polls.show', $poll) }}" class="text-sm font-medium text-white hover:text-primary-400 transition-colors truncate block">{{ $poll->title }}</a>
                <span class="text-xs text-gray-500">{{ $poll->votes_count }} suara</span>
            </div>
            @if($poll->is_active)
                <span class="badge-active"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif</span>
            @else
                <span class="badge-inactive">Nonaktif</span>
            @endif
        </div>
        @empty
        <div class="text-center py-8">
            <i class="fas fa-poll text-4xl text-gray-700 mb-3 block"></i>
            <p class="text-gray-500 text-sm">Belum ada polling</p>
            <a href="{{ route('user.polls.create') }}" class="btn-primary btn-sm mt-3">Buat Polling Pertama</a>
        </div>
        @endforelse
    </div>
</div>

<!-- Quick actions -->
<div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
    <a href="{{ route('user.polls.create') }}" class="card p-4 text-center hover:border-primary-800/50 transition-colors group">
        <i class="fas fa-plus-circle text-primary-500 text-2xl mb-2 group-hover:scale-110 transition-transform block"></i>
        <p class="text-sm font-medium text-white">Buat Polling</p>
    </a>
    <a href="{{ route('user.polls.index') }}" class="card p-4 text-center hover:border-primary-800/50 transition-colors group">
        <i class="fas fa-list text-blue-400 text-2xl mb-2 group-hover:scale-110 transition-transform block"></i>
        <p class="text-sm font-medium text-white">Semua Polling</p>
    </a>
    <a href="{{ route('user.export.summary.pdf') }}" class="card p-4 text-center hover:border-primary-800/50 transition-colors group">
        <i class="fas fa-file-pdf text-red-400 text-2xl mb-2 group-hover:scale-110 transition-transform block"></i>
        <p class="text-sm font-medium text-white">Export PDF</p>
    </a>
    <a href="{{ route('user.profile') }}" class="card p-4 text-center hover:border-primary-800/50 transition-colors group">
        <i class="fas fa-user-edit text-purple-400 text-2xl mb-2 group-hover:scale-110 transition-transform block"></i>
        <p class="text-sm font-medium text-white">Edit Profil</p>
    </a>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('pollChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Jumlah Suara',
            data: {!! json_encode($chartData) !!},
            backgroundColor: 'rgba(220,38,38,0.7)',
            borderColor: '#dc2626',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#9ca3af' } } },
        scales: {
            y: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            x: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' } }
        }
    }
});
</script>
@endpush
