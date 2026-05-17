@extends('layouts.admin')
@section('title', 'Detail User — ' . $user->name)
@section('page_title', 'Detail User')
@section('page_subtitle', $user->name)

@section('main')
<div class="max-w-4xl">
    <div class="grid md:grid-cols-3 gap-6 mb-6">
        <!-- Profile -->
        <div class="card p-6 text-center">
            <img src="{{ $user->avatar_url }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-4 ring-4 ring-primary-700">
            <h3 class="font-bold text-white text-lg">{{ $user->name }}</h3>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            @if($user->phone)<p class="text-gray-600 text-sm">{{ $user->phone }}</p>@endif
            <div class="mt-4">
                @if($user->is_active)
                    <span class="badge-active"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif</span>
                @else
                    <span class="badge-inactive">Nonaktif</span>
                @endif
            </div>
            <p class="text-xs text-gray-600 mt-3">Daftar: {{ $user->created_at->format('d M Y') }}</p>
        </div>
        <!-- Stats -->
        <div class="md:col-span-2 grid grid-cols-2 gap-4">
            <div class="card p-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-900/30 rounded-lg flex items-center justify-center"><i class="fas fa-poll text-primary-500"></i></div>
                <div>
                    <p class="text-xs text-gray-500">Total Polling</p>
                    <p class="text-2xl font-extrabold text-white">{{ $user->polls->count() }}</p>
                </div>
            </div>
            <div class="card p-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-900/20 rounded-lg flex items-center justify-center"><i class="fas fa-vote-yea text-blue-400"></i></div>
                <div>
                    <p class="text-xs text-gray-500">Total Suara</p>
                    <p class="text-2xl font-extrabold text-white">{{ $totalVotes }}</p>
                </div>
            </div>
            <div class="card p-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-green-900/20 rounded-lg flex items-center justify-center"><i class="fas fa-check-circle text-green-400"></i></div>
                <div>
                    <p class="text-xs text-gray-500">Polling Aktif</p>
                    <p class="text-2xl font-extrabold text-white">{{ $user->polls->where('is_active', true)->count() }}</p>
                </div>
            </div>
            <div class="card p-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center"><i class="fas fa-ban text-gray-500"></i></div>
                <div>
                    <p class="text-xs text-gray-500">Polling Nonaktif</p>
                    <p class="text-2xl font-extrabold text-white">{{ $user->polls->where('is_active', false)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Polls list -->
    <div class="card overflow-hidden">
        <div class="p-5 border-b border-white/5">
            <h3 class="font-bold text-white flex items-center gap-2"><i class="fas fa-list text-primary-500"></i> Daftar Polling</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-900/50 border-b border-white/5">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Suara</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($user->polls as $poll)
                    <tr class="hover:bg-white/2">
                        <td class="px-5 py-3 text-white font-medium">{{ \Str::limit($poll->title, 40) }}</td>
                        <td class="px-5 py-3 text-gray-300">{{ $poll->votes_count }}</td>
                        <td class="px-5 py-3">
                            @if($poll->is_active)<span class="badge-active">Aktif</span>
                            @else<span class="badge-inactive">Off</span>@endif
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $poll->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.polls.show', $poll) }}" class="btn-secondary btn-sm text-xs px-3 py-1.5">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-gray-500">Belum ada polling.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
