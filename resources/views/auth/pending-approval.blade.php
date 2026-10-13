<x-layouts.guest>
    <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none text-center">
        <div class="w-16 h-16 rounded-3xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 dark:text-amber-400 flex items-center justify-center mx-auto mb-5 shadow-sm">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 mb-3">
            Account Pending Review
        </span>

        <h2 class="text-2xl font-black text-[#12345B] dark:text-white">
            Application Under Verification
        </h2>

        <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 mt-3 leading-relaxed">
            Maayong adlaw, <strong>{{ $user?->name ?? 'Resident' }}</strong>! Your registration has been submitted and is currently queued for identity verification by the officials of <strong>Barangay North Poblacion</strong>.
        </p>

        <div class="my-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 text-left space-y-2 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-400">Email:</span>
                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $user?->email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Purok:</span>
                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $user?->purok?->name ?? 'Purok 5' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Barangay Hall Office:</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">Sayre Highway, North Poblacion</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Hotline:</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">(088) 123-4567 / 0917-123-4567</span>
            </div>
        </div>

        <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-6">
            Approvals are usually completed during standard municipal office hours (Monday - Friday, 8:00 AM - 5:00 PM). Protected resident features will unlock automatically upon approval.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-3 px-6 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition-colors">
                Log Out for Now
            </button>
        </form>
    </div>
</x-layouts.guest>
