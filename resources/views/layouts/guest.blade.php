<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'AI-Community Complaint Management System' }} - Barangay North Poblacion, Maramag</title>

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
<body class="bg-[#F8FAFC] dark:bg-[#071426] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-200 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Top Navigation Bar (Matching user design screenshot) -->
    <header class="w-full px-6 md:px-12 py-3.5 flex items-center justify-between border-b border-slate-200/80 dark:border-slate-800/80 bg-white/95 dark:bg-[#0B172E]/95 backdrop-blur-md sticky top-0 z-50">
        <x-brand-logo />

        <!-- Navigation Links & Actions (Right Side) -->
        <div class="flex items-center gap-4 lg:gap-6">
            <div class="hidden md:flex items-center gap-4 lg:gap-6">
                @auth
                    @if(Auth::user()->isAdmin() || Auth::user()->isOfficial())
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-xs transition-all">
                            Admin Portal &rarr;
                        </a>
                    @else
                        <a href="{{ route('resident.dashboard') }}" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-xs transition-all">
                            Resident Portal &rarr;
                        </a>
                    @endif
                @else
                    <!-- Register Button (Blue Pill) -->
                    <a href="{{ route('register') }}" class="px-6 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-xs hover:shadow transition-all">
                        Register
                    </a>

                    <!-- Sign In Button (Outline Pill) -->
                    <a href="{{ route('login') }}" class="px-6 py-2 rounded-xl bg-white dark:bg-transparent border border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 font-semibold text-sm transition-all">
                        Sign In
                    </a>
                @endauth

                <!-- Main Navigation Links -->
                <nav class="flex items-center gap-5 lg:gap-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="transition-colors py-1 {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        Home
                    </a>
                    
                    <a href="{{ route('about') }}" class="relative transition-colors py-1 {{ request()->routeIs('about') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        About
                        @if(request()->routeIs('about'))
                            <span class="absolute -bottom-1.5 left-0 right-0 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                        @endif
                    </a>

                    <a href="{{ route('how-it-works') }}" class="relative transition-colors py-1 {{ request()->routeIs('how-it-works') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        How It Works
                        @if(request()->routeIs('how-it-works'))
                            <span class="absolute -bottom-1.5 left-0 right-0 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                        @endif
                    </a>

                    <a href="{{ route('contact') }}" class="relative transition-colors py-1 {{ request()->routeIs('contact') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        Contact
                        @if(request()->routeIs('contact'))
                            <span class="absolute -bottom-1.5 left-0 right-0 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                        @endif
                    </a>
                </nav>
            </div>

            <!-- Theme Toggle Button -->
            <x-theme-toggle />

            <!-- Mobile Hamburger Toggle -->
            <button type="button" 
                    id="mobile-nav-toggle"
                    onclick="document.getElementById('mobile-nav-menu').classList.toggle('hidden')"
                    class="md:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    aria-label="Toggle navigation">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-nav-menu" class="hidden md:hidden bg-white dark:bg-[#0B172E] border-b border-slate-200 dark:border-slate-800 px-6 py-4 space-y-3 animate-fade-in">
        <nav class="flex flex-col gap-2.5 text-sm font-semibold">
            <a href="{{ route('home') }}" class="py-1.5 {{ request()->routeIs('home') ? 'text-blue-600' : 'text-slate-700 dark:text-slate-300' }}">Home</a>
            <a href="{{ route('about') }}" class="py-1.5 {{ request()->routeIs('about') ? 'text-blue-600 font-bold' : 'text-slate-700 dark:text-slate-300' }}">About</a>
            <a href="{{ route('how-it-works') }}" class="py-1.5 {{ request()->routeIs('how-it-works') ? 'text-blue-600 font-bold' : 'text-slate-700 dark:text-slate-300' }}">How It Works</a>
            <a href="{{ route('contact') }}" class="py-1.5 {{ request()->routeIs('contact') ? 'text-blue-600 font-bold' : 'text-slate-700 dark:text-slate-300' }}">Contact</a>
        </nav>
        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-3">
            @auth
                <a href="{{ route('home') }}" class="w-full text-center py-2 rounded-xl bg-blue-600 text-white font-bold text-xs">Portal Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="flex-1 text-center py-2 rounded-xl bg-blue-600 text-white font-bold text-xs">Register</a>
                <a href="{{ route('login') }}" class="flex-1 text-center py-2 rounded-xl border border-blue-600 text-blue-600 dark:text-blue-400 font-bold text-xs">Sign In</a>
            @endauth
        </div>
    </div>

    <!-- Main Content Slot -->
    <main class="flex-1 flex flex-col items-center justify-center w-full px-4 py-8 md:py-12">
        <div class="w-full max-w-7xl mx-auto">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-500 dark:text-slate-500 border-t border-slate-200/80 dark:border-slate-800/80 bg-white/50 dark:bg-[#071426]/50">
        <p>© {{ date('Y') }} Barangay North Poblacion, Municipality of Maramag, Bukidnon. Republic of the Philippines.</p>
        <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-600">AI-Powered Community Complaint Management System • In compliance with Republic Act No. 10173 (Data Privacy Act)</p>
    </footer>

    <script>
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
</body>
</html>
