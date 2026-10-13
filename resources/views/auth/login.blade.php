<x-layouts.guest>
    <div class="glass-card rounded-3xl p-8 md:p-10 border border-slate-200/80 dark:border-slate-800 shadow-2xl animate-fade-in-up">
        <!-- Logo & Civic Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center mx-auto mb-4 font-black shadow-lg shadow-blue-500/25 animate-float-soft">
                <svg viewBox="0 0 48 48" class="w-9 h-9 fill-none stroke-current" stroke-width="2.5">
                    <path d="M24 4L8 10V22C8 32 15 41 24 44C33 41 40 32 40 22V10L24 4Z" />
                    <circle cx="24" cy="22" r="6" />
                    <path d="M24 16V10M24 28V34M18 22H12M30 22H36" />
                </svg>
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-900/40 mb-2">
                Official Civic Access
            </span>
            <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white tracking-tight">
                Resident & Official Sign In
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Barangay North Poblacion, Maramag, Bukidnon</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Email Address</span>
                </label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="resident@gmail.com"
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                @error('email')
                    <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Password</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors">
                        Forgot Password?
                    </a>
                </div>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                @error('password')
                    <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <label class="flex items-center text-xs font-medium text-slate-600 dark:text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 mr-2">
                    Remember my credentials
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#12345B] via-[#0B2340] to-blue-800 hover:from-[#0B2340] hover:to-blue-900 text-white font-extrabold text-sm shadow-xl shadow-blue-950/20 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                <span>Sign In to Portal</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Quick Demo Credentials for Fast Testing -->
        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-3">Quick Login (Testing Accounts)</p>
            <div class="grid grid-cols-2 gap-2.5 text-xs">
                <button type="button" 
                        onclick="document.getElementById('email').value='jan.auditor@gmail.com'; document.getElementById('password').value='Password123!';"
                        class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-bold text-center transition-all hover:scale-105 border border-blue-200/60 dark:border-blue-900/40">
                    👤 Resident (Jan)
                </button>
                <button type="button" 
                        onclick="document.getElementById('email').value='admin@northpoblacion.gov.ph'; document.getElementById('password').value='Password123!';"
                        class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-center transition-all hover:scale-105 border border-slate-200 dark:border-slate-700">
                    🛡️ Administrator
                </button>
            </div>
        </div>

        <!-- Registration Link -->
        <div class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
            Don't have an approved resident account yet?
            <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 ml-1">
                Register as Resident &rarr;
            </a>
        </div>
    </div>
</x-layouts.guest>
