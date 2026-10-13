<x-layouts.guest>
    <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none text-center">
        <div class="w-16 h-16 rounded-3xl bg-rose-50 dark:bg-rose-950/60 text-rose-500 dark:text-rose-400 flex items-center justify-center mx-auto mb-5 shadow-sm">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6m0-6l6 6" />
            </svg>
        </div>

        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 mb-3">
            Registration Application Not Approved
        </span>

        <h2 class="text-2xl font-black text-[#12345B] dark:text-white">
            Application Status: Rejected
        </h2>

        <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 mt-3 leading-relaxed">
            Dear <strong>{{ $user?->name ?? 'Resident' }}</strong>, your resident registration application could not be verified by Barangay Officials at this time.
        </p>

        @if($user?->rejection_reason)
            <div class="my-6 p-4 rounded-2xl bg-rose-50/80 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-left text-xs">
                <div class="font-bold text-rose-900 dark:text-rose-200 mb-1">Official Reason for Rejection:</div>
                <div class="text-rose-700 dark:text-rose-300">{{ $user->rejection_reason }}</div>
            </div>
        @endif

        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">
            If you believe this is an error or need to update your address/identity documents, please visit the Barangay Hall in person or contact the Secretariat at (088) 123-4567.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-3 px-6 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition-colors">
                Sign Out
            </button>
        </form>
    </div>
</x-layouts.guest>
