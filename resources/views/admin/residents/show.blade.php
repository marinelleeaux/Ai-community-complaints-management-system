<x-layouts.admin>
    <x-slot:header>Complainant Profile: {{ $resident->name }}</x-slot:header>

    <div class="space-y-6">
        <div>
            <a href="{{ route('admin.residents.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back to Complainants Directory</span>
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-2xl font-black text-[#12345B] dark:text-white">{{ $resident->name }}</h2>
                <div class="flex flex-wrap items-center gap-2">
                    @if($resident->account_status === 'pending')
                        <form method="POST" action="{{ route('admin.residents.approve', $resident->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs">
                                ✓ Approve Citizen
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.residents.destroy', $resident->id) }}" onsubmit="return confirmAction(event, 'Are you sure you want to delete this account?', 'Delete Complainant Account', 'Yes, Delete Account', true);">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-colors shadow-xs flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Delete Account</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resident Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Col: Profile Picture & Identity -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-5 text-center">
                    <!-- Profile Picture -->
                    <div class="flex flex-col items-center">
                        <div class="relative w-28 h-28 rounded-3xl overflow-hidden ring-4 ring-blue-500/20 shadow-md mb-3 bg-slate-100 dark:bg-slate-800">
                            <img src="{{ $resident->avatarUrl() }}" alt="{{ $resident->name }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">{{ $resident->name }}</h3>
                        <p class="text-xs text-slate-400">Citizen ID: #{{ $resident->id }}</p>
                        <div class="mt-2">
                            @if($resident->account_status === 'approved')
                                <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    ✓ Verified Citizen
                                </span>
                            @elseif($resident->account_status === 'pending')
                                <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                    ⏳ Pending Verification
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    {{ ucfirst($resident->account_status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($resident->avatar_path)
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-[11px] text-slate-400 block font-medium mb-2">Government / Barangay ID Document:</span>
                            <a href="{{ $resident->avatarUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-xs font-bold hover:underline">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Inspect Full Resolution Photo</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right 2 Cols: Personal Information & Redirection to Complaint Management -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Citizen Contact & Residence Information -->
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-5">
                    <h3 class="text-base font-extrabold text-[#12345B] dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">
                        Citizen Registration & Contact Details
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block font-medium text-[11px]">Official Email Address</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-1 block text-sm">{{ $resident->email }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block font-medium text-[11px]">Contact Mobile Phone</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-1 block text-sm">{{ $resident->phone ?: 'Not provided' }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block font-medium text-[11px]">Barangay Purok Jurisdiction</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-1 block text-sm">{{ $resident->purok?->name ?? 'Purok 5' }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block font-medium text-[11px]">System Registration Date</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-1 block text-sm">{{ $resident->created_at->format('F d, Y • h:i A') }}</span>
                        </div>
                        <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block font-medium text-[11px]">Permanent Residence Address</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-1 block leading-relaxed">{{ $resident->address ?: 'Barangay North Poblacion, Maramag, Bukidnon' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Complaints Delegation Notice Card (Complaints belong strictly in Complaint Management) -->
                <div class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/80 dark:from-slate-900 dark:via-[#111C33] dark:to-indigo-950/40 rounded-3xl p-6 md:p-8 border border-blue-200/80 dark:border-blue-900/60 shadow-xs space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-blue-500/20">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-extrabold text-[#12345B] dark:text-white">Community Complaint Records</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                                All incident reports, complaints, and investigations filed by <strong>{{ $resident->name }}</strong> are recorded and processed exclusively in the <strong>Complaint Management</strong> module.
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-end">
                        <a href="{{ route('admin.complaints.index', ['search' => $resident->name]) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-[#12345B] to-blue-700 hover:from-[#0B2340] hover:to-blue-800 text-white font-extrabold text-xs shadow-md shadow-blue-950/20 hover:scale-[1.02] transition-all">
                            <span>Open in Complaint Management</span>
                            <svg class="w-4 h-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
