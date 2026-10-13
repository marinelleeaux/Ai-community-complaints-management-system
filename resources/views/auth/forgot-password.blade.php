<x-layouts.guest>
    <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Recover Password</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Enter your registered email address and we'll send you recovery instructions.
            </p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    Registered Email Address
                </label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="resident@gmail.com"
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white font-bold text-sm shadow-md transition-all">
                Send Password Recovery Link
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
            Remember your credentials?
            <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 ml-1">
                Back to Sign In
            </a>
        </div>
    </div>
</x-layouts.guest>
