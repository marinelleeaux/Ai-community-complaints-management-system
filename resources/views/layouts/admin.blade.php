<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Portal' }} - Barangay North Poblacion Official Administration</title>

    <!-- Anti-flash Theme Script -->
    <script>
        (function() {
            const saved = localStorage.getItem('npc_theme') || 'system';
            const isDark = saved === 'dark' || (saved === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] dark:bg-[#0B132B] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col md:flex-row transition-colors duration-200 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Mobile Header -->
    <div class="md:hidden flex items-center justify-between px-4 py-3 bg-[#0B2340] text-white sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-blue-500 text-white flex items-center justify-center font-bold text-xs">
                NP
            </div>
            <span class="font-bold text-xs tracking-wider uppercase">ADMIN PORTAL • NORTH POBLACION</span>
        </div>
        <button type="button" onclick="document.getElementById('admin-sidebar').classList.toggle('-translate-x-full')" class="p-2 rounded-lg text-slate-300 hover:text-white">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <!-- Admin Sidebar -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0B2340] dark:bg-[#071426] text-white flex flex-col justify-between transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out shadow-xl md:shadow-none overflow-y-auto">
        <div>
            <!-- Admin Logo / Branding -->
            <div class="px-6 py-6 border-b border-blue-900/60 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white flex items-center justify-center font-extrabold shadow-lg shadow-blue-900/40 shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-black tracking-widest text-blue-300 uppercase">OFFICIAL PORTAL</h2>
                    <h1 class="text-sm font-extrabold text-white leading-tight">BARANGAY NORTH POBLACION</h1>
                    <p class="text-[11px] text-blue-200/70">Maramag, Bukidnon</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-5 space-y-1.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'text-slate-300 hover:bg-blue-900/40 hover:text-white' }}">
                    <svg class="w-5 h-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                @php
                    $pendingComplaintsCount = \App\Models\Complaint::where('status', 'submitted')->count();
                    $pendingResidentsCount = \App\Models\User::where('role', 'resident')->where('account_status', 'pending')->count();
                @endphp

                <a href="{{ route('admin.complaints.index') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.complaints*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'text-slate-300 hover:bg-blue-900/40 hover:text-white' }}">
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Complaints Queue</span>
                    </div>
                    @if($pendingComplaintsCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-500 text-slate-900">
                            {{ $pendingComplaintsCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.residents.index') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.residents*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'text-slate-300 hover:bg-blue-900/40 hover:text-white' }}">
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Residents Directory</span>
                    </div>
                    @if($pendingResidentsCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-400 text-slate-950">
                            {{ $pendingResidentsCount }} pending
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.announcements.index') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.announcements*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'text-slate-300 hover:bg-blue-900/40 hover:text-white' }}">
                    <svg class="w-5 h-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>Announcements</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.reports*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'text-slate-300 hover:bg-blue-900/40 hover:text-white' }}">
                    <svg class="w-5 h-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Reports & Analytics</span>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.audit-logs*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'text-slate-300 hover:bg-blue-900/40 hover:text-white' }}">
                    <svg class="w-5 h-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Audit Logs</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'text-slate-300 hover:bg-blue-900/40 hover:text-white' }}">
                    <svg class="w-5 h-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span>System Settings</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Bottom -->
        <div class="p-4 border-t border-blue-900/60 space-y-2">
            <a href="{{ route('resident.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-blue-200/80 hover:bg-blue-900/50 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>View Resident Portal</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-rose-300 hover:bg-rose-950/40 hover:text-rose-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Admin Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Bar -->
        <header class="px-6 md:px-10 py-5 bg-white dark:bg-[#111C33] border-b border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4 sticky top-0 z-30 shadow-xs">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ $header ?? 'Administrator Workspace' }}
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Barangay North Poblacion Administration Panel
                </p>
            </div>

            <div class="flex items-center gap-4">
                <x-theme-toggle />

                <div class="flex items-center gap-3 pl-3 border-l border-slate-200 dark:border-slate-800">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold text-xs flex items-center justify-center shadow-xs">
                        {{ Auth::user()->initials() }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-xs font-extrabold text-slate-900 dark:text-white leading-tight">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                            {{ ucfirst(Auth::user()->role) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 px-6 md:px-10 py-8 max-w-7xl w-full">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">✓</span>
                    <div class="font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-xs">⚠</span>
                    <div class="font-medium">{{ session('error') }}</div>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm pointer-events-none"></div>

    @stack('scripts')
</body>
</html>
