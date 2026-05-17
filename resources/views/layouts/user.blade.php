@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-gray-900 border-r border-white/5 flex flex-col fixed h-full z-30 transition-transform duration-300 lg:translate-x-0 -translate-x-full">
        <!-- Logo -->
        <div class="p-5 border-b border-white/5">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg viewBox="0 0 24 24" fill="white" class="w-5 h-5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xl font-bold text-white">e-<span class="text-primary-500">Polling</span></span>
            </a>
        </div>

        <!-- Nav -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider px-4 mb-2">Menu</p>
            <a href="{{ route('user.dashboard') }}" class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home w-4"></i> Dashboard
            </a>
            <a href="{{ route('user.polls.index') }}" class="sidebar-link {{ request()->routeIs('user.polls.*') ? 'active' : '' }}">
                <i class="fas fa-poll w-4"></i> Polling Saya
            </a>
            <a href="{{ route('user.polls.create') }}" class="sidebar-link {{ request()->routeIs('user.polls.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle w-4"></i> Buat Polling
            </a>

            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider px-4 mb-2 mt-4">Ekspor</p>
            <a href="{{ route('user.export.summary.pdf') }}" class="sidebar-link">
                <i class="fas fa-file-pdf w-4 text-red-400"></i> Export PDF
            </a>
            <a href="{{ route('user.export.summary.excel') }}" class="sidebar-link">
                <i class="fas fa-file-excel w-4 text-green-400"></i> Export Excel
            </a>

            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider px-4 mb-2 mt-4">Akun</p>
            <a href="{{ route('user.profile') }}" class="sidebar-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle w-4"></i> Profil Saya
            </a>
        </nav>

        <!-- User info + logout -->
        <div class="p-4 border-t border-white/5">
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover ring-2 ring-primary-600">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <button onclick="confirmLogout()" class="w-full flex items-center gap-2 px-4 py-2 rounded-lg text-sm text-gray-400 hover:bg-red-900/30 hover:text-red-400 transition-colors">
                <i class="fas fa-sign-out-alt w-4"></i> Logout
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </aside>

    <!-- Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Main content -->
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
        <!-- Top bar -->
        <header class="bg-gray-900/80 backdrop-blur border-b border-white/5 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-white">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-500">@yield('page_subtitle', '')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('user.polls.create') }}" class="btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Buat Polling
                </a>
            </div>
        </header>

        <main class="flex-1 p-6">
            @if($errors->any())
            <div class="mb-4 bg-red-900/30 border border-red-700 rounded-lg p-4">
                <ul class="list-disc list-inside text-red-400 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
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
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        text: 'Apakah Anda yakin ingin keluar?',
        icon: 'question',
        background: '#1f2937',
        color: '#fff',
        iconColor: '#dc2626',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
</script>
@endpush
@endsection
