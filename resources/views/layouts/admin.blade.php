@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Admin Sidebar -->
    <aside id="sidebar" class="w-64 bg-black border-r border-white/5 flex flex-col fixed h-full z-30 transition-transform duration-300 lg:translate-x-0 -translate-x-full">
        <div class="p-5 border-b border-white/5 bg-primary-700/20">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center">
                    <svg viewBox="0 0 24 24" fill="white" class="w-5 h-5"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <span class="text-lg font-bold text-white">e-<span class="text-primary-400">Polling</span></span>
                    <p class="text-xs text-primary-400 font-medium">Admin Panel</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider px-4 mb-2">Navigasi</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-4"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users w-4"></i> Kelola User
            </a>
            <a href="{{ route('admin.polls.index') }}" class="sidebar-link {{ request()->routeIs('admin.polls.*') ? 'active' : '' }}">
                <i class="fas fa-poll-h w-4"></i> Kelola Polling
            </a>

            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider px-4 mb-2 mt-4">Laporan</p>
            <a href="{{ route('admin.export.polls.pdf') }}" class="sidebar-link">
                <i class="fas fa-file-pdf w-4 text-red-400"></i> Export Polling PDF
            </a>
            <a href="{{ route('admin.export.polls.excel') }}" class="sidebar-link">
                <i class="fas fa-file-excel w-4 text-green-400"></i> Export Polling Excel
            </a>
            <a href="{{ route('admin.export.users.pdf') }}" class="sidebar-link">
                <i class="fas fa-file-pdf w-4 text-red-400"></i> Export User PDF
            </a>
            <a href="{{ route('admin.export.users.excel') }}" class="sidebar-link">
                <i class="fas fa-file-excel w-4 text-green-400"></i> Export User Excel
            </a>

            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider px-4 mb-2 mt-4">Pengaturan</p>
            <a href="{{ route('admin.account') }}" class="sidebar-link {{ request()->routeIs('admin.account') ? 'active' : '' }}">
                <i class="fas fa-user-shield w-4"></i> Akun Admin
            </a>
        </nav>

        <div class="p-4 border-t border-white/5">
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover ring-2 ring-primary-600">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-primary-400 font-medium">Administrator</p>
                </div>
            </div>
            <button onclick="confirmLogout()" class="w-full flex items-center gap-2 px-4 py-2 rounded-lg text-sm text-gray-400 hover:bg-red-900/30 hover:text-red-400 transition-colors">
                <i class="fas fa-sign-out-alt w-4"></i> Logout
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </aside>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <div class="flex-1 lg:ml-64 flex flex-col">
        <header class="bg-black/80 backdrop-blur border-b border-white/5 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-white">@yield('page_title', 'Admin Panel')</h1>
                    <p class="text-xs text-gray-500">@yield('page_subtitle', '')</p>
                </div>
            </div>
            <span class="bg-primary-900/50 text-primary-400 border border-primary-800 text-xs font-semibold px-3 py-1 rounded-full">
                <i class="fas fa-shield-alt mr-1"></i> Admin
            </span>
        </header>

        <main class="flex-1 p-6 bg-gray-950">
            @if($errors->any())
            <div class="mb-4 bg-red-900/30 border border-red-700 rounded-lg p-4">
                <ul class="list-disc list-inside text-red-400 text-sm space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif
            @yield('main')
        </main>
    </div>
</div>

@push('scripts')
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.toggle('hidden');
}
function confirmLogout() {
    Swal.fire({
        title: 'Logout Admin?', text: 'Anda akan keluar dari panel admin.',
        icon: 'warning', background: '#111827', color: '#fff', iconColor: '#dc2626',
        showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Logout', cancelButtonText: 'Batal'
    }).then((r) => { if (r.isConfirmed) document.getElementById('logout-form').submit(); });
}
</script>
@endpush
@endsection
