<x-layouts.admin>
    <x-slot:header>Resident Profile: {{ $resident->name }}</x-slot:header>

    <div class="space-y-6">
        <div>
            <a href="{{ route('admin.residents.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back to Residents Directory</span>
            </a>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black text-[#12345B] dark:text-white">{{ $resident->name }}</h2>
                <div class="flex items-center gap-2">
                    @if($resident->account_status === 'pending')
                        <form method="POST" action="{{ route('admin.residents.approve', $resident->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold">
                                ✓ Approve Resident
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.residents.toggle-active', $resident->id) }}" onsubmit="return confirm('Change active status for this resident?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                            {{ $resident->account_status === 'inactive' ? 'Reactivate Account' : 'Deactivate Account' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resident Details Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Col: Details & Verification -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-4">
                    <div class="flex items-center gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 font-black text-lg flex items-center justify-center">
                            {{ $resident->initials() }}
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ $resident->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ ucfirst($resident->account_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-slate-400 block font-medium">Email Address</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ $resident->email }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Phone Number</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ $resident->phone }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Purok</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ $resident->purok?->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Complete Residence Address</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block leading-relaxed">{{ $resident->address }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Registered Since</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ $resident->created_at->format('F d, Y') }}</span>
                        </div>
                    </div>

                    @if($resident->avatar_path)
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-[11px] text-slate-400 block font-medium mb-2">Uploaded Verification Avatar / ID:</span>
                            <a href="{{ $resident->avatarUrl() }}" target="_blank" class="inline-block px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-bold hover:underline">
                                View Uploaded Document &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right 2 Cols: Resident's Submitted Complaints -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                    <h3 class="text-base font-extrabold text-[#12345B] dark:text-white mb-4">
                        Complaints Submitted by {{ $resident->name }} ({{ $resident->complaints->count() }})
                    </h3>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($resident->complaints as $c)
                            @php $b = $c->statusBadge(); @endphp
                            <div class="py-4 flex items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-xs text-blue-600">{{ $c->reference_number }}</span>
                                        <span class="text-xs text-slate-400">•</span>
                                        <span class="text-xs font-semibold text-slate-500">{{ $c->category?->name ?? 'General' }}</span>
                                    </div>
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-white mt-1">
                                        <a href="{{ route('admin.complaints.show', $c->id) }}" class="hover:text-blue-600">
                                            {{ $c->title }}
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">{{ $c->created_at->format('M d, Y') }}</span>
                                </div>
                                <span class="px-3 py-1 rounded-xl text-xs font-bold border {{ $b['bg'] }} {{ $b['text'] }} {{ $b['border'] }}">
                                    {{ $b['label'] }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-8 text-center">This resident has not submitted any complaints yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
