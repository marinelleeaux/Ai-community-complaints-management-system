<x-layouts.guest>
    <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-blue-600/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-4 font-black">
                <svg viewBox="0 0 48 48" class="w-8 h-8 fill-none stroke-current" stroke-width="3">
                    <path d="M24 4L8 10V22C8 32 15 41 24 44C33 41 40 32 40 22V10L24 4Z" />
                    <circle cx="24" cy="22" r="6" />
                </svg>
            </div>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Resident & Official Login</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Barangay North Poblacion, Maramag, Bukidnon</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    Email Address
                </label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="resident@gmail.com"
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all">
                @error('email')
                    <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Password
                    </label>
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        Forgot Password?
                    </a>
                </div>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all">
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
            <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white font-bold text-sm shadow-lg shadow-blue-950/20 hover:shadow-xl transition-all">
                Sign In to Portal
            </button>
        </form>

        <!-- Quick Demo Credentials for Fast Testing -->
        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-3">Quick Login (Testing Accounts)</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" 
                        onclick="document.getElementById('email').value='jan.auditor@gmail.com'; document.getElementById('password').value='Password123!';"
                        class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-semibold text-center transition-colors">
                    Resident (Jan)
                </button>
                <button type="button" 
                        onclick="document.getElementById('email').value='admin@northpoblacion.gov.ph'; document.getElementById('password').value='Password123!';"
                        class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-semibold text-center transition-colors">
                    Administrator
                </button>
            </div>
        </div>

        <!-- Registration Link -->
        <div class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
            Don't have an approved resident account yet?
            <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 ml-1">
                Register as Resident
            </a>
        </div>
    </div>
</x-layouts.guest>
