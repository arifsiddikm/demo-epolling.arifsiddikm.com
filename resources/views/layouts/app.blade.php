<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'e-Polling') — e-Polling</title>
    <meta name="description" content="@yield('meta_description', 'Platform polling online terpercaya. Buat dan kelola polling dengan mudah, aman, dan transparan.')">
    <meta name="keywords" content="@yield('meta_keywords', 'polling, voting, e-polling, pemilihan online, suara digital')">
    <meta property="og:title" content="@yield('title', 'e-Polling')">
    <meta property="og:description" content="@yield('meta_description', 'Platform polling online terpercaya.')">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#dc2626', 50: '#fef2f2', 100: '#fee2e2', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c', 800: '#991b1b', 900: '#7f1d1d' },
                        dark: { DEFAULT: '#0f0f0f', 50: '#1a1a1a', 100: '#111111', 200: '#0d0d0d' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ─── Base ────────────────────────────────────────── */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ─── Sidebar ─────────────────────────────────────── */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar-link.active {
            background-color: #dc2626;
            color: #fff;
            box-shadow: 0 8px 24px rgba(185,28,28,.4);
        }
        .sidebar-link:not(.active) {
            color: #9ca3af;
        }
        .sidebar-link:not(.active):hover {
            background: rgba(255,255,255,.05);
            color: #fff;
        }

        /* ─── Buttons ─────────────────────────────────────── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(220,38,38,.35), inset 0 1px 0 rgba(255,255,255,.12);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
            box-shadow: 0 6px 20px rgba(220,38,38,.45), inset 0 1px 0 rgba(255,255,255,.15);
            transform: translateY(-1px);
            color: #fff;
        }
        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(239,68,68,.4);
        }
        .btn-primary:active { transform: scale(0.97); }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            background: rgba(255,255,255,.07);
            color: #e5e7eb;
            border: 1px solid rgba(255,255,255,.13);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.06);
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,.13);
            border-color: rgba(255,255,255,.22);
            transform: translateY(-1px);
            color: #fff;
        }
        .btn-secondary:focus { outline: none; box-shadow: 0 0 0 3px rgba(255,255,255,.2); }
        .btn-secondary:active { transform: scale(0.97); }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            background: linear-gradient(135deg, #dc2626 0%, #7f1d1d 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(185,28,28,.4);
        }
        .btn-danger:hover { filter: brightness(1.12); transform: translateY(-1px); color: #fff; }
        .btn-danger:active { transform: scale(0.97); }

        .btn-success {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(34,197,94,.3);
        }
        .btn-success:hover { filter: brightness(1.1); transform: translateY(-1px); color: #fff; }
        .btn-success:active { transform: scale(0.97); }

        .btn-warning {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(245,158,11,.3);
        }
        .btn-warning:hover { filter: brightness(1.1); transform: translateY(-1px); color: #fff; }
        .btn-warning:active { transform: scale(0.97); }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.5rem;
        }
        .btn-xs {
            padding: 0.25rem 0.625rem;
            font-size: 0.75rem;
            border-radius: 0.375rem;
        }

        /* ─── Card ────────────────────────────────────────── */
        .card {
            background: linear-gradient(145deg, #161b22 0%, #0d1117 100%);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0,0,0,.5), inset 0 1px 0 rgba(255,255,255,.04);
        }

        /* ─── Form Label ──────────────────────────────────── */
        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            margin-bottom: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ─── Form Input ──────────────────────────────────── */
        .form-input {
            width: 100%;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.10);
            color: #fff;
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-family: inherit;
            transition: all 0.2s;
            outline: none;
        }
        .form-input::placeholder { color: #4b5563; }
        .form-input:hover { border-color: rgba(255,255,255,.18); }
        .form-input:focus {
            background: rgba(255,255,255,.06);
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.15);
        }
        .form-input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ─── Form Textarea ───────────────────────────────── */
        .form-textarea {
            width: 100%;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.10);
            color: #fff;
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-family: inherit;
            transition: all 0.2s;
            outline: none;
            resize: none;
        }
        .form-textarea::placeholder { color: #4b5563; }
        .form-textarea:hover { border-color: rgba(255,255,255,.18); }
        .form-textarea:focus {
            background: rgba(255,255,255,.06);
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.15);
        }

        /* ─── Form Select ─────────────────────────────────── */
        .form-select {
            width: 100%;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.10);
            color: #fff;
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-family: inherit;
            transition: all 0.2s;
            outline: none;
            cursor: pointer;
            appearance: auto;
        }
        .form-select:hover { border-color: rgba(255,255,255,.18); }
        .form-select:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.15);
        }
        .form-select option { background: #1a1a2e; color: #e5e7eb; }

        /* ─── Form Checkbox / Radio ───────────────────────── */
        .form-check {
            width: 1rem;
            height: 1rem;
            border-radius: 0.25rem;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.05);
            accent-color: #dc2626;
            cursor: pointer;
        }
        .form-check:focus { outline: none; box-shadow: 0 0 0 3px rgba(239,68,68,.3); }

        /* ─── File Input ──────────────────────────────────── */
        .form-file {
            width: 100%;
            font-size: 0.875rem;
            color: #9ca3af;
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            transition: all 0.2s;
            cursor: pointer;
            background: rgba(255,255,255,.04);
            border: 1px dashed rgba(255,255,255,.15);
            font-family: inherit;
        }
        .form-file:hover {
            border-color: #ef4444;
            background: rgba(239,68,68,.05);
        }
        .form-file::file-selector-button {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            margin-right: 0.75rem;
            transition: all 0.2s;
            font-family: inherit;
        }
        .form-file::file-selector-button:hover { filter: brightness(1.15); }

        /* ─── Form Helper & Error ─────────────────────────── */
        .form-hint {
            font-size: 0.75rem;
            color: #4b5563;
            margin-top: 0.25rem;
        }
        .form-error {
            font-size: 0.75rem;
            color: #f87171;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* ─── Input with icon ─────────────────────────────── */
        .input-icon-wrap { position: relative; }
        .input-icon-wrap .input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 0.875rem;
            pointer-events: none;
        }
        .input-icon-wrap .form-input { padding-left: 2.5rem; }

        /* ─── Badges ──────────────────────────────────────── */
        .badge-active {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(34,197,94,.12);
            color: #4ade80;
            border: 1px solid rgba(34,197,94,.25);
        }
        .badge-inactive {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(255,255,255,.05);
            color: #9ca3af;
            border: 1px solid rgba(255,255,255,.08);
        }
        .badge-warning {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(245,158,11,.12);
            color: #fbbf24;
            border: 1px solid rgba(245,158,11,.25);
        }
        .badge-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(239,68,68,.12);
            color: #f87171;
            border: 1px solid rgba(239,68,68,.25);
        }

        /* ─── Table ───────────────────────────────────────── */
        .table-head th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .table-row td {
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
            color: #d1d5db;
            border-bottom: 1px solid rgba(255,255,255,.04);
        }
        .table-row:hover td { background: rgba(255,255,255,.025); }
        .table-row:last-child td { border-bottom: none; }

        /* ─── Toggle switch ───────────────────────────────── */
        .toggle-switch {
            position: relative;
            display: inline-flex;
            height: 1.5rem;
            width: 2.75rem;
            align-items: center;
            border-radius: 9999px;
            transition: colors 0.2s;
            cursor: pointer;
        }

        /* ─── Alerts ──────────────────────────────────────── */
        .alert-error {
            border-radius: 0.75rem;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.2);
        }
        .alert-warning {
            border-radius: 0.75rem;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: rgba(245,158,11,.08);
            border: 1px solid rgba(245,158,11,.2);
        }
        .alert-success {
            border-radius: 0.75rem;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: rgba(34,197,94,.08);
            border: 1px solid rgba(34,197,94,.2);
        }

        /* ─── Option card (voting) ────────────────────────── */
        .option-card-selected .option-box {
            border-color: #ef4444 !important;
            background: rgba(239,68,68,.06) !important;
        }

        /* ─── Animations ──────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp .4s ease both; }

        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-950 text-white min-h-screen">
    @yield('content')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', background: '#1f2937', color: '#fff', iconColor: '#22c55e', confirmButtonColor: '#dc2626', timer: 3000, timerProgressBar: true });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', background: '#1f2937', color: '#fff', iconColor: '#ef4444', confirmButtonColor: '#dc2626' });
    </script>
    @endif

    @stack('scripts')
</body>
</html>