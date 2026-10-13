<x-layouts.resident>
    <x-slot:title>Dashboard</x-slot:title>

    <div class="space-y-8 animate-fade-in-up">
        <!-- Civic Welcome & Quick Actions Bar -->
        <div class="bg-gradient-to-r from-[#12345B] via-[#0B2340] to-[#1E3A8A] rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden border border-blue-900/40">
            <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-10 w-40 h-40 bg-emerald-500/10 blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-400/20 text-blue-200 border border-blue-400/30 mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Official Resident Portal • Barangay North Poblacion
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight">
                        Maayong Adlaw, {{ Auth::user()->name }}!
                    </h2>
                    <p class="text-xs sm:text-sm text-blue-100/80 mt-1 max-w-xl">
                        Monitor your reported community incidents, track barangay resolutions, and communicate directly with your barangay leaders.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('resident.complaints.create') }}" 
                       class="px-5 py-3 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-extrabold text-xs shadow-lg shadow-blue-500/30 transition-all hover:scale-105 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Submit New Complaint</span>
                    </a>

                    <a href="{{ route('resident.chatbot') }}" 
                       class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 backdrop-blur-md transition-all flex items-center gap-2 hover:scale-105">
                        <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                        </svg>
                        <span>Ask AI Assistant</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Top Metric Cards (Elevated with Hover-Lift, Soft Glow & Lucide SVG Icons) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: My Complaints -->
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift flex flex-col justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-[#12345B] dark:text-white leading-none">
                            {{ $totalComplaints }}
                        </div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">
                            My Complaints
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 mt-6 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Total community reports submitted
                </div>
            </div>

            <!-- Card 2: In Progress -->
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift flex flex-col justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-amber-500 dark:text-amber-400 leading-none">
                            {{ $inProgressCount }}
                        </div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">
                            In Progress
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 mt-6 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Under active official handling
                </div>
            </div>

            <!-- Card 3: Resolved -->
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift flex flex-col justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 leading-none">
                            {{ $resolvedCount }}
                        </div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">
                            Resolved
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 mt-6 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Successfully cleared by Barangay
                </div>
            </div>

            <!-- Card 4: Rejected / Closed -->
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift flex flex-col justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-[#12345B] dark:text-white leading-none">
                            {{ $rejectedCount }}
                        </div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">
                            Closed / Rejected
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 mt-6 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Dismissed or resolved cases
                </div>
            </div>
        </div>

        <!-- Recent Complaints Section -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift">
            <div class="flex items-center justify-between pb-6 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-lg md:text-xl font-black text-[#12345B] dark:text-white">
                            Recent Community Incidents
                        </h2>
                        <p class="text-xs text-slate-400">Real-time status updates from Barangay Secretariat</p>
                    </div>
                </div>
                <a href="{{ route('resident.complaints.index') }}" class="text-xs md:text-sm font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-1 transition-colors">
                    <span>View All</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recentComplaints as $complaint)
                    @php $badge = $complaint->statusBadge(); @endphp
                    <a href="{{ route('resident.complaints.show', $complaint->id) }}" 
                       class="py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 rounded-2xl px-3 transition-colors group">
                        
                        <!-- Left: Status Icon + Complaint Details -->
                        <div class="flex items-start md:items-center gap-4 flex-1 min-w-0">
                            @if($complaint->status === 'resolved')
                                <div class="w-11 h-11 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @elseif($complaint->status === 'in_progress')
                                <div class="w-11 h-11 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @elseif($complaint->status === 'submitted')
                                <div class="w-11 h-11 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm md:text-base font-extrabold text-[#12345B] dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $complaint->title }}
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
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5 italic">
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 not-italic">🇵🇭 Dialect Input:</span> "{{ Str::limit($complaint->original_description, 70) }}"
                                    </p>
                                @endif

                                <div class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-2 mt-1">
                                    <span class="font-mono font-bold text-blue-600 dark:text-blue-400">{{ $complaint->reference_number }}</span>
                                    <span>•</span>
                                    <span>{{ $complaint->purok?->name ?? 'Purok 5' }}</span>
                                    <span>•</span>
                                    <span>{{ $complaint->category?->name ?? 'General' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Status Badge Pill + Incident Date -->
                        <div class="flex items-center gap-5 shrink-0 self-end md:self-auto pl-14 md:pl-0">
                            <span class="px-4 py-1.5 rounded-xl text-xs font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }} shadow-xs">
                                {{ $badge['label'] }}
                            </span>

                            <span class="text-xs md:text-sm font-medium text-slate-500 dark:text-slate-400 w-28 text-right">
                                {{ $complaint->incident_date ? $complaint->incident_date->format('F j, Y') : $complaint->created_at->format('F j, Y') }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 rounded-3xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">No complaints submitted yet.</p>
                        <a href="{{ route('resident.complaints.create') }}" class="inline-block mt-3 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 shadow-md">
                            Submit Your First Complaint &rarr;
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick AI Assistant Banner & Latest Announcements Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- AI Barangay Assistant Banner -->
            <div class="lg:col-span-1 bg-gradient-to-br from-[#12345B] to-[#0B2340] rounded-3xl p-6 md:p-8 text-white flex flex-col justify-between shadow-md relative overflow-hidden group hover-lift">
                <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-blue-500/20 rounded-full blur-2xl"></div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-400/20 text-blue-200 text-xs font-bold mb-4 border border-blue-400/30">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        AI Civic Assistant 24/7
                    </div>
                    <h3 class="text-xl font-black leading-tight text-white">
                        North Poblacion AI Barangay Assistant
                    </h3>
                    <p class="text-xs text-blue-100/80 mt-2 leading-relaxed">
                        Pangutana bahin sa imong reklamo, iskedyul sa basura, clearance, o serbisyo sa barangay gamit ang Bisaya, Tagalog, o English.
                    </p>
                </div>
                <div class="mt-6">
                    <a href="{{ route('resident.chatbot') }}" class="w-full py-3.5 px-4 bg-white text-[#12345B] hover:bg-blue-50 rounded-2xl text-xs font-extrabold flex items-center justify-center gap-2 transition-all shadow-md group-hover:scale-105">
                        <span>Chat With AI Assistant</span>
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- Announcements & Events -->
            <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between hover-lift">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </span>
                            <h2 class="text-base font-extrabold text-[#12345B] dark:text-white">
                                Community Announcements & Events
                            </h2>
                        </div>
                        <a href="{{ route('resident.announcements.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            View All Updates &rarr;
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
