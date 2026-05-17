@extends('layouts.admin')
@section('title', 'Kelola Polling')
@section('page_title', 'Kelola Polling')
@section('page_subtitle', 'Semua polling di sistem')

@section('main')
<div class="card overflow-hidden">
    <div class="p-5 border-b border-white/5 flex flex-wrap items-center gap-3 justify-between">
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input pl-9 w-64" placeholder="Cari judul polling...">
            </div>
            <select name="status" class="form-select w-36">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn-primary btn-sm">Filter</button>
        </form>
        <div class="flex gap-2">
            <a href="{{ route('admin.export.polls.pdf') }}" class="btn-secondary btn-sm"><i class="fas fa-file-pdf text-red-400"></i> PDF</a>
            <a href="{{ route('admin.export.polls.excel') }}" class="btn-secondary btn-sm"><i class="fas fa-file-excel text-green-400"></i> Excel</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-900/50 border-b border-white/5">
                <tr>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Polling</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembuat</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Suara</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($polls as $i => $poll)
                <tr class="hover:bg-white/2 transition-colors">
                    <td class="px-5 py-4 text-gray-600">{{ $polls->firstItem() + $i }}</td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-white">{{ \Str::limit($poll->title, 35) }}</p>
                        <p class="text-xs text-gray-600">{{ $poll->options_count ?? '?' }} pilihan</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-gray-300 text-sm">{{ $poll->user->name ?? 'N/A' }}</p>
                        <p class="text-gray-600 text-xs">{{ $poll->user->email ?? '' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="font-bold text-white">{{ $poll->votes_count }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <label class="toggle-switch {{ $poll->is_active ? 'bg-green-600' : 'bg-gray-700' }} cursor-pointer">
                            <input type="checkbox" class="sr-only poll-toggle" data-id="{{ $poll->id }}" {{ $poll->is_active ? 'checked' : '' }}>
                            <span class="absolute {{ $poll->is_active ? 'translate-x-5' : 'translate-x-0' }} left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-200 block"></span>
                        </label>
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs">{{ $poll->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4">
                        <div class="flex gap-1.5">
                            <a href="{{ route('admin.polls.show', $poll) }}" class="btn-secondary btn-sm text-xs px-3 py-1.5"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('poll.show', $poll->slug) }}" target="_blank" class="btn-secondary btn-sm text-xs px-3 py-1.5"><i class="fas fa-external-link-alt"></i></a>
                            <button onclick="deletePoll({{ $poll->id }})" class="btn-danger btn-sm text-xs px-3 py-1.5"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                        <i class="fas fa-poll-h text-4xl text-gray-700 mb-3 block"></i>Tidak ada data polling.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-white/5">{{ $polls->withQueryString()->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.poll-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        const label = this.closest('label');
        const span = label.querySelector('span');
        fetch(`/webmin/polls/${id}/toggle`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                label.className = `toggle-switch ${data.is_active ? 'bg-green-600' : 'bg-gray-700'} cursor-pointer`;
                span.className = `absolute ${data.is_active ? 'translate-x-5' : 'translate-x-0'} left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-200 block`;
                Swal.fire({ icon: 'success', title: data.message, background: '#111827', color: '#fff', confirmButtonColor: '#dc2626', timer: 1500 });
            }
        });
    });
});

function deletePoll(id) {
    Swal.fire({
        title: 'Hapus Polling?', text: 'Semua data voting akan terhapus permanen!',
        icon: 'warning', background: '#111827', color: '#fff', iconColor: '#dc2626',
        showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            fetch(`/webmin/polls/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: data.message, background: '#111827', color: '#fff', confirmButtonColor: '#dc2626', timer: 1500 })
                        .then(() => location.reload());
                }
            });
        }
    });
}
</script>
@endpush
