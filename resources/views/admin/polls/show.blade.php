@extends('layouts.admin')
@section('title', 'Detail Polling')
@section('page_title', 'Detail Polling')
@section('page_subtitle', $poll->title)

@section('main')
<div class="max-w-4xl">
    <div class="card overflow-hidden mb-6">
        @if($poll->image)
        <img src="{{ $poll->image_url }}" class="w-full h-44 object-cover">
        @endif
        <div class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-white">{{ $poll->title }}</h2>
                    @if($poll->description)<p class="text-gray-400 mt-1 text-sm">{{ strip_tags($poll->description) }}</p>@endif
                    <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-500">
                        <span><i class="fas fa-user mr-1 text-primary-600"></i>{{ $poll->user->name ?? '-' }}</span>
                        <span><i class="fas fa-key mr-1 text-yellow-600"></i>{{ $poll->primary_key_label }}</span>
                        <span><i class="fas fa-vote-yea mr-1 text-blue-500"></i>{{ $poll->total_votes }} suara</span>
                        <span><i class="fas fa-calendar mr-1"></i>{{ $poll->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                @if($poll->is_active)<span class="badge-active shrink-0">Aktif</span>@else<span class="badge-inactive shrink-0">Nonaktif</span>@endif
            </div>
        </div>
    </div>

    <!-- Options recap -->
    <div class="grid md:grid-cols-{{ min($poll->options->count(), 3) }} gap-4 mb-6">
        @foreach($poll->options->sortByDesc(fn($o) => $o->vote_count) as $i => $option)
        <div class="card p-5 {{ $i === 0 && $poll->total_votes > 0 ? 'border-primary-700/50' : '' }}">
            @if($option->image)<img src="{{ $option->image_url }}" class="w-full h-24 object-cover rounded-lg mb-3">@endif
            @if($i === 0 && $poll->total_votes > 0)
            <p class="text-xs text-yellow-400 font-semibold mb-1"><i class="fas fa-crown mr-1"></i>Unggul</p>
            @endif
            <h4 class="font-bold text-white">{{ $option->name }}</h4>
            <p class="text-2xl font-extrabold {{ $i === 0 && $poll->total_votes > 0 ? 'text-primary-400' : 'text-white' }} mt-1">{{ $option->percentage }}%</p>
            <p class="text-xs text-gray-500">{{ $option->vote_count }} suara</p>
            <div class="w-full bg-gray-800 rounded-full h-2 mt-2">
                <div class="bg-primary-600 h-2 rounded-full" style="width:{{ $option->percentage }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Voters table -->
    <div class="card overflow-hidden mb-6">
        <div class="p-5 border-b border-white/5">
            <h3 class="font-bold text-white flex items-center gap-2"><i class="fas fa-list text-primary-500"></i> Data Pemilih ({{ $poll->votes->count() }})</h3>
        </div>
        <div class="overflow-x-auto max-h-80 overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-900 sticky top-0">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">#</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ $poll->primary_key_label }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Pilihan</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($poll->votes as $i => $vote)
                    <tr class="hover:bg-white/2">
                        <td class="px-5 py-3 text-gray-600">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 text-white font-mono text-sm">{{ $vote->voter_key }}</td>
                        <td class="px-5 py-3 text-gray-400">{{ $vote->voter_name ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-300">{{ $vote->option->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $vote->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">Belum ada suara masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('poll.show', $poll->slug) }}" target="_blank" class="btn-secondary">
            <i class="fas fa-external-link-alt"></i> Lihat Publik
        </a>
        <a href="{{ route('admin.polls.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
