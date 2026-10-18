<x-layouts.admin>
    <x-slot:header>Complaints Queue Management</x-slot:header>

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Community Complaints Queue</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Filter, review AI suggestions, assign officials, and update complaint status</p>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <form method="GET" action="{{ route('admin.complaints.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-4">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search title, resident, or NPC-2026-XXXX..." 
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="sm:col-span-2">
                    <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted (New)</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="acknowledged" {{ request('status') === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="referred_to_responsible_office" {{ request('status') === 'referred_to_responsible_office' ? 'selected' : '' }}>Referred</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <select name="priority" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Priorities</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <select name="purok_id" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Puroks</option>
                        @foreach($puroks as $purok)
                            <option value="{{ $purok->id }}" {{ request('purok_id') == $purok->id ? 'selected' : '' }}>{{ $purok->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex gap-2">
                    <select name="category_id" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-bold transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 uppercase font-black tracking-wider text-[10px]">
                            <th class="p-4 pl-6">Reference</th>
                            <th class="p-4">Title & Description</th>
                            <th class="p-4">Resident</th>
                            <th class="p-4">Location</th>
                            <th class="p-4">Priority</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Date & Time Submitted</th>
                            <th class="p-4 pr-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($complaints as $c)
                            @php 
                                $b = $c->statusBadge(); 
                                $p = $c->priorityBadge();
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-4 pl-6 font-black text-blue-600 dark:text-blue-400">
                                    <a href="{{ route('admin.complaints.show', $c->id) }}" class="hover:underline">
                                        {{ $c->reference_number }}
                                    </a>
                                </td>
                                <td class="p-4 max-w-xs">
                                    <div class="font-bold text-slate-900 dark:text-white truncate">
                                        <a href="{{ route('admin.complaints.show', $c->id) }}" class="hover:text-blue-600">
                                            {{ $c->title }}
                                        </a>
                                    </div>
                                    <div class="text-[11px] truncate mt-0.5">
                                        @if($c->english_summary)
                                            <span class="font-extrabold text-blue-600 dark:text-blue-400">AI:</span> <span class="text-slate-600 dark:text-slate-300 font-medium">{{ $c->english_summary }}</span>
                                        @else
                                            <span class="text-slate-400">{{ $c->original_description }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $c->resident?->avatarUrl() }}" alt="{{ $c->resident?->name }}" class="w-8 h-8 rounded-xl object-cover shrink-0 ring-1 ring-slate-200 dark:ring-slate-700 bg-slate-100 dark:bg-slate-800">
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-800 dark:text-slate-200 truncate">
                                                {{ $c->resident?->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-[11px] text-slate-400">
                                                {{ $c->resident?->phone }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $c->purok?->name ?? 'Purok 5' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 truncate max-w-32">
                                        {{ $c->incident_location }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase border {{ $p['bg'] }} {{ $p['text'] }} {{ $p['border'] }}">
                                        {{ $p['label'] }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-xl text-[11px] font-bold border {{ $b['bg'] }} {{ $b['text'] }} {{ $b['border'] }}">
                                        {{ $b['label'] }}
                                    </span>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                        {{ $c->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('M d, Y') }}
                                    </div>
                                    <div class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/></svg>
                                        <span>{{ $c->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('h:i A') }}</span>
                                        <span class="text-slate-400 text-[10px]">({{ $c->created_at->diffForHumans() }})</span>
                                    </div>
                                    @if($c->incident_date)
                                        <div class="text-[10px] text-slate-400 mt-1">
                                            Incident: {{ $c->incident_date->format('M d, Y') }}@if($c->incident_time) • {{ \Carbon\Carbon::parse($c->incident_time)->format('h:i A') }}@endif
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 pr-6 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.complaints.show', $c->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-xs hover:shadow transition-all hover:scale-102 active:scale-95 shrink-0"
                                       title="View Complaint Details">
                                        <svg class="w-3.5 h-3.5 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>View Details</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center text-xs text-slate-400">
                                    No complaints found matching the filter criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $complaints->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
