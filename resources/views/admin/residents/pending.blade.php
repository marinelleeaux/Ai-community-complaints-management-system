<x-layouts.admin>
    <x-slot:header>Pending Resident Approvals</x-slot:header>

    <div class="space-y-6 animate-fade-in-up">
        <!-- Executive Header & Quick Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-[#111C33] p-5 sm:p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500/20 via-blue-500/10 to-purple-500/20 border border-amber-500/30 text-amber-500 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">Resident Verification Applications</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $residents->total() > 0 ? 'bg-amber-500 text-white animate-pulse' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' }}">
                            {{ $residents->total() }} {{ Str::plural('Applicant', $residents->total()) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Review citizen registrations, verify Barangay residency proof, and authorize portal access</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 self-end sm:self-auto">
                <a href="{{ route('admin.residents.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800/80 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span>&larr;</span>
                    <span>All Residents Directory</span>
                </a>
            </div>
        </div>

        <!-- Pending Applications List -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                @forelse($residents as $res)
                    <div class="py-5 first:pt-0 last:pb-0 flex flex-col lg:flex-row lg:items-center justify-between gap-5 group transition-all">
                        <div class="flex items-start gap-4">
                            <!-- Initials Avatar with Gradient & Reflection -->
                            <div class="relative w-13 h-13 rounded-2xl bg-gradient-to-tr from-amber-500 via-orange-500 to-amber-600 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                <span>{{ $res->initials() }}</span>
                                <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-amber-400 border-2 border-white dark:border-[#111C33]"></span>
                            </div>

                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">{{ $res->name }}</h3>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-blue-900/60">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $res->purok?->name ?? 'Unassigned Purok' }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300">
                                        Pending Verification
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $res->email }}
                                    </span>
                                    @if($res->phone)
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            {{ $res->phone }}
                                        </span>
                                    @endif
                                </div>

                                @if($res->address)
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span class="truncate max-w-md">Resident Address: {{ $res->address }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center gap-1 text-[11px] text-slate-400 pt-0.5">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Registered on {{ $res->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('M d, Y • h:i A') }} ({{ $res->created_at->diffForHumans() }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Action Controls -->
                        <div class="flex items-center gap-2.5 self-end lg:self-center shrink-0 pt-2 lg:pt-0">
                            <!-- Approve Form -->
                            <form method="POST" action="{{ route('admin.residents.approve', $res->id) }}">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirmAction(event, 'Approve {{ addslashes($res->name) }} as a verified resident of Barangay North Poblacion?', 'Approve Resident Registration', 'Yes, Approve Resident', false);"
                                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 hover:from-emerald-700 hover:to-teal-800 text-white font-extrabold text-xs shadow-md shadow-emerald-900/20 hover:shadow-lg transition-all hover:scale-102 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Approve Resident</span>
                                </button>
                            </form>

                            <!-- Reject Modal Toggle -->
                            <button type="button" 
                                    onclick="openRejectModal('{{ $res->id }}', '{{ addslashes($res->name) }}')"
                                    class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 font-bold text-xs transition-colors border border-rose-200/80 dark:border-rose-900/80 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Reject...</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-500 dark:text-emerald-400 flex items-center justify-center mx-auto mb-3 shadow-2xs">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-200">No Pending Resident Applications</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">All resident registration requests have been verified. Any newly submitted registrations will appear in this administrative queue immediately.</p>
                        <a href="{{ route('admin.residents.index') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 transition-colors">
                            <span>Browse Verified Residents Directory</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                @endforelse
            </div>

            @if($residents->hasPages())
                <div class="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800">
                    {{ $residents->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Executive Reject Resident Dialog Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border border-rose-200 dark:border-rose-900/80 space-y-4 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-rose-600 dark:text-rose-400">
                        Reject Resident Registration
                    </h3>
                    <p class="text-[11px] text-slate-400">Official Municipal Notification</p>
                </div>
            </div>

            <p id="reject-modal-text" class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Please specify the official reason why this resident application cannot be verified:
            </p>

            <form id="reject-form" method="POST" action="" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Rejection Reason <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason_field" rows="3" required 
                              placeholder="e.g. Identity documents unverified; name does not match Purok masterlist..." 
                              class="w-full p-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-colors"></textarea>
                    
                    <!-- Quick Reason Suggestion Chips -->
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        <button type="button" onclick="setReason('Identity documents could not be verified by Barangay Secretariat.')" class="px-2 py-0.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[10px] text-slate-600 dark:text-slate-300 font-medium transition-colors">
                            Unverified Docs
                        </button>
                        <button type="button" onclick="setReason('Address is outside Barangay North Poblacion jurisdiction.')" class="px-2 py-0.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[10px] text-slate-600 dark:text-slate-300 font-medium transition-colors">
                            Outside Jurisdiction
                        </button>
                        <button type="button" onclick="setReason('Duplicate resident account detected on record.')" class="px-2 py-0.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[10px] text-slate-600 dark:text-slate-300 font-medium transition-colors">
                            Duplicate Account
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" 
                            onclick="document.getElementById('reject-modal').classList.add('hidden')" 
                            class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold shadow-sm transition-colors">
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
            document.getElementById('reject-modal-text').innerHTML = `Please specify the official reason why the registration for <strong class="text-slate-900 dark:text-white">${name}</strong> is being rejected:`;
            document.getElementById('rejection_reason_field').value = '';
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function setReason(reason) {
            document.getElementById('rejection_reason_field').value = reason;
        }
    </script>
    @endpush
</x-layouts.admin>
