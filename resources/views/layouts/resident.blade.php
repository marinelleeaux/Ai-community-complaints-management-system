<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-authenticated" content="true">

    <title>{{ $title ?? 'Resident Portal' }} - AI Community Complaint Management System</title>

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
<body class="bg-[#F5F7FA] dark:bg-[#0F172A] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col md:flex-row transition-colors duration-200 antialiased selection:bg-blue-500 selection:text-white">

    <!-- Mobile Header -->
    <div class="md:hidden flex items-center justify-between px-4 py-3 bg-white dark:bg-[#1E293B] border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                AI
            </div>
            <span class="font-bold text-xs text-[#12345B] dark:text-white tracking-tight">BARANGAY NORTH POBLACION</span>
        </div>
        <button type="button" onclick="document.getElementById('resident-sidebar').classList.toggle('-translate-x-full')" class="p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <!-- Sidebar (Matches User's Reference Screenshot) -->
    <aside id="resident-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-[#1E293B] border-r border-slate-100 dark:border-slate-800 flex flex-col justify-between transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out shadow-sm md:shadow-none overflow-hidden">
        <div>
            <!-- Sidebar Logo / Header -->
            <div class="px-6 py-6 border-b border-slate-50 dark:border-slate-800/60">
                <a href="{{ route('resident.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-700 text-white flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform p-2 relative">
                        <svg viewBox="0 0 48 48" class="w-full h-full fill-none stroke-current" stroke-width="2.5">
                            <path d="M24 4L8 10V22C8 32 15 41 24 44C33 41 40 32 40 22V10L24 4Z" />
                            <circle cx="24" cy="22" r="6" />
                            <path d="M24 16V10M24 28V34M18 22H12M30 22H36" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-xs tracking-wider text-[#12345B] dark:text-blue-400 uppercase leading-snug">
                            AI COMMUNITY
                        </div>
                        <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 leading-tight">
                            COMPLAINT MANAGEMENT SYSTEM
                        </div>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-5 space-y-1.5">
                <a href="{{ route('resident.dashboard') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('resident.dashboard') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('resident.dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('resident.complaints.index') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('resident.complaints.index*') || request()->routeIs('resident.complaints.show*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('resident.complaints.index*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>My Complaints</span>
                </a>

                <a href="{{ route('resident.complaints.create') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('resident.complaints.create') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('resident.complaints.create') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8"/>
                    </svg>
                    <span>New Complaint</span>
                </a>

                <a href="{{ route('resident.chatbot') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('resident.chatbot*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                        </svg>
                        <span>AI Assistant</span>
                    </div>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-100 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 rounded-full">AI</span>
                </a>

                <a href="{{ route('resident.announcements.index') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('resident.announcements*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>Announcements</span>
                </a>

                <a href="{{ route('resident.notifications.index') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('resident.notifications*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span>Notifications</span>
                    </div>
                    @php $unreadCount = Auth::user()?->unreadNotificationsCount() ?? 0; @endphp
                    @if($unreadCount > 0)
                        <span id="sidebar-notification-badge" class="w-5 h-5 rounded-full bg-blue-600 text-white text-[11px] font-bold flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('resident.profile') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('resident.profile*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profile</span>
                </a>

                <a href="{{ route('resident.settings') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('resident.settings*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span>Settings</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Sidebar Bottom: Village Hills Landscape Vector Art (Directly matching user screenshot) -->
        <div class="relative w-full h-32 overflow-hidden pointer-events-none mt-auto">
            <svg class="absolute bottom-0 w-full h-full text-blue-500/20 dark:text-blue-600/10" viewBox="0 0 288 120" preserveAspectRatio="none" fill="currentColor">
                <!-- Background Mountain Curves -->
                <path d="M0 70 Q 70 30 144 50 T 288 35 L 288 120 L 0 120 Z" opacity="0.3"/>
                <path d="M0 80 Q 90 55 180 75 T 288 65 L 288 120 L 0 120 Z" opacity="0.6"/>
                <path d="M0 95 Q 100 80 200 95 T 288 90 L 288 120 L 0 120 Z" opacity="0.9"/>
            </svg>

            <!-- Illustrated Houses & Trees Silhouette -->
            <div class="absolute bottom-1 inset-x-0 px-4 flex items-end justify-between text-blue-600/40 dark:text-blue-400/20">
                <svg class="w-12 h-10" viewBox="0 0 40 30" fill="currentColor">
                    <polygon points="10,0 0,15 20,15" />
                    <rect x="2" y="15" width="16" height="15" />
                    <circle cx="28" cy="18" r="8" />
                    <rect x="26" y="24" width="4" height="6" />
                </svg>
                <svg class="w-16 h-12" viewBox="0 0 60 40" fill="currentColor">
                    <polygon points="20,0 5,20 35,20" />
                    <rect x="8" y="20" width="24" height="20" />
                    <rect x="14" y="26" width="6" height="14" fill="white" opacity="0.4" />
                    <circle cx="48" cy="22" r="10" />
                    <rect x="46" y="30" width="4" height="10" />
                </svg>
                <svg class="w-10 h-8" viewBox="0 0 30 25" fill="currentColor">
                    <polygon points="12,0 2,12 22,12" />
                    <rect x="4" y="12" width="16" height="13" />
                </svg>
            </div>
        </div>
    </aside>

    <!-- Main Content Layout -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Header Bar (Matching user screenshot) -->
        <header class="px-6 md:px-10 py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-transparent">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#12345B] dark:text-white tracking-tight">
                    Welcome, {{ Auth::user()->name }}!
                </h1>
                <div class="flex items-center gap-1.5 text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>North Poblacion, Maramag, Bukidnon</span>
                </div>
            </div>

            <!-- Top Actions: Notification Bell & User Avatar Pill -->
            <div class="flex items-center gap-3 md:gap-4 self-end sm:self-center">
                <!-- Theme Toggle -->
                <x-theme-toggle />

                <!-- Notification Bell Button -->
                <div class="relative" id="notification-dropdown-wrapper">
                    <a href="{{ route('resident.notifications.index') }}" 
                       class="relative w-11 h-11 rounded-2xl bg-white dark:bg-[#1E293B] border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php $unreads = Auth::user()?->unreadNotificationsCount() ?? 0; @endphp
                        <span id="notification-badge" class="{{ $unreads > 0 ? '' : 'hidden' }} absolute -top-1 -right-1 w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shadow-sm">
                            {{ $unreads }}
                        </span>
                    </a>
                </div>

                <!-- User Profile Pill (Matching User Screenshot: JA circle + Name + Chevron) -->
                <a href="{{ route('resident.profile') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-2xl bg-white dark:bg-[#1E293B] border border-slate-100 dark:border-slate-800 shadow-sm hover:border-slate-200 dark:hover:border-slate-700 transition-all">
                    <div class="w-9 h-9 rounded-xl bg-blue-600/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 font-extrabold text-sm flex items-center justify-center">
                        {{ Auth::user()->initials() }}
                    </div>
                    <span class="font-semibold text-sm text-[#12345B] dark:text-white hidden lg:inline">
                        {{ Auth::user()->name }}
                    </span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 px-6 md:px-10 py-6 max-w-7xl w-full">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center gap-3 shadow-sm">
                    <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">✓</span>
                    <div class="font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm flex items-center gap-3 shadow-sm">
                    <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-xs">⚠</span>
                    <div class="font-medium">{{ session('error') }}</div>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm pointer-events-none"></div>

    @stack('scripts')
</body>
</html>
