<x-layouts.resident>
    <x-slot:title>Dashboard</x-slot:title>

    <div class="space-y-8">
        <!-- 4 Top Metric Cards (Directly matching User Screenshot) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: My Complaints -->
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-[#12345B] dark:text-white leading-none">
                            {{ $totalComplaints }}
                        </div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1">
                            My Complaints
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 mt-6 font-medium">
                    Total complaints you have submitted
                </div>
            </div>

            <!-- Card 2: In Progress -->
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 dark:text-amber-400 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-[#12345B] dark:text-white leading-none">
                            {{ $inProgressCount }}
                        </div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1">
                            In Progress
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 mt-6 font-medium">
                    Complaints currently being processed
                </div>
            </div>

            <!-- Card 3: Resolved -->
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-[#12345B] dark:text-white leading-none">
                            {{ $resolvedCount }}
                        </div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1">
                            Resolved
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 mt-6 font-medium">
                    Complaints that have been resolved
                </div>
            </div>

            <!-- Card 4: Rejected -->
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-[#12345B] dark:text-white leading-none">
                            {{ $rejectedCount }}
                        </div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1">
                            Rejected
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 mt-6 font-medium">
                    Complaints that were rejected
                </div>
            </div>
        </div>

        <!-- Recent Complaints Section (Directly matching User Screenshot) -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between pb-6 border-b border-slate-100 dark:border-slate-800">
                <h2 class="text-lg md:text-xl font-extrabold text-[#12345B] dark:text-white">
                    Recent Complaints
                </h2>
                <a href="{{ route('resident.complaints.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors">
                    View All
                </a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recentComplaints as $complaint)
                    @php $badge = $complaint->statusBadge(); @endphp
                    <a href="{{ route('resident.complaints.show', $complaint->id) }}" 
                       class="py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 rounded-2xl px-3 transition-colors group">
                        
                        <!-- Left: Status Circle Icon + Complaint Title in Cebuano/English -->
                        <div class="flex items-start md:items-center gap-4 flex-1 min-w-0">
                            @if($complaint->status === 'resolved')
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                                    </svg>
                                </div>
                            @elseif($complaint->status === 'in_progress')
                                <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 dark:text-amber-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/>
                                    </svg>
                                </div>
                            @elseif($complaint->status === 'submitted')
                                <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="min-w-0">
                                <h3 class="text-sm md:text-base font-bold text-[#12345B] dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                    {{ $complaint->title }}
                                </h3>
                                <div class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-2 mt-0.5">
                                    <span>{{ $complaint->reference_number }}</span>
                                    <span>•</span>
                                    <span>{{ $complaint->purok?->name ?? 'Purok 5' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Status Badge Pill + Incident Date -->
                        <div class="flex items-center gap-6 shrink-0 self-end md:self-auto pl-14 md:pl-0">
                            <!-- Status Pill (Matching User Screenshot style) -->
                            <span class="px-4 py-1.5 rounded-xl text-xs font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                                {{ $badge['label'] }}
                            </span>

                            <!-- Formatted Date (e.g. July 1, 2026) -->
                            <span class="text-xs md:text-sm font-medium text-slate-500 dark:text-slate-400 w-28 text-right">
                                {{ $complaint->incident_date ? $complaint->incident_date->format('F j, Y') : $complaint->created_at->format('F j, Y') }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 rounded-3xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">No complaints submitted yet.</p>
                        <a href="{{ route('resident.complaints.create') }}" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700">Submit Your First Complaint</a>
                    </div>
                @endforelse
            </div>

            <!-- Footer note matching screenshot -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-400 dark:text-slate-500">
                Showing complaints from July 1, 2026 to July 13, 2026 only.
            </div>
        </div>

        <!-- Quick AI Assistant Banner & Latest Announcements Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- AI Barangay Assistant Banner -->
            <div class="lg:col-span-1 bg-gradient-to-br from-[#12345B] to-[#0B2340] rounded-3xl p-6 text-white flex flex-col justify-between shadow-sm relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-400/20 text-blue-200 text-xs font-bold mb-4">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        AI Powered 24/7
                    </div>
                    <h3 class="text-xl font-extrabold leading-tight">North Poblacion AI Barangay Assistant</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Pangutana bahin sa imong reklamo, iskedyul sa basura, clearance, o mga serbisyo sa barangay sa Bisaya, Tagalog, o English.
                    </p>
                </div>
                <div class="mt-6">
                    <a href="{{ route('resident.chatbot') }}" class="w-full py-3 px-4 bg-white text-[#12345B] hover:bg-blue-50 rounded-2xl text-xs font-bold flex items-center justify-center gap-2 transition-colors">
                        <span>Chat With AI Assistant</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- Announcements & Events -->
            <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h2 class="text-base font-extrabold text-[#12345B] dark:text-white">
                            Community Announcements & Events
                        </h2>
                        <a href="{{ route('resident.announcements.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            All Updates
                        </a>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800 mt-2">
                        @forelse($latestAnnouncements as $announcement)
                            <div class="py-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 mb-1">
                                        {{ $announcement->category }}
                                    </span>
                                    <h4 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white hover:text-blue-600 transition-colors">
                                        <a href="{{ route('resident.announcements.show', $announcement->id) }}">{{ $announcement->title }}</a>
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mt-0.5">{{ $announcement->description }}</p>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400 shrink-0">
                                    {{ $announcement->published_at ? $announcement->published_at->format('M d') : 'Recent' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-4 text-center">No current public announcements.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.resident>
