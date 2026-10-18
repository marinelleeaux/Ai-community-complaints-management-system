<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Favicon: Official Barangay Hall Icon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F7FC] dark:bg-[#0B132B] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-200 antialiased selection:bg-blue-600 selection:text-white">

    @php
        $user = Auth::user();
        $residentUserId = $user?->id;
        $myActiveComplaintsCount = $residentUserId ? \Illuminate\Support\Facades\Cache::remember("user_{$residentUserId}_active_complaints_count", 30, function() use ($residentUserId) {
            return \App\Models\Complaint::where('resident_id', $residentUserId)->whereIn('status', ['submitted', 'under_review'])->count();
        }) : 0;
        $unreadNotifCount = $residentUserId ? \Illuminate\Support\Facades\Cache::remember("user_{$residentUserId}_unread_notifs_count", 120, function() use ($residentUserId) {
            return \App\Models\Notification::where('recipient_id', $residentUserId)->where('is_read', false)->count();
        }) : 0;
        $recentNotifications = $residentUserId ? \Illuminate\Support\Facades\Cache::remember("user_{$residentUserId}_recent_notifs", 120, function() use ($residentUserId) {
            return \App\Models\Notification::where('recipient_id', $residentUserId)->latest()->take(6)->get();
        }) : collect();
    @endphp

    <!-- Top Navigation Bar (Full Width Header) -->
    <header class="h-18 px-4 sm:px-6 md:px-8 bg-white/95 dark:bg-[#111C33]/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 sticky top-0 z-40 flex items-center justify-between shadow-2xs">

        <!-- Left: Logo & Official System Branding (Returned Nav Bar) -->
        <div class="flex items-center gap-3">
            <!-- Mobile Menu Toggle Button -->
            <button type="button" onclick="toggleResidentSidebar()" aria-label="Toggle Navigation Menu" class="md:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <a href="{{ route('resident.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-blue-50/80 dark:bg-blue-950/60 p-1 border border-blue-100 dark:border-blue-900/60 flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/blue-barangay-icon.svg') }}" alt="Barangay Seal" class="w-8 h-8 object-contain">
                </div>
                <div class="leading-tight">
                    <div class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>AI-Community Complaint Management System</span>
                        <span class="hidden xl:inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100/70 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/80">
                            RESIDENT
                        </span>
                    </div>
                    <div class="text-[10px] sm:text-[11px] font-medium text-slate-400">
                        Barangay North Poblacion, Maramag, Bukidnon
                    </div>
                </div>
            </a>
        </div>

        <!-- Right: Actions (Live Time, Notification, Theme, Profile Dropdown) -->
        <div class="flex items-center gap-2.5 sm:gap-3">
            <!-- Live Date & Time Pill -->
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-slate-100/80 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span id="resident-live-time" class="font-bold text-[#12345B] dark:text-white font-mono text-[11px]">{{ now()->format('h:i:s A') }}</span>
                <span class="text-slate-300 dark:text-slate-600">•</span>
                <span id="resident-live-date" class="text-slate-500 dark:text-slate-400 font-medium text-[11px]">{{ now()->format('D, M j, Y') }}</span>
            </div>

            <!-- Notification Bell with Notify Dropdown Panel -->
            <div class="relative" id="resident-notif-wrapper">
                <button type="button" 
                        onclick="document.getElementById('resident-notif-dropdown').classList.toggle('hidden')" 
                        class="relative p-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        title="Notifications">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($unreadNotifCount > 0)
                        <span class="absolute top-1.5 right-1.5 min-w-[1rem] h-4 px-1 rounded-full bg-rose-500 text-white text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                            {{ $unreadNotifCount }}
                        </span>
                    @endif
                </button>

                <!-- Notifications Dropdown Panel -->
                <div id="resident-notif-dropdown" class="hidden absolute right-0 mt-2 w-80 sm:w-96 rounded-3xl bg-white dark:bg-[#111C33] border border-slate-200/90 dark:border-slate-800 shadow-2xl py-3 z-50 text-xs">
                    <div class="px-4 pb-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h3 class="font-extrabold text-slate-900 dark:text-white text-sm">Notifications</h3>
                            @if($unreadNotifCount > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 dark:bg-rose-950/70 text-rose-600 dark:text-rose-400">
                                    {{ $unreadNotifCount }} new
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('resident.notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                    Mark all read
                                </button>
                            </form>
                            <span class="text-slate-300 dark:text-slate-700">|</span>
                            <form method="POST" action="{{ route('resident.notifications.unread-all') }}">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400">
                                    Mark unread
                                </button>
                            </form>
                            <span class="text-slate-300 dark:text-slate-700">|</span>
                            <form method="POST" action="{{ route('resident.notifications.delete-all') }}" onsubmit="return confirmAction(event, 'Are you sure you want to delete all notifications?', 'Delete All Notifications', 'Yes, Delete All');">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-rose-500 hover:text-rose-700">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($recentNotifications as $notif)
                            <div class="p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors flex items-start justify-between gap-3 {{ $notif->is_read ? 'opacity-70' : 'bg-blue-50/40 dark:bg-blue-950/20' }}">
                                <div class="flex items-start gap-2.5 min-w-0">
                                    <span class="w-2 h-2 rounded-full mt-1.5 shrink-0 {{ $notif->is_read ? 'bg-slate-300 dark:bg-slate-700' : 'bg-blue-600' }}"></span>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 dark:text-white truncate text-xs">{{ $notif->title }}</p>
                                        <p class="text-slate-500 dark:text-slate-400 text-[11px] line-clamp-2 mt-0.5 leading-tight">{{ $notif->message }}</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    @if(! $notif->is_read)
                                        <form method="POST" action="{{ route('resident.notifications.read', $notif->id) }}">
                                            @csrf
                                            <button type="submit" class="p-1 rounded text-slate-400 hover:text-blue-600" title="Mark as read">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('resident.notifications.unread', $notif->id) }}">
                                            @csrf
                                            <button type="submit" class="p-1 rounded text-slate-400 hover:text-amber-600" title="Mark as unread">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('resident.notifications.destroy', $notif->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600" title="Delete">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">
                                No notifications yet.
                            </div>
                        @endforelse
                    </div>

                    <div class="px-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-center">
                        <a href="{{ route('resident.notifications.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                            View All Notifications &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Single Theme Toggle (Moon / Sun) -->
            <button type="button" 
                    onclick="toggleSystemTheme()" 
                    id="resident-theme-toggle"
                    class="p-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    title="Toggle Theme">
                <!-- Moon Icon (Light Mode) -->
                <svg class="w-5 h-5 block dark:hidden text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <!-- Sun Icon (Dark Mode) -->
                <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- User Profile Pill with Dropdown (Resident Picture and Name Above) -->
            <div class="relative" id="resident-user-menu-wrapper">
                <button type="button" 
                        onclick="document.getElementById('resident-user-menu-dropdown').classList.toggle('hidden')" 
                        class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors">
                    @if(Auth::user()->avatarUrl())
                        <img src="{{ Auth::user()->avatarUrl() }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-500/20 shrink-0">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#12345B] to-blue-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs">
                            {{ Auth::user()->initials() }}
                        </div>
                    @endif
                    <div class="text-left hidden sm:block">
                        <div class="font-bold text-xs text-slate-900 dark:text-white leading-tight">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-[10px] font-medium text-emerald-600 dark:text-emerald-400 leading-none mt-0.5">
                            Verified Resident
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="resident-user-menu-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl py-2 z-50 text-xs animate-in fade-in slide-in-from-top-2">
                    <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                        <div class="font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] text-slate-500">{{ Auth::user()->email }}</div>
                    </div>

                    <a href="{{ route('resident.profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>My Profile</span>
                    </a>

                    <a href="{{ route('resident.settings') }}" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>Account Settings</span>
                    </a>

                    <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>

                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-semibold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Sign out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Workspace Container: Sidebar + Content Area -->
    <div class="flex-1 flex min-h-[calc(100vh-4.5rem)] relative">
        <!-- Mobile Drawer Backdrop Overlay (Cellphone & Tablet) -->
        <div id="resident-sidebar-backdrop" 
             onclick="closeResidentSidebar()" 
             class="hidden fixed inset-0 bg-slate-950/50 backdrop-blur-xs z-25 md:hidden transition-opacity"></div>

        <!-- Sidebar Navigation (Aesthetic, clean, professional) -->
        <aside id="resident-sidebar" 
               class="fixed md:static inset-y-18 left-0 z-30 w-64 sm:w-68 lg:w-72 bg-white/95 dark:bg-[#0E1726]/95 backdrop-blur-md border-r border-slate-200/80 dark:border-slate-800/80 flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shadow-lg md:shadow-none overflow-y-auto">
            
            <div class="p-3.5 sm:p-4 flex-1 flex flex-col">
                <!-- Clean Aesthetic Navigation Header -->
                <div class="flex items-center justify-between pb-3 mb-2 px-1 border-b border-slate-100 dark:border-slate-800/80">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Resident Menu
                        </span>
                    </div>
                    <!-- Close button for mobile drawer -->
                    <button type="button" onclick="closeResidentSidebar()" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Close Sidebar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <nav class="space-y-1.5 flex-1">
                    <!-- 1. Dashboard -->
                    <a href="{{ route('resident.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('resident.dashboard') ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('resident.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- 2. My Complaints -->
                    <a href="{{ route('resident.complaints.index') }}" 
                       class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('resident.complaints.index*') || request()->routeIs('resident.complaints.show*') ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 {{ request()->routeIs('resident.complaints.index*') || request()->routeIs('resident.complaints.show*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span class="truncate">My Complaints</span>
                        </div>
                        @if($myActiveComplaintsCount > 0)
                            <span class="min-w-5 h-5 px-1.5 rounded-full text-[10px] font-black bg-blue-600 text-white flex items-center justify-center shadow-xs shrink-0 ml-2 animate-pulse" title="{{ $myActiveComplaintsCount }} Active Complaints">
                                {{ $myActiveComplaintsCount }}
                            </span>
                        @endif
                    </a>

                    <!-- 3. New Complaint -->
                    <a href="{{ route('resident.complaints.create') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('resident.complaints.create') ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('resident.complaints.create') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8"/>
                        </svg>
                        <span>File Complaint</span>
                    </a>

                    <!-- 4. Announcements -->
                    <a href="{{ route('resident.announcements.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('resident.announcements*') ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('resident.announcements*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                    </a>

                    <!-- 5. AI Chatbot -->
                    <a href="{{ route('resident.chatbot') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('resident.chatbot*') ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('resident.chatbot*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span>AI Barangay Assistant</span>
                    </a>

                    <!-- 6. Profile -->
                    <a href="{{ route('resident.profile') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('resident.profile') ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('resident.profile') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>My Profile</span>
                    </a>

                    <!-- 7. Settings -->
                    <a href="{{ route('resident.settings') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('resident.settings*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('resident.settings*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span>System Settings</span>
                    </a>
                </nav>
            </div>

            <!-- Aesthetic Civic Footer Status Card -->
            <div class="p-3.5 mt-auto w-full select-none">
                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-blue-50/70 via-slate-50/40 to-indigo-50/30 dark:from-slate-900/60 dark:via-[#111C33]/40 dark:to-blue-950/30 border border-slate-200/70 dark:border-slate-800/80 shadow-2xs">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[11px] font-extrabold text-slate-800 dark:text-slate-200 truncate">
                                Resident Portal
                            </div>
                            <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Verified Citizen Access</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 sm:p-6 md:p-8 overflow-y-auto">
            @if(session('success'))
                <div class="flash-auto-dismiss mb-5 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center gap-3 shadow-2xs transition-all duration-500 print:hidden">
                    <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="flash-auto-dismiss mb-5 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-semibold flex items-center gap-3 shadow-2xs transition-all duration-500 print:hidden">
                    <span class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center font-bold text-xs shrink-0">!</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- Floating Ask AI Assistant Bubble Widget (Resident Exclusive) -->
    <x-ai-chat-bubble />

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-40 flex flex-col gap-2 max-w-sm pointer-events-none"></div>

    <!-- Theme & Search Keyboard Listener Script -->
    <script>
        function toggleSystemTheme() {
            const html = document.documentElement;
            const current = html.classList.contains('dark') ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            if (next === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
            localStorage.setItem('npc_theme', next);
        }

        // Mobile Sidebar Drawer Controller (Cellphone & Tablet)
        function toggleResidentSidebar() {
            const sidebar = document.getElementById('resident-sidebar');
            const backdrop = document.getElementById('resident-sidebar-backdrop');
            if (sidebar) sidebar.classList.toggle('-translate-x-full');
            if (backdrop) backdrop.classList.toggle('hidden');
        }

        function closeResidentSidebar() {
            const sidebar = document.getElementById('resident-sidebar');
            const backdrop = document.getElementById('resident-sidebar-backdrop');
            if (sidebar) sidebar.classList.add('-translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
        }

        // Close user dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const menu = document.getElementById('resident-user-menu-dropdown');
            const wrapper = document.getElementById('resident-user-menu-wrapper');
            if (menu && wrapper && !wrapper.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Live Resident Clock & Date Updater
        function updateResidentClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            const dateStr = now.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
            const timeEl = document.getElementById('resident-live-time');
            const dateEl = document.getElementById('resident-live-date');
            if (timeEl) timeEl.textContent = timeStr;
            if (dateEl) dateEl.textContent = dateStr;
        }
        updateResidentClock();
        setInterval(updateResidentClock, 1000);

        // Silent Auto-dismiss Flash Alerts in 5 Seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.flash-auto-dismiss');
            if (alerts.length > 0) {
                setTimeout(function() {
                    alerts.forEach(function(el) {
                        el.classList.add('opacity-0', '-translate-y-2');
                        setTimeout(function() {
                            if (el && el.parentNode) {
                                el.parentNode.removeChild(el);
                            }
                        }, 500);
                    });
                }, 5000);
            }
        });
    </script>

    @stack('scripts')
    <x-confirm-modal />
</body>
</html>
