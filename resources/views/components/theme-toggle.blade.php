<!-- Single Animated Dynamic Theme Toggle Icon -->
<button type="button" 
        id="single-theme-toggle"
        onclick="toggleThemeMode()" 
        title="Toggle Light / Dark Mode"
        aria-label="Toggle Theme"
        class="relative w-10 h-10 rounded-2xl flex items-center justify-center bg-slate-100/90 hover:bg-slate-200/90 dark:bg-slate-800/90 dark:hover:bg-slate-700/90 text-slate-700 dark:text-amber-400 border border-slate-200/80 dark:border-slate-700/80 shadow-xs hover:shadow-md transition-all duration-300 transform active:scale-90 hover:scale-105 group">
    
    <!-- Sun Icon (Visible in Dark Mode) -->
    <svg id="theme-icon-sun" 
         class="w-5 h-5 hidden dark:block text-amber-400 group-hover:rotate-45 transition-transform duration-500 ease-out drop-shadow-[0_0_8px_rgba(251,191,36,0.5)]" 
         viewBox="0 0 24 24" 
         fill="none" 
         stroke="currentColor" 
         stroke-width="2.2" 
         stroke-linecap="round" 
         stroke-linejoin="round">
        <circle cx="12" cy="12" r="4" fill="currentColor" fill-opacity="0.2"/>
        <path d="M12 2v2"/>
        <path d="M12 20v2"/>
        <path d="m4.93 4.93 1.41 1.41"/>
        <path d="m17.66 17.66 1.41 1.41"/>
        <path d="M2 12h2"/>
        <path d="M20 12h2"/>
        <path d="m6.34 17.66-1.41 1.41"/>
        <path d="m19.07 4.93-1.41 1.41"/>
    </svg>

    <!-- Moon Icon (Visible in Light Mode) -->
    <svg id="theme-icon-moon" 
         class="w-5 h-5 block dark:hidden text-[#12345B] group-hover:-rotate-12 transition-transform duration-500 ease-out" 
         viewBox="0 0 24 24" 
         fill="none" 
         stroke="currentColor" 
         stroke-width="2.2" 
         stroke-linecap="round" 
         stroke-linejoin="round">
        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" fill="currentColor" fill-opacity="0.15"/>
        <path d="M19 3v4"/>
        <path d="M21 5h-4"/>
    </svg>
</button>

<script>
    function toggleThemeMode() {
        const isDark = document.documentElement.classList.contains('dark');
        const newTheme = isDark ? 'light' : 'dark';
        
        if (newTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        localStorage.setItem('npc_theme', newTheme);

        // Optional server sync if logged in
        if (window.csrfToken) {
            fetch('/resident/settings/theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({ theme: newTheme })
            }).catch(() => {});
        }
    }
</script>
