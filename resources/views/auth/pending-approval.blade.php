<x-layouts.guest>
    <div class="w-full max-w-md mx-auto my-2 sm:my-4 animate-fade-in-up">
        <!-- Executive Verification Card -->
        <div class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none transition-all">
            <!-- Top Gradient Accent -->
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>

            <div class="p-6 sm:p-7 text-center">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/20 mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    Account Pending Review
                </div>

                <!-- Verification Icon Badge -->
                <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500/20 via-blue-500/10 to-purple-500/20 border border-amber-500/30 dark:border-amber-500/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-4 shadow-sm group">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                <!-- Card Heading -->
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Application Under Verification
                </h1>

                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed">
                    Hello, <strong class="text-slate-800 dark:text-white font-bold">{{ $user?->name ?? 'Resident' }}</strong>! Your registration is queued for official identity confirmation by <strong>Barangay North Poblacion</strong>.
                </p>

                <!-- Compact 3-Step Verification Timeline -->
                <div class="my-5 p-3 rounded-2xl bg-slate-50/80 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800/80">
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black shadow-xs mb-1">
                                &check;
                            </div>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200">Registered</span>
                            <span class="text-[9px] text-emerald-600 dark:text-emerald-400 font-semibold">Complete</span>
                        </div>

                        <div class="flex flex-col items-center relative">
                            <div class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] font-black shadow-xs animate-pulse mb-1">
                                &bull;
                            </div>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200">Verification</span>
                            <span class="text-[9px] text-amber-600 dark:text-amber-400 font-semibold">In Review</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-[10px] font-black mb-1">
                                &lock;
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">Full Access</span>
                            <span class="text-[9px] text-slate-400 dark:text-slate-500">Unlocks Soon</span>
                        </div>
                    </div>
                </div>

                <!-- Compact Application Details -->
                <div class="p-3.5 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 text-left space-y-2 text-xs mb-5">
                    <div class="flex justify-between items-center py-0.5 border-b border-slate-100/60 dark:border-slate-800/60">
                        <span class="text-slate-400 dark:text-slate-500 font-medium text-[11px]">Applicant:</span>
                        <span class="font-bold text-slate-700 dark:text-slate-200 text-[11px] truncate max-w-[200px]">{{ $user?->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-0.5 border-b border-slate-100/60 dark:border-slate-800/60">
                        <span class="text-slate-400 dark:text-slate-500 font-medium text-[11px]">Email:</span>
                        <span class="font-bold text-slate-700 dark:text-slate-200 text-[11px] truncate max-w-[200px]">{{ $user?->email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-0.5 border-b border-slate-100/60 dark:border-slate-800/60">
                        <span class="text-slate-400 dark:text-slate-500 font-medium text-[11px]">Purok:</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400 text-[11px]">{{ $user?->purok?->name ?? 'Purok 5' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400 dark:text-slate-500 font-medium text-[11px]">Submitted:</span>
                        <span class="font-medium text-slate-600 dark:text-slate-300 text-[11px]">{{ $user?->created_at?->format('M d, Y • h:i A') ?? 'Recent' }}</span>
                    </div>
                </div>

                <!-- Action Controls -->
                <div class="space-y-2.5">
                    <!-- Refresh / Check Status Button -->
                    <button type="button" 
                            onclick="window.location.reload()" 
                            class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/20 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Check Live Approval Status</span>
                    </button>

                    <!-- Sign Out Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800/80 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors border border-transparent dark:border-slate-700/50">
                            Log Out for Now
                        </button>
                    </form>
                </div>

                <!-- Municipal Support Info Footer -->
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-400 dark:text-slate-500">
                    <p>Barangay Hall Office Hours: <strong>Mon - Fri (8:00 AM - 5:00 PM)</strong></p>
                    <p class="mt-0.5">Assistance Hotline: <span class="font-semibold text-slate-600 dark:text-slate-400">(088) 123-4567</span></p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
