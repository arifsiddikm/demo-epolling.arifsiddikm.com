@extends('layouts.admin')
@section('title', 'Kelola User')
@section('page_title', 'Kelola User')
@section('page_subtitle', 'Daftar semua user terdaftar')

@section('main')
<div class="card overflow-hidden">
    <!-- Table header -->
    <div class="p-5 border-b border-white/5 flex items-center justify-between gap-4">
        <form method="GET" class="flex items-center gap-2 flex-1 max-w-sm">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input pl-9" placeholder="Cari nama atau email...">
            </div>
            <button type="submit" class="btn-primary btn-sm">Cari</button>
        </form>
        <div class="flex gap-2">
            <a href="{{ route('admin.export.users.pdf') }}" class="btn-secondary btn-sm">
                <i class="fas fa-file-pdf text-red-400"></i> PDF
            </a>
            <a href="{{ route('admin.export.users.excel') }}" class="btn-secondary btn-sm">
                <i class="fas fa-file-excel text-green-400"></i> Excel
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-900/50 border-b border-white/5">
                <tr>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Polling</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Daftar</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $i => $user)
                <tr class="hover:bg-white/2 transition-colors">
                    <td class="px-5 py-4 text-gray-600">{{ $users->firstItem() + $i }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                            <div>
                                <p class="font-semibold text-white">{{ $user->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-gray-300">{{ $user->email }}</p>
                        <p class="text-gray-600 text-xs">{{ $user->phone ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="font-bold text-white">{{ $user->polls_count }}</span>
                        <span class="text-gray-500 text-xs"> polling</span>
                    </td>
                    <td class="px-5 py-4">
                        <label class="toggle-switch {{ $user->is_active ? 'bg-green-600' : 'bg-gray-700' }} cursor-pointer" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                            <input type="checkbox" class="sr-only user-toggle" data-id="{{ $user->id }}" {{ $user->is_active ? 'checked' : '' }}>
                            <span class="absolute {{ $user->is_active ? 'translate-x-5' : 'translate-x-0' }} left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-200 block"></span>
                        </label>
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4">
                        <div class="flex gap-1.5">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary btn-sm text-xs px-3 py-1.5">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="deleteUser({{ $user->id }})" class="btn-danger btn-sm text-xs px-3 py-1.5">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                        <i class="fas fa-users text-4xl text-gray-700 mb-3 block"></i>
                        Belum ada user terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-white/5">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.user-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        const label = this.closest('label');
        const span = label.querySelector('span');
        fetch(`/webmin/users/${id}/toggle`, {
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

function deleteUser(id) {
    Swal.fire({
        title: 'Hapus User?', text: 'Semua data dan polling user ini akan terhapus permanen!',
        icon: 'warning', background: '#111827', color: '#fff', iconColor: '#dc2626',
        showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            fetch(`/webmin/users/${id}`, {
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
