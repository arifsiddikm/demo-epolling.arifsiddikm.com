@extends('layouts.user')
@section('title', 'Polling Saya')
@section('page_title', 'Polling Saya')
@section('page_subtitle', 'Kelola semua polling Anda')

@section('main')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-400 text-sm">Total: <span class="text-white font-semibold">{{ $polls->total() }}</span> polling</p>
    <div class="flex gap-2">
        <a href="{{ route('user.export.summary.excel') }}" class="btn-secondary btn-sm">
            <i class="fas fa-file-excel text-green-400"></i> Excel
        </a>
        <a href="{{ route('user.export.summary.pdf') }}" class="btn-secondary btn-sm">
            <i class="fas fa-file-pdf text-red-400"></i> PDF
        </a>
        <a href="{{ route('user.polls.create') }}" class="btn-primary btn-sm">
            <i class="fas fa-plus"></i> Buat Polling
        </a>
    </div>
</div>

@if($polls->count())
<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach($polls as $poll)
    <div class="card overflow-hidden hover:border-primary-800/40 transition-colors group">
        @if($poll->image)
        <img src="{{ $poll->image_url }}" alt="{{ $poll->title }}" class="w-full h-36 object-cover">
        @else
        <div class="w-full h-36 bg-gradient-to-br from-primary-900/40 to-gray-800 flex items-center justify-center">
            <i class="fas fa-poll text-4xl text-primary-700"></i>
        </div>
        @endif
        <div class="p-5">
            <div class="flex items-start justify-between gap-2 mb-3">
                <h3 class="font-bold text-white text-base leading-tight group-hover:text-primary-400 transition-colors">{{ $poll->title }}</h3>
                <label class="toggle-switch {{ $poll->is_active ? 'bg-green-600' : 'bg-gray-700' }} flex-shrink-0" title="{{ $poll->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                    <input type="checkbox" class="sr-only poll-toggle" data-id="{{ $poll->id }}" {{ $poll->is_active ? 'checked' : '' }}>
                    <span class="absolute {{ $poll->is_active ? 'translate-x-5' : 'translate-x-0' }} left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-200 block"></span>
                </label>
            </div>
            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $poll->description ?? 'Tidak ada deskripsi.' }}</p>
            <div class="flex items-center gap-3 text-xs text-gray-600 mb-4">
                <span><i class="fas fa-vote-yea mr-1"></i>{{ $poll->votes_count }} suara</span>
                <span><i class="fas fa-list mr-1"></i>{{ $poll->options_count ?? $poll->options->count() }} pilihan</span>
            </div>
            <!-- Copy link -->
            <div class="flex items-center gap-1 bg-gray-800 rounded-lg p-2 mb-4">
                <input type="text" value="{{ $poll->url }}" class="flex-1 bg-transparent text-xs text-gray-400 outline-none truncate" readonly>
                <button onclick="copyLink('{{ $poll->url }}')" class="text-primary-400 hover:text-primary-300 px-2 transition-colors">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('user.polls.show', $poll) }}" class="btn-secondary btn-sm flex-1 justify-center">
                    <i class="fas fa-chart-bar"></i> Rekap
                </a>
                <a href="{{ route('user.polls.edit', $poll) }}" class="btn-secondary btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <button onclick="deletePoll({{ $poll->id }})" class="btn-danger btn-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-6">{{ $polls->links() }}</div>
@else
<div class="card p-16 text-center">
    <i class="fas fa-poll text-6xl text-gray-700 mb-4 block"></i>
    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Polling</h3>
    <p class="text-gray-500 mb-6">Buat polling pertama Anda sekarang dan bagikan ke peserta.</p>
    <a href="{{ route('user.polls.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> Buat Polling Pertama
    </a>
</div>
@endif
@endsection

@push('scripts')
<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({ icon: 'success', title: 'Link disalin!', text: url, background: '#1f2937', color: '#fff', confirmButtonColor: '#dc2626', timer: 2000, timerProgressBar: true });
    });
}

document.querySelectorAll('.poll-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        const label = this.closest('label');
        const span = label.querySelector('span');
        fetch(`/dashboard/polls/${id}/toggle`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                label.className = `toggle-switch ${data.is_active ? 'bg-green-600' : 'bg-gray-700'} flex-shrink-0`;
                span.className = `absolute ${data.is_active ? 'translate-x-5' : 'translate-x-0'} left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-200 block`;
                Swal.fire({ icon: 'success', title: data.message, background: '#1f2937', color: '#fff', confirmButtonColor: '#dc2626', timer: 1500 });
            }
        });
    });
});

function deletePoll(id) {
    Swal.fire({
        title: 'Hapus Polling?', text: 'Semua data voting akan ikut terhapus. Tindakan ini tidak bisa dibatalkan!',
        icon: 'warning', background: '#1f2937', color: '#fff', iconColor: '#dc2626',
        showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/dashboard/polls/' + id;
            form.innerHTML = '<input type="hidden" name="_token" value="' + token + '"><input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
