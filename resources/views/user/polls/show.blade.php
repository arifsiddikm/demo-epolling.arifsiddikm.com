@extends('layouts.user')
@section('title', $poll->title . ' — Rekap')
@section('page_title', 'Detail Polling')
@section('page_subtitle', $poll->title)

@section('main')
<div class="max-w-4xl">
    <!-- Header card -->
    <div class="card overflow-hidden mb-6">
        @if($poll->image)
        <img src="{{ $poll->image_url }}" class="w-full h-48 object-cover">
        @endif
        <div class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <h2 class="text-2xl font-extrabold text-white mb-2">{{ $poll->title }}</h2>
                    @if($poll->description)
                    <div class="text-gray-400 text-sm prose-sm">
                        {!! nl2br(e($poll->description)) !!}
                    </div>
                    @endif
                </div>
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    @if($poll->is_active)
                        <span class="badge-active"><span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>Aktif</span>
                    @else
                        <span class="badge-inactive">Nonaktif</span>
                    @endif
                </div>
            </div>

            <!-- Link copy -->
            <div class="mt-4 flex items-center gap-2 bg-gray-800 rounded-lg px-4 py-3">
                <i class="fas fa-link text-primary-500 text-sm"></i>
                <input type="text" value="{{ $poll->url }}" class="flex-1 bg-transparent text-sm text-gray-300 outline-none" readonly>
                <button onclick="copyLink('{{ $poll->url }}')" class="btn-primary btn-sm">
                    <i class="fas fa-copy"></i> Salin Link
                </button>
            </div>

            <!-- Meta -->
            <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-500">
                <span><i class="fas fa-key mr-1 text-primary-600"></i>{{ $poll->primary_key_label }}</span>
                <span><i class="fas fa-vote-yea mr-1 text-blue-500"></i>{{ $poll->total_votes }} total suara</span>
                <span><i class="fas fa-calendar mr-1"></i>{{ $poll->created_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats + Chart -->
    <div class="grid md:grid-cols-3 gap-5 mb-6">
        @foreach($poll->options as $option)
        <div class="card p-5">
            @if($option->image)
            <img src="{{ $option->image_url }}" alt="{{ $option->name }}" class="w-full h-28 object-cover rounded-lg mb-3">
            @endif
            <h4 class="font-bold text-white mb-1">{{ $option->name }}</h4>
            @if($option->description)<p class="text-xs text-gray-500 mb-3">{{ $option->description }}</p>@endif
            <div class="flex items-center justify-between text-sm mb-2">
                <span class="text-gray-400">{{ $option->vote_count }} suara</span>
                <span class="font-bold text-primary-400">{{ $option->percentage }}%</span>
            </div>
            <div class="w-full bg-gray-800 rounded-full h-2">
                <div class="bg-primary-600 h-2 rounded-full transition-all" style="width: {{ $option->percentage }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Chart -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-white mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-primary-500"></i> Grafik Hasil Polling
        </h3>
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="w-64 h-64 flex-shrink-0">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="flex-1 space-y-2">
                @foreach($poll->options as $option)
                <div class="flex items-center justify-between py-2 border-b border-white/5">
                    <span class="text-sm text-gray-300">{{ $option->name }}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-white">{{ $option->vote_count }}</span>
                        <span class="text-xs text-gray-500 w-10 text-right">{{ $option->percentage }}%</span>
                    </div>
                </div>
                @endforeach
                <div class="flex items-center justify-between pt-3 font-bold">
                    <span class="text-gray-300">Total</span>
                    <span class="text-white">{{ $poll->total_votes }} suara</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('user.polls.export.pdf', $poll) }}" class="btn-primary">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('user.polls.export.excel', $poll) }}" class="btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('user.polls.edit', $poll) }}" class="btn-secondary">
            <i class="fas fa-edit"></i> Edit Polling
        </a>
        <a href="{{ route('poll.show', $poll->slug) }}" target="_blank" class="btn-secondary">
            <i class="fas fa-external-link-alt"></i> Lihat Halaman Publik
        </a>
        <a href="{{ route('user.polls.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({ icon: 'success', title: 'Link disalin!', background: '#1f2937', color: '#fff', confirmButtonColor: '#dc2626', timer: 1500 });
    });
}

const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($poll->options->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($poll->options->map(fn($o) => $o->vote_count)) !!},
            backgroundColor: ['#dc2626','#ef4444','#f87171','#fca5a5','#fecaca','#fee2e2'],
            borderColor: '#111827', borderWidth: 3
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
