<x-layouts.admin>
    <x-slot:header>Pending Resident Approvals</x-slot:header>

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Resident Verification Applications</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Review submitted resident registrations and authorize portal access</p>
        </div>

        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($residents as $res)
                    <div class="py-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 font-black text-sm flex items-center justify-center shrink-0">
                                {{ $res->initials() }}
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">{{ $res->name }}</h3>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    <span>📧 {{ $res->email }}</span>
                                    <span>📞 {{ $res->phone }}</span>
                                    <span>📍 {{ $res->purok?->name ?? 'Purok' }}</span>
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
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5">
                                    <span>✓ Approve Resident</span>
                                </button>
                            </form>

                            <!-- Reject Modal Toggle -->
                            <button type="button" 
                                    onclick="openRejectModal('{{ $res->id }}', '{{ addslashes($res->name) }}')"
                                    class="px-4 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 text-rose-600 dark:text-rose-400 font-bold text-xs transition-colors">
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
    <div id="reject-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-slate-200 dark:border-slate-700 space-y-4">
            <h3 class="text-lg font-black text-rose-600 dark:text-rose-400">Reject Resident Registration</h3>
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
