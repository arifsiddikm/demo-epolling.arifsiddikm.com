@extends('layouts.user')
@section('title', 'Rekap — ' . $poll->title)
@section('page_title', 'Rekap Polling')
@section('page_subtitle', $poll->title)

@section('main')
<div class="max-w-4xl">

    {{-- Header card --}}
    <div class="card overflow-hidden mb-6">
        @if($poll->image)
        <img src="{{ $poll->image_url }}" class="w-full h-48 object-cover">
        @endif
        <div class="p-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-extrabold text-white mb-2">{{ $poll->title }}</h2>
                    @if($poll->description)
                    <div class="text-gray-400 text-sm leading-relaxed">
                        {!! nl2br(e(strip_tags($poll->description))) !!}
                    </div>
                    @endif
                </div>
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    @if($poll->is_active)
                        <span class="badge-active"><span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Aktif</span>
                    @else
                        <span class="badge-inactive">Nonaktif</span>
                    @endif
                </div>
            </div>

            {{-- Poll link --}}
            <div class="mt-4 flex items-center gap-2 rounded-xl px-4 py-3" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);">
                <i class="fas fa-link text-primary-500 text-sm flex-shrink-0"></i>
                <input type="text" value="{{ $poll->url }}" class="flex-1 bg-transparent text-sm text-gray-300 outline-none" readonly>
                <button onclick="copyLink('{{ $poll->url }}')" class="btn-primary btn-sm flex-shrink-0">
                    <i class="fas fa-copy"></i> Salin
                </button>
            </div>

            {{-- Meta info --}}
            <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-500">
                <span><i class="fas fa-key mr-1 text-primary-600"></i>{{ $poll->primary_key_label }}</span>
                <span><i class="fas fa-vote-yea mr-1 text-blue-500"></i>{{ $poll->total_votes }} total suara</span>
                <span><i class="fas fa-list mr-1 text-purple-500"></i>{{ $poll->options->count() }} pilihan</span>
                <span><i class="fas fa-calendar mr-1"></i>{{ $poll->created_at->format('d M Y') }}</span>
                @if($poll->start_date)
                <span><i class="fas fa-play mr-1 text-green-500"></i>{{ $poll->start_date->format('d M Y H:i') }}</span>
                @endif
                @if($poll->end_date)
                <span><i class="fas fa-stop mr-1 text-red-500"></i>{{ $poll->end_date->format('d M Y H:i') }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Winner banner --}}
    @if($poll->total_votes > 0)
    @php $winner = $poll->options->sortByDesc(fn($o) => $o->vote_count)->first(); @endphp
    <div class="card mb-6 p-6 flex items-center gap-5" style="background:linear-gradient(135deg,rgba(220,38,38,.12),rgba(13,17,23,.9));border-color:rgba(220,38,38,.2);">
        @if($winner->image)
        <img src="{{ $winner->image_url }}" class="w-16 h-16 rounded-xl object-cover ring-4 ring-primary-600 flex-shrink-0">
        @else
        <div class="w-16 h-16 bg-primary-700 rounded-xl flex items-center justify-center ring-4 ring-primary-600 flex-shrink-0">
            <i class="fas fa-trophy text-2xl text-yellow-400"></i>
        </div>
        @endif
        <div>
            <p class="text-xs font-bold text-primary-400 uppercase tracking-wider mb-1">
                <i class="fas fa-crown mr-1 text-yellow-400"></i> Unggul Sementara
            </p>
            <h3 class="text-xl font-extrabold text-white">{{ $winner->name }}</h3>
            <p class="text-gray-400 text-sm mt-0.5">{{ $winner->vote_count }} suara &mdash; {{ $winner->percentage }}%</p>
        </div>
    </div>
    @endif

    {{-- Options stats --}}
    <div class="grid md:grid-cols-{{ $poll->options->count() >= 3 ? '3' : $poll->options->count() }} gap-5 mb-6">
        @foreach($poll->options->sortByDesc(fn($o) => $o->vote_count) as $i => $option)
        <div class="card p-5">
            @if($option->image)
            <img src="{{ $option->image_url }}" alt="{{ $option->name }}" class="w-full h-28 object-cover rounded-xl mb-3">
            @else
            <div class="w-full h-20 rounded-xl mb-3 flex items-center justify-center" style="background:rgba(255,255,255,.04);">
                <i class="fas fa-user text-3xl text-gray-700"></i>
            </div>
            @endif
            <div class="flex items-start justify-between gap-2 mb-1">
                <h4 class="font-bold text-white text-sm">{{ $option->name }}</h4>
                @if($i === 0 && $poll->total_votes > 0)
                <i class="fas fa-crown text-yellow-400 text-xs mt-0.5 flex-shrink-0"></i>
                @endif
            </div>
            @if($option->description)
            <p class="text-xs text-gray-500 mb-3">{{ $option->description }}</p>
            @endif
            <div class="flex items-center justify-between text-sm mb-2">
                <span class="text-gray-400">{{ $option->vote_count }} suara</span>
                <span class="font-bold {{ $i === 0 ? 'text-primary-400' : 'text-white' }}">{{ $option->percentage }}%</span>
            </div>
            <div class="w-full rounded-full h-2" style="background:rgba(255,255,255,.08);">
                <div class="h-2 rounded-full transition-all duration-1000 {{ $i === 0 ? 'bg-primary-600' : 'bg-gray-600' }}"
                     style="width: 0%" data-width="{{ $option->percentage }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Chart --}}
    @if($poll->total_votes > 0)
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-white mb-5 flex items-center gap-2">
            <i class="fas fa-chart-pie text-primary-500"></i> Grafik Distribusi Suara
        </h3>
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="flex-shrink-0" style="width:220px;height:220px;">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="flex-1 w-full space-y-2">
                @foreach($poll->options->sortByDesc(fn($o) => $o->vote_count) as $option)
                <div class="flex items-center justify-between py-2" style="border-bottom:1px solid rgba(255,255,255,.05);">
                    <span class="text-sm text-gray-300">{{ $option->name }}</span>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-white">{{ $option->vote_count }}</span>
                        <span class="text-xs text-gray-500 w-12 text-right">{{ $option->percentage }}%</span>
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
    @else
    <div class="card p-10 text-center mb-6">
        <i class="fas fa-hourglass-half text-4xl text-gray-700 mb-3 block"></i>
        <p class="text-gray-500">Belum ada suara masuk.</p>
        <a href="{{ route('poll.show', $poll->slug) }}" target="_blank" class="btn-primary mt-4">
            <i class="fas fa-external-link-alt"></i> Buka Halaman Voting
        </a>
    </div>
    @endif

    {{-- Voter list --}}
    @if($poll->votes->count() > 0)
    <div class="card mb-6">
        <div class="px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
            <h3 class="font-bold text-white flex items-center gap-2">
                <i class="fas fa-list-ul text-primary-500"></i> Daftar Pemilih
                <span class="text-xs font-normal text-gray-500 ml-1">({{ $poll->votes->count() }} orang)</span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="table-head">
                        <th class="text-left">#</th>
                        <th class="text-left">{{ $poll->primary_key_label }}</th>
                        <th class="text-left">Pilihan</th>
                        <th class="text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($poll->votes->sortByDesc('created_at') as $i => $vote)
                    <tr class="table-row">
                        <td class="text-gray-600">{{ $i + 1 }}</td>
                        <td class="font-mono text-sm">{{ $vote->voter_key }}</td>
                        <td>
                            <span class="badge-active">{{ $vote->option->name ?? '—' }}</span>
                        </td>
                        <td class="text-gray-500 text-xs">{{ $vote->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Actions --}}
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
            <i class="fas fa-external-link-alt"></i> Halaman Publik
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
// Animate progress bars
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('[data-width]').forEach(el => {
            el.style.width = el.dataset.width;
        });
    }, 200);
});

function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({ icon: 'success', title: 'Link disalin!', background: '#1f2937', color: '#fff', confirmButtonColor: '#dc2626', timer: 1500, showConfirmButton: false });
    });
}

@if($poll->total_votes > 0)
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($poll->options->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($poll->options->map(fn($o) => $o->vote_count)) !!},
            backgroundColor: ['#dc2626','#ef4444','#f87171','#fca5a5','#fecaca','#fee2e2'],
            borderColor: '#0d1117',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        }
    }
});
@endif
</script>
@endpush
