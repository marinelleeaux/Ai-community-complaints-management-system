<x-layouts.guest>
    <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none text-center">
        <div class="w-16 h-16 rounded-3xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center mx-auto mb-5 shadow-sm">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <h2 class="text-2xl font-black text-[#12345B] dark:text-white">
            Access Restricted
        </h2>

        <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 mt-3 leading-relaxed">
            Your account does not have permission to view this resource, or your profile status has been deactivated.
        </p>

        <div class="mt-6">
            <a href="{{ route('login') }}" class="inline-block py-3 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-colors">
                Return to Login
            </a>
        </div>
    </div>
</x-layouts.guest>
