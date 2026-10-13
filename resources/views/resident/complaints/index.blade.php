<x-layouts.resident>
    <x-slot:title>My Complaints</x-slot:title>

    <div class="space-y-6 animate-fade-in-up">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white">My Complaints Directory</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Track and monitor all your community incident submissions</p>
            </div>
            <a href="{{ route('resident.complaints.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs shadow-md shadow-blue-600/30 transition-all hover:scale-105 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Submit New Complaint</span>
            </a>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-5 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift">
            <form method="GET" action="{{ route('resident.complaints.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-6">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by title, location, or reference (NPC-2026-XXXX)..."
                               class="w-full pl-9 pr-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                        <option value="">All Statuses</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Pending / Submitted</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="sm:col-span-3 flex gap-2">
                    <select name="category_id" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-bold transition-all shadow-sm">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Complaints List -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($complaints as $complaint)
                    @php $badge = $complaint->statusBadge(); @endphp
                    <div class="py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-extrabold text-xs text-blue-600 dark:text-blue-400 font-mono">{{ $complaint->reference_number }}</span>
                                    <span class="text-xs text-slate-300">•</span>
                                    <span class="text-xs text-slate-500 font-semibold">{{ $complaint->category?->name ?? 'General' }}</span>
                                    <span class="text-xs text-slate-300">•</span>
                                    <span class="text-xs text-slate-500">{{ $complaint->purok?->name ?? 'Purok 5' }}</span>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap mt-1">
                                    <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">
                                        <a href="{{ route('resident.complaints.show', $complaint->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $complaint->title }}
                                        </a>
                                    </h3>
                                    @php
                                        $priorityStyles = [
                                            'urgent' => 'bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 border-rose-300 dark:border-rose-800',
                                            'high' => 'bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border-amber-300 dark:border-amber-800',
                                            'medium' => 'bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-800',
                                            'low' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700',
                                        ];
                                        $pClass = $priorityStyles[$complaint->final_priority] ?? $priorityStyles['medium'];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $pClass }} {{ $complaint->final_priority === 'urgent' ? 'animate-pulse' : '' }}">
                                        {{ $complaint->final_priority }}
                                    </span>
                                </div>
                                @if($complaint->original_description && in_array($complaint->original_language, ['ceb', 'fil', 'tgl']))
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mt-1 italic">
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 not-italic">🇵🇭 Dialect Input:</span> "{{ $complaint->original_description }}"
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4 self-end md:self-auto shrink-0 pl-14 md:pl-0">
                            <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }} shadow-xs">
                                {{ $badge['label'] }}
                            </span>
                            <span class="text-xs font-semibold text-slate-400 min-w-24 text-right">
                                {{ $complaint->incident_date ? $complaint->incident_date->format('M d, Y') : $complaint->created_at->format('M d, Y') }}
                            </span>
                            <a href="{{ route('resident.complaints.show', $complaint->id) }}" 
                               class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/50 hover:text-blue-600 transition-colors"
                               title="View Details">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-xs">
                        No complaints found matching your filter criteria.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                {{ $complaints->links() }}
            </div>
        </div>
    </div>
</x-layouts.resident>
