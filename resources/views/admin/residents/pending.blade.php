<x-layouts.admin>
    <x-slot:header>Pending Resident Approvals</x-slot:header>

    <div class="space-y-6 animate-fade-in-up">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Resident Verification Applications</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Review submitted resident registrations and authorize portal access</p>
            </div>
            <a href="{{ route('admin.residents.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                &larr; Back to Directory
            </a>
        </div>

        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-xs hover-lift">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($residents as $res)
                    <div class="py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                                {{ $res->initials() }}
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">{{ $res->name }}</h3>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $res->email }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $res->phone }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $res->purok?->name ?? 'Purok' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Address: {{ $res->address }}</p>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Applied on {{ $res->created_at->format('M d, Y • h:i A') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 self-end md:self-auto shrink-0">
                            <!-- Approve Form -->
                            <form method="POST" action="{{ route('admin.residents.approve', $res->id) }}">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-xs shadow-md shadow-emerald-900/30 transition-all hover:scale-105 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Approve Resident</span>
                                </button>
                            </form>

                            <!-- Reject Modal Toggle -->
                            <button type="button" 
                                    onclick="openRejectModal('{{ $res->id }}', '{{ addslashes($res->name) }}')"
                                    class="px-4 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 text-rose-600 dark:text-rose-400 font-bold text-xs transition-colors border border-rose-200 dark:border-rose-900">
                                Reject...
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center text-xs text-slate-400">
                        All resident registration applications have been verified. No pending requests.
                    </div>
                @endforelse
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                {{ $residents->links() }}
            </div>
        </div>
    </div>

    <!-- Reject Resident Dialog Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-rose-200 dark:border-rose-900 space-y-4 animate-fade-in-up">
            <h3 class="text-lg font-black text-rose-600 dark:text-rose-400 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Reject Resident Registration</span>
            </h3>
            <p id="reject-modal-text" class="text-xs text-slate-600 dark:text-slate-300">
                Please specify the official reason why this resident application cannot be approved.
            </p>

            <form id="reject-form" method="POST" action="" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Rejection Reason <span class="text-rose-500">*</span></label>
                    <textarea name="rejection_reason" rows="3" required placeholder="e.g. Incomplete address proof; not found in Purok resident masterlist..." class="w-full p-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-rose-500"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openRejectModal(id, name) {
            document.getElementById('reject-form').action = `/admin/residents/${id}/reject`;
            document.getElementById('reject-modal-text').innerHTML = `Please specify the official reason why registration for <strong>${name}</strong> is being rejected:`;
            document.getElementById('reject-modal').classList.remove('hidden');
        }
    </script>
    @endpush
</x-layouts.admin>
