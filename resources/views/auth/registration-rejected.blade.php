<x-layouts.guest>
    <div class="w-full max-w-md mx-auto my-2 sm:my-4 animate-fade-in-up">
        <div class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none transition-all">
            <!-- Top Gradient Accent -->
            <div class="h-1.5 w-full bg-gradient-to-r from-rose-500 via-rose-600 to-red-600"></div>

            <div class="p-6 sm:p-7 text-center">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-rose-500/10 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/20 mb-4">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Registration Not Approved
                </div>

                <!-- Rejection Icon Badge -->
                <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-500 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6m0-6l6 6" />
                    </svg>
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Application Status: Rejected
                </h1>

                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed">
                    Dear <strong class="text-slate-800 dark:text-white">{{ $user?->name ?? 'Resident' }}</strong>, your resident registration application could not be verified by Barangay Officials at this time.
                </p>

                @if($user?->rejection_reason)
                    <div class="my-5 p-3.5 rounded-2xl bg-rose-50/80 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-left text-xs">
                        <div class="font-bold text-rose-900 dark:text-rose-200 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Official Rejection Reason:</span>
                        </div>
                        <div class="text-rose-700 dark:text-rose-300 pl-5.5 leading-relaxed">{{ $user->rejection_reason }}</div>
                    </div>
                @endif

                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-5 leading-relaxed">
                    If you believe this is an error or need to update your address/identity documents, please visit the Barangay Hall in person or contact the Secretariat.
                </p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition-colors">
                        Sign Out
                    </button>
                </form>

                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-400 dark:text-slate-500">
                    <p>Barangay Hall Office Hotline: <strong>(088) 123-4567</strong></p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
