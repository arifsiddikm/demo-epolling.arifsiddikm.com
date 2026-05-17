@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Overview sistem e-Polling')

@section('main')
<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    @foreach([
        ['icon'=>'fa-users','color'=>'text-blue-400','bg'=>'bg-blue-900/20','label'=>'Total User','value'=>$totalUsers],
        ['icon'=>'fa-poll-h','color'=>'text-purple-400','bg'=>'bg-purple-900/20','label'=>'Total Polling','value'=>$totalPolls],
        ['icon'=>'fa-check-circle','color'=>'text-green-400','bg'=>'bg-green-900/20','label'=>'Polling Aktif','value'=>$activePolls],
        ['icon'=>'fa-vote-yea','color'=>'text-primary-400','bg'=>'bg-primary-900/20','label'=>'Total Suara','value'=>$totalVotes],
    ] as $stat)
    <div class="card p-5 flex items-center gap-4">
        <div class="w-11 h-11 {{ $stat['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas {{ $stat['icon'] }} {{ $stat['color'] }} text-lg"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
            <p class="text-2xl font-extrabold text-white">{{ $stat['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<!-- Chart + Recent Polls -->
<div class="grid lg:grid-cols-5 gap-6 mb-6">
    <div class="lg:col-span-3 card p-6">
        <h3 class="font-bold text-white mb-5 flex items-center gap-2">
            <i class="fas fa-chart-line text-primary-500"></i> Pertumbuhan 6 Bulan Terakhir
        </h3>
        <canvas id="growthChart" height="220"></canvas>
    </div>
    <div class="lg:col-span-2 card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white flex items-center gap-2">
                <i class="fas fa-poll text-primary-500"></i> Polling Terbaru
            </h3>
            <a href="{{ route('admin.polls.index') }}" class="text-xs text-primary-400">Semua</a>
        </div>
        @foreach($recentPolls as $poll)
        <div class="flex items-center justify-between py-2.5 border-b border-white/5 last:border-0 gap-3">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ $poll->title }}</p>
                <p class="text-xs text-gray-500">{{ $poll->user->name ?? '-' }} · {{ $poll->votes_count }} suara</p>
            </div>
            @if($poll->is_active)
                <span class="badge-active shrink-0">Aktif</span>
            @else
                <span class="badge-inactive shrink-0">Off</span>
            @endif
        </div>
        @endforeach
    </div>
</div>

<!-- Quick exports -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <a href="{{ route('admin.export.polls.pdf') }}" class="card p-4 text-center hover:border-red-800/50 transition-colors group">
        <i class="fas fa-file-pdf text-red-400 text-2xl mb-2 block group-hover:scale-110 transition-transform"></i>
        <p class="text-xs font-medium text-white">Polling PDF</p>
    </a>
    <a href="{{ route('admin.export.polls.excel') }}" class="card p-4 text-center hover:border-green-800/50 transition-colors group">
        <i class="fas fa-file-excel text-green-400 text-2xl mb-2 block group-hover:scale-110 transition-transform"></i>
        <p class="text-xs font-medium text-white">Polling Excel</p>
    </a>
    <a href="{{ route('admin.export.users.pdf') }}" class="card p-4 text-center hover:border-red-800/50 transition-colors group">
        <i class="fas fa-file-pdf text-red-400 text-2xl mb-2 block group-hover:scale-110 transition-transform"></i>
        <p class="text-xs font-medium text-white">User PDF</p>
    </a>
    <a href="{{ route('admin.export.users.excel') }}" class="card p-4 text-center hover:border-green-800/50 transition-colors group">
        <i class="fas fa-file-excel text-green-400 text-2xl mb-2 block group-hover:scale-110 transition-transform"></i>
        <p class="text-xs font-medium text-white">User Excel</p>
    </a>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('growthChart').getContext('2d');
const labels = {!! json_encode($months->pluck('label')) !!};
new Chart(ctx, {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'User Baru',
                data: {!! json_encode($months->pluck('users')) !!},
                borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)',
                tension: 0.4, fill: true, pointBackgroundColor: '#3b82f6'
            },
            {
                label: 'Polling Baru',
                data: {!! json_encode($months->pluck('polls')) !!},
                borderColor: '#dc2626', backgroundColor: 'rgba(220,38,38,0.1)',
                tension: 0.4, fill: true, pointBackgroundColor: '#dc2626'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#9ca3af' } } },
        scales: {
            y: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true },
            x: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' } }
        }
    }
});
</script>
@endpush
