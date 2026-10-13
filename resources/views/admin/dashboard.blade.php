<x-layouts.admin>
    <x-slot:header>Official Administrator Dashboard</x-slot:header>

    @php
        $pendingComplaintsTotal = $newComplaints + $underReview;
        $urgentTotal = $urgentComplaints + $highPriorityComplaints;
        $resolutionRate = $totalComplaints > 0 ? round(($resolvedComplaints / $totalComplaints) * 100, 1) : 0;
    @endphp

    <div class="space-y-8">
        <!-- 1. Executive Civic Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0B2340] via-[#12345B] to-[#1E3A8A] text-white p-6 sm:p-8 shadow-xl border border-blue-900/40">
            <!-- Decorative Background Patterns -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-10 w-48 h-48 rounded-full bg-emerald-500/10 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live Civic Command Center
                        </span>
                        <span class="text-xs text-blue-200/70 hidden sm:inline">•</span>
                        <span class="text-xs text-blue-200/80 font-medium hidden sm:inline" id="live-datetime">
                            Barangay North Poblacion, Maramag, Bukidnon
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Mabuhay, {{ Auth::user()->name }}
                    </h2>

                    <p class="text-xs sm:text-sm text-blue-100/80 max-w-2xl leading-relaxed">
                        Welcome to the official incident resolution desk. You currently have 
                        <strong class="text-amber-300 font-extrabold underline decoration-amber-400/50 underline-offset-2">{{ $pendingComplaintsTotal }} complaints</strong> 
                        awaiting official triage and 
                        <strong class="text-rose-300 font-extrabold">{{ $urgentTotal }} urgent safety alerts</strong>.
                    </p>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a href="{{ route('admin.complaints.index') }}" 
                       class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/15 backdrop-blur-md transition-all flex items-center gap-2 hover:scale-[1.02]">
                        <svg class="w-4 h-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Full Queue ({{ $totalComplaints }})</span>
                    </a>

                    <a href="{{ route('admin.residents.pending') }}" 
                       class="px-4 py-2.5 rounded-2xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-200 text-xs font-bold border border-amber-500/30 backdrop-blur-md transition-all flex items-center gap-2 hover:scale-[1.02]">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Pending Residents ({{ $pendingApprovals }})</span>
                    </a>

                    <a href="{{ route('admin.reports.export') }}" 
                       class="px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-900/30 transition-all flex items-center gap-2 hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Export CSV</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Top Stats Row: Key Barangay KPIs (6 Cards) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-5">
            <!-- Total Complaints -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total</span>
                    <span class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-black text-[#12345B] dark:text-white">{{ $totalComplaints }}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Recorded Cases</div>
                </div>
            </div>

            <!-- Pending / New -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-amber-200/80 dark:border-amber-900/50 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Pending Action</span>
                    <span class="p-2 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-black text-amber-600 dark:text-amber-400">{{ $pendingComplaintsTotal }}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $newComplaints }} new • {{ $underReview }} in review</div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">In Progress</span>
                    <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-black text-[#12345B] dark:text-white">{{ $inProgress }}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Active Handling</div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-emerald-200/80 dark:border-emerald-900/50 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Resolved</span>
                    <span class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $resolvedComplaints }}</div>
                    <div class="text-[11px] text-emerald-600/80 font-semibold mt-0.5">{{ $resolutionRate }}% success rate</div>
                </div>
            </div>

            <!-- Rejected / Dismissed -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Rejected</span>
                    <span class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-slate-200">{{ $rejectedComplaints }}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Non-jurisdiction/closed</div>
                </div>
            </div>

            <!-- Urgent & High Alerts -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-rose-200/80 dark:border-rose-900/50 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Urgent Alerts</span>
                    <span class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-black text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                        {{ $urgentTotal }}
                        @if($urgentTotal > 0)
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                        @endif
                    </div>
                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $urgentComplaints }} urgent • {{ $highPriorityComplaints }} high</div>
                </div>
            </div>
        </div>

        <!-- 3. Chart Visualizations Grid (Real Database Query Data) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Complaints by Category -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-[#12345B] dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            Complaints by Civic Category
                        </h3>
                        <p class="text-xs text-slate-400">Distribution across municipal service areas</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400">{{ $categoriesData->sum('count') }} total</span>
                </div>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Complaints by Status -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-[#12345B] dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Resolution Workflow Progress
                        </h3>
                        <p class="text-xs text-slate-400">Status progression pipeline</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400">{{ $totalComplaints }} cases</span>
                </div>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. CORE COMMAND SECTION: Complaints Resolution Center with Direct Manual Actions -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden space-y-0">
            <!-- Section Header & Controls -->
            <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-800">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <span class="p-2 rounded-xl bg-blue-600 text-white shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-xl font-extrabold text-[#12345B] dark:text-white">
                                    Complaints Resolution Command Center
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    View incoming reports, review AI triage, and manually change status to 
                                    <strong class="text-emerald-600 dark:text-emerald-400 font-bold">Resolved</strong> or 
                                    <strong class="text-rose-600 dark:text-rose-400 font-bold">Rejected</strong> with official remarks.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Search Input & Purok Filter -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative w-full sm:w-64">
                            <input type="text" 
                                   id="complaintSearchInput"
                                   placeholder="Filter reference, title, resident..." 
                                   class="w-full pl-9 pr-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800 dark:text-slate-200">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <select id="complaintPurokFilter" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800 dark:text-slate-200 font-medium">
                            <option value="">All Puroks</option>
                            @foreach($puroks as $purok)
                                <option value="{{ $purok->id }}">{{ $purok->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status Filter Tabs -->
                <div class="flex flex-wrap items-center gap-2 mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" 
                            onclick="setFilterTab('all')" 
                            id="tab-all"
                            class="status-tab-btn active px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-blue-600 text-white shadow-xs">
                        <span>All Cases</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/20 text-white">{{ $recentComplaints->count() }}</span>
                    </button>

                    <button type="button" 
                            onclick="setFilterTab('pending')" 
                            id="tab-pending"
                            class="status-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Pending / Review</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300">{{ $pendingComplaintsTotal }}</span>
                    </button>

                    <button type="button" 
                            onclick="setFilterTab('in_progress')" 
                            id="tab-in_progress"
                            class="status-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        <span>In Progress</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300">{{ $inProgress }}</span>
                    </button>

                    <button type="button" 
                            onclick="setFilterTab('resolved')" 
                            id="tab-resolved"
                            class="status-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Resolved</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300">{{ $resolvedComplaints }}</span>
                    </button>

                    <button type="button" 
                            onclick="setFilterTab('rejected')" 
                            id="tab-rejected"
                            class="status-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span>Rejected</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300">{{ $rejectedComplaints }}</span>
                    </button>
                </div>
            </div>

            <!-- Table of Complaints with Manual Action Buttons -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs" id="complaintsTable">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/50 text-slate-400 uppercase font-black tracking-wider text-[10px]">
                            <th class="p-4 pl-6">Reference & Date</th>
                            <th class="p-4">Incident Title & Citizen Remarks</th>
                            <th class="p-4">Resident & Purok</th>
                            <th class="p-4">Priority / Triage</th>
                            <th class="p-4">Current Status</th>
                            <th class="p-4 pr-6 text-right min-w-[240px]">Manual Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800" id="complaintsTableBody">
                        @forelse($recentComplaints as $complaint)
                            @php 
                                $b = $complaint->statusBadge(); 
                                $p = $complaint->priorityBadge();
                                $isPending = in_array($complaint->status, ['submitted', 'under_review']);
                                $isResolved = $complaint->status === 'resolved';
                                $isRejected = $complaint->status === 'rejected';
                            @endphp
                            <tr class="complaint-row hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors"
                                data-status="{{ $complaint->status }}"
                                data-purok="{{ $complaint->purok_id }}"
                                data-search="{{ strtolower($complaint->reference_number . ' ' . $complaint->title . ' ' . ($complaint->resident?->name ?? '') . ' ' . ($complaint->purok?->name ?? '')) }}">
                                
                                <!-- Reference & Date -->
                                <td class="p-4 pl-6 align-top">
                                    <div class="flex items-center gap-1.5 font-black text-blue-600 dark:text-blue-400">
                                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="hover:underline flex items-center gap-1">
                                            {{ $complaint->reference_number }}
                                        </a>
                                        <button type="button" 
                                                onclick="copyRef('{{ $complaint->reference_number }}', this)" 
                                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-0.5 rounded"
                                                title="Copy Reference Number">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-1 whitespace-nowrap">
                                        {{ $complaint->created_at->format('M d, Y • h:i A') }}
                                    </div>
                                    @if($complaint->attachments->count() > 0)
                                        <div class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] font-semibold text-slate-500">
                                            <span>📎</span> {{ $complaint->attachments->count() }} photo(s)
                                        </div>
                                    @endif
                                </td>

                                <!-- Title & Description -->
                                <td class="p-4 align-top max-w-sm">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                            {{ $complaint->category?->name ?? 'General' }}
                                        </span>
                                        @if($complaint->original_language && $complaint->original_language !== 'English')
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-300">
                                                {{ $complaint->original_language }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="font-extrabold text-slate-900 dark:text-white line-clamp-1">
                                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="hover:text-blue-600 transition-colors">
                                            {{ $complaint->title }}
                                        </a>
                                    </div>

                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 mt-1 leading-relaxed">
                                        {{ $complaint->original_description }}
                                    </div>
                                </td>

                                <!-- Resident & Purok -->
                                <td class="p-4 align-top">
                                    <div class="font-extrabold text-slate-800 dark:text-slate-200">
                                        {{ $complaint->resident?->name ?? 'Resident Citizen' }}
                                    </div>
                                    <div class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 mt-0.5">
                                        {{ $complaint->purok?->name ?? 'Barangay Center' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $complaint->resident?->phone ?? 'No phone' }}
                                    </div>
                                </td>

                                <!-- Priority / Triage -->
                                <td class="p-4 align-top whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-black uppercase border {{ $p['bg'] }} {{ $p['text'] }} {{ $p['border'] }} {{ $complaint->final_priority === 'urgent' ? 'animate-pulse' : '' }}">
                                        @if($complaint->final_priority === 'urgent')
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        @endif
                                        {{ $p['label'] }}
                                    </span>
                                </td>

                                <!-- Current Status -->
                                <td class="p-4 align-top whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-xl text-[11px] font-bold border inline-flex items-center gap-1.5 {{ $b['bg'] }} {{ $b['text'] }} {{ $b['border'] }}">
                                        @if($isResolved)
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($isRejected)
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @elseif($isPending)
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                        @endif
                                        {{ $b['label'] }}
                                    </span>
                                </td>

                                <!-- MANUAL ACTIONS: Quick Put to Resolved / Reject (Core Requirement) -->
                                <td class="p-4 pr-6 align-top text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Quick Mark Resolved Button -->
                                        @if(! $isResolved)
                                            <button type="button"
                                                    onclick="openResolveModal('{{ $complaint->id }}', '{{ $complaint->reference_number }}', '{{ addslashes($complaint->title) }}', '{{ addslashes($complaint->resident?->name ?? 'Citizen') }}')"
                                                    class="px-2.5 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/80 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 font-extrabold text-[11px] inline-flex items-center gap-1 transition-all hover:scale-105 shadow-xs"
                                                    title="Mark this complaint as Resolved">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Resolve</span>
                                            </button>
                                        @endif

                                        <!-- Quick Mark Rejected Button -->
                                        @if(! $isRejected)
                                            <button type="button"
                                                    onclick="openRejectModal('{{ $complaint->id }}', '{{ $complaint->reference_number }}', '{{ addslashes($complaint->title) }}', '{{ addslashes($complaint->resident?->name ?? 'Citizen') }}')"
                                                    class="px-2.5 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 dark:hover:bg-rose-900/80 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-800 font-extrabold text-[11px] inline-flex items-center gap-1 transition-all hover:scale-105 shadow-xs"
                                                    title="Reject or dismiss this complaint">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                <span>Reject</span>
                                            </button>
                                        @endif

                                        <!-- Change Status Dropdown Trigger -->
                                        <button type="button"
                                                onclick="openStatusModal('{{ $complaint->id }}', '{{ $complaint->reference_number }}', '{{ $complaint->status }}', '{{ addslashes($complaint->title) }}')"
                                                class="p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-colors"
                                                title="Change to In Progress, Under Review, etc.">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </button>

                                        <!-- Review Details Link -->
                                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" 
                                           class="p-1.5 rounded-xl bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 text-blue-600 dark:text-blue-400 font-bold text-xs transition-colors"
                                           title="Open Complete Review Page">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-xs text-slate-400">
                                    No complaints recorded yet in Barangay North Poblacion.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50/70 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500">
                <span>Showing latest {{ $recentComplaints->count() }} incidents recorded in North Poblacion database</span>
                <a href="{{ route('admin.complaints.index') }}" class="font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    View Complete Paginated Queue &rarr;
                </a>
            </div>
        </div>

        <!-- 5. Pending Resident Applications Action Queue -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-amber-500 text-slate-950 font-black text-xs shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">
                            Resident Registration Approvals Queue
                        </h3>
                        <p class="text-xs text-slate-400">Review pending citizens before granting access to report complaints</p>
                    </div>
                </div>

                <a href="{{ route('admin.residents.pending') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    View All Pending ({{ $pendingApprovals }}) &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($pendingResidentsList as $resident)
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3 hover:border-blue-400 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-xs">
                                {{ $resident->initials() }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-extrabold text-slate-900 dark:text-white truncate">{{ $resident->name }}</h4>
                                <div class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold">{{ $resident->purok?->name ?? 'Purok' }}</div>
                                <div class="text-[10px] text-slate-400 truncate">{{ $resident->email }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <form method="POST" action="{{ route('admin.residents.approve', $resident->id) }}">
                                @csrf
                                <button type="submit" 
                                        class="px-2.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs" 
                                        title="Approve Resident">
                                    Approve ✓
                                </button>
                            </form>
                            <a href="{{ route('admin.residents.show', $resident->id) }}" 
                               class="p-1.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-300 transition-colors" 
                               title="Inspect Full Profile">
                                &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-6 text-center text-xs text-slate-400">
                        No pending resident registrations currently awaiting review.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: QUICK RESOLVE COMPLAINT (WITH PRE-MADE REMARKS CHIPS)             -->
    <!-- ========================================================================= -->
    <div id="resolveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md hidden transition-opacity duration-300">
        <div class="bg-white dark:bg-[#111C33] rounded-3xl max-w-lg w-full border border-emerald-500/30 shadow-2xl p-6 sm:p-8 transform transition-transform duration-300 scale-95" id="resolveModalCard">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-2xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black">
                        ✓
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">
                            Mark Complaint as Resolved
                        </h3>
                        <p class="text-xs text-slate-400" id="resolveModalSubtitle">Reference</p>
                    </div>
                </div>
                <button type="button" onclick="closeResolveModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">
                    ✕
                </button>
            </div>

            <form id="resolveForm" method="POST" action="" class="space-y-4 mt-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="resolved">

                <div class="p-3.5 rounded-2xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200/60 dark:border-emerald-900/40 text-xs text-emerald-900 dark:text-emerald-300">
                    <strong>Incident:</strong> <span id="resolveModalTitle" class="font-bold"></span>
                </div>

                <!-- Quick Remarks Chips -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Quick Official Resolution Tags (Click to Insert)
                    </label>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="insertResolveText('Field inspection conducted and issue resolved on-site by Barangay Maintenance Crew.')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-emerald-100 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            ⚡ On-Site Inspection Resolved
                        </button>
                        <button type="button" onclick="insertResolveText('Clean-up drive completed in coordination with Purok leaders.')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-emerald-100 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            🧹 Clean-Up Drive Completed
                        </button>
                        <button type="button" onclick="insertResolveText('Coordinated and resolved with Maramag Municipal Office & Public Works.')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-emerald-100 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            🏛 Coordinated with LGU/Municipal
                        </button>
                        <button type="button" onclick="insertResolveText('Settled amicably through Barangay Lupon Peace & Order conciliation.')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-emerald-100 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            ⚖ Lupon Settlement Concluded
                        </button>
                    </div>
                </div>

                <!-- Public Remarks -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Official Resolution Remarks <span class="text-emerald-600 font-normal">(Sent to Citizen Notification)</span>
                    </label>
                    <textarea name="public_remarks" 
                              id="resolvePublicRemarks"
                              rows="3" 
                              class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-slate-100 leading-relaxed"
                              placeholder="Describe the action taken and solution provided..."></textarea>
                </div>

                <!-- Internal Notes -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Internal Administrative Note <span class="text-slate-400 font-normal">(Confidential to Barangay Staff)</span>
                    </label>
                    <input type="text" 
                           name="internal_notes" 
                           placeholder="Optional private case note (e.g. dispatched team leader name)..." 
                           class="w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <!-- Form Action Buttons -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeResolveModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-extrabold shadow-md shadow-emerald-900/30 transition-all flex items-center gap-1.5">
                        <span>Confirm & Mark Resolved ✓</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: QUICK REJECT / DISMISS COMPLAINT (WITH REASON CHIPS)             -->
    <!-- ========================================================================= -->
    <div id="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md hidden transition-opacity duration-300">
        <div class="bg-white dark:bg-[#111C33] rounded-3xl max-w-lg w-full border border-rose-500/30 shadow-2xl p-6 sm:p-8 transform transition-transform duration-300 scale-95" id="rejectModalCard">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-2xl bg-rose-100 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black">
                        ✕
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">
                            Reject or Dismiss Complaint
                        </h3>
                        <p class="text-xs text-slate-400" id="rejectModalSubtitle">Reference</p>
                    </div>
                </div>
                <button type="button" onclick="closeRejectModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">
                    ✕
                </button>
            </div>

            <form id="rejectForm" method="POST" action="" class="space-y-4 mt-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">

                <div class="p-3.5 rounded-2xl bg-rose-50/70 dark:bg-rose-950/30 border border-rose-200/60 dark:border-rose-900/40 text-xs text-rose-900 dark:text-rose-300">
                    <strong>Incident:</strong> <span id="rejectModalTitle" class="font-bold"></span>
                </div>

                <!-- Quick Rejection Reason Chips -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Common Rejection Reasons (Click to Insert)
                    </label>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="insertRejectText('Duplicate complaint: This incident is already currently undergoing active resolution under an existing ticket.')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-rose-100 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            📄 Duplicate Submission
                        </button>
                        <button type="button" onclick="insertRejectText('Outside Territorial Jurisdiction: The reported location falls outside the boundary of Barangay North Poblacion.')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-rose-100 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            🗺 Outside Jurisdiction
                        </button>
                        <button type="button" onclick="insertRejectText('Insufficient incident details or unverifiable contact information provided.')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-rose-100 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            ❓ Insufficient Information
                        </button>
                        <button type="button" onclick="insertRejectText('Private civil dispute not falling under public community complaint jurisdiction.')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-rose-100 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            ⛔ Private Civil Matter
                        </button>
                    </div>
                </div>

                <!-- Rejection Reason Remarks -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Official Reason for Rejection <span class="text-rose-600 font-normal">(Sent to Citizen Notification)</span>
                    </label>
                    <textarea name="public_remarks" 
                              id="rejectPublicRemarks"
                              rows="3" 
                              required
                              class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none text-slate-800 dark:text-slate-100 leading-relaxed"
                              placeholder="Please explain clearly to the citizen why this complaint is being rejected or closed..."></textarea>
                </div>

                <!-- Internal Notes -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Internal Administrative Note <span class="text-slate-400 font-normal">(Confidential)</span>
                    </label>
                    <input type="text" 
                           name="internal_notes" 
                           placeholder="Optional administrative reason..." 
                           class="w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
                </div>

                <!-- Form Action Buttons -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white text-xs font-extrabold shadow-md shadow-rose-900/30 transition-all flex items-center gap-1.5">
                        <span>Confirm & Reject Complaint ✕</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: QUICK STATUS CHANGER (UNDER REVIEW, IN PROGRESS, ETC.)           -->
    <!-- ========================================================================= -->
    <div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md hidden transition-opacity duration-300">
        <div class="bg-white dark:bg-[#111C33] rounded-3xl max-w-lg w-full border border-blue-500/30 shadow-2xl p-6 sm:p-8 transform transition-transform duration-300 scale-95" id="statusModalCard">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-2xl bg-blue-100 dark:bg-blue-950/80 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black">
                        ⚙
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">
                            Update Complaint Status
                        </h3>
                        <p class="text-xs text-slate-400" id="statusModalSubtitle">Reference</p>
                    </div>
                </div>
                <button type="button" onclick="closeStatusModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">
                    ✕
                </button>
            </div>

            <form id="statusForm" method="POST" action="" class="space-y-4 mt-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Select New Official Status
                    </label>
                    <select name="status" id="statusModalSelect" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 dark:text-slate-100 font-bold">
                        <option value="submitted">Submitted (New / Pending)</option>
                        <option value="under_review">Under Review (Assigned to Official)</option>
                        <option value="acknowledged">Acknowledged by Barangay</option>
                        <option value="in_progress">In Progress (Active Action Being Taken)</option>
                        <option value="referred_to_responsible_office">Referred to Responsible Municipal Office</option>
                        <option value="resolved">Resolved (Action Completed)</option>
                        <option value="closed">Closed (Concluded)</option>
                        <option value="rejected">Rejected (Dismissed)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Citizen Feedback Remarks <span class="text-slate-400 font-normal">(Visible to Resident)</span>
                    </label>
                    <textarea name="public_remarks" 
                              rows="3" 
                              class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 dark:text-slate-100"
                              placeholder="Provide status updates for the citizen..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Internal Administrative Note <span class="text-slate-400 font-normal">(Confidential)</span>
                    </label>
                    <input type="text" 
                           name="internal_notes" 
                           placeholder="Internal assignment or inspection notes..." 
                           class="w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-md shadow-blue-900/30 transition-all">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Modal Control Functions
        function openResolveModal(id, ref, title, resident) {
            const form = document.getElementById('resolveForm');
            form.action = `/admin/complaints/${id}/status`;
            document.getElementById('resolveModalSubtitle').innerText = `${ref} • Submitted by ${resident}`;
            document.getElementById('resolveModalTitle').innerText = title;
            document.getElementById('resolvePublicRemarks').value = 'Field inspection conducted and issue resolved on-site by Barangay Maintenance Crew.';
            
            const modal = document.getElementById('resolveModal');
            const card = document.getElementById('resolveModalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function closeResolveModal() {
            const modal = document.getElementById('resolveModal');
            const card = document.getElementById('resolveModalCard');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 150);
        }

        function insertResolveText(text) {
            document.getElementById('resolvePublicRemarks').value = text;
        }

        function openRejectModal(id, ref, title, resident) {
            const form = document.getElementById('rejectForm');
            form.action = `/admin/complaints/${id}/status`;
            document.getElementById('rejectModalSubtitle').innerText = `${ref} • Submitted by ${resident}`;
            document.getElementById('rejectModalTitle').innerText = title;
            document.getElementById('rejectPublicRemarks').value = '';
            
            const modal = document.getElementById('rejectModal');
            const card = document.getElementById('rejectModalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            const card = document.getElementById('rejectModalCard');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 150);
        }

        function insertRejectText(text) {
            document.getElementById('rejectPublicRemarks').value = text;
        }

        function openStatusModal(id, ref, currentStatus, title) {
            const form = document.getElementById('statusForm');
            form.action = `/admin/complaints/${id}/status`;
            document.getElementById('statusModalSubtitle').innerText = `${ref} • ${title}`;
            document.getElementById('statusModalSelect').value = currentStatus;
            
            const modal = document.getElementById('statusModal');
            const card = document.getElementById('statusModalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            const card = document.getElementById('statusModalCard');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 150);
        }

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeResolveModal();
                closeRejectModal();
                closeStatusModal();
            }
        });

        // Copy Reference Number
        function copyRef(text, btn) {
            navigator.clipboard.writeText(text);
            const orig = btn.innerHTML;
            btn.innerHTML = '✓';
            setTimeout(() => btn.innerHTML = orig, 1500);
        }

        // Tab & Search Filtering
        let activeTab = 'all';

        function setFilterTab(tab) {
            activeTab = tab;
            document.querySelectorAll('.status-tab-btn').forEach(b => {
                b.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-xs');
                b.classList.add('bg-slate-100', 'dark:bg-slate-800/70', 'text-slate-600', 'dark:text-slate-300');
            });
            const selected = document.getElementById(`tab-${tab}`);
            if (selected) {
                selected.classList.remove('bg-slate-100', 'dark:bg-slate-800/70', 'text-slate-600', 'dark:text-slate-300');
                selected.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-xs');
            }
            applyFilters();
        }

        function applyFilters() {
            const search = (document.getElementById('complaintSearchInput')?.value || '').toLowerCase().trim();
            const purok = document.getElementById('complaintPurokFilter')?.value || '';
            const rows = document.querySelectorAll('.complaint-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const rowPurok = row.getAttribute('data-purok');
                const searchData = row.getAttribute('data-search') || '';

                // Tab check
                let matchesTab = true;
                if (activeTab === 'pending') {
                    matchesTab = status === 'submitted' || status === 'under_review';
                } else if (activeTab === 'in_progress') {
                    matchesTab = status === 'in_progress' || status === 'acknowledged' || status === 'referred_to_responsible_office';
                } else if (activeTab === 'resolved') {
                    matchesTab = status === 'resolved' || status === 'closed';
                } else if (activeTab === 'rejected') {
                    matchesTab = status === 'rejected';
                }

                // Purok check
                const matchesPurok = !purok || rowPurok === purok;

                // Search check
                const matchesSearch = !search || searchData.includes(search);

                if (matchesTab && matchesPurok && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('complaintSearchInput')?.addEventListener('input', applyFilters);
        document.getElementById('complaintPurokFilter')?.addEventListener('change', applyFilters);

        // Chart.js Visualizations
        document.addEventListener('DOMContentLoaded', function () {
            // Category Chart (Doughnut)
            const catCtx = document.getElementById('categoryChart')?.getContext('2d');
            if (catCtx) {
                const catData = @json($categoriesData);
                new Chart(catCtx, {
                    type: 'doughnut',
                    data: {
                        labels: catData.map(d => d.name),
                        datasets: [{
                            data: catData.map(d => d.count),
                            backgroundColor: [
                                '#2563EB', '#16815D', '#F59E0B', '#EF4444', 
                                '#8B5CF6', '#06B6D4', '#EC4899', '#64748B'
                            ],
                            borderWidth: 2,
                            borderColor: document.documentElement.classList.contains('dark') ? '#111C33' : '#FFFFFF',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    boxWidth: 12, 
                                    font: { size: 10 },
                                    color: document.documentElement.classList.contains('dark') ? '#94A3B8' : '#64748B'
                                } 
                            }
                        }
                    }
                });
            }

            // Status Chart (Bar)
            const statusCtx = document.getElementById('statusChart')?.getContext('2d');
            if (statusCtx) {
                const statusCounts = @json($statusCounts);
                new Chart(statusCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Submitted', 'Under Review', 'In Progress', 'Resolved', 'Rejected'],
                        datasets: [{
                            label: 'Complaints',
                            data: [
                                statusCounts.submitted || 0,
                                statusCounts.under_review || 0,
                                statusCounts.in_progress || 0,
                                statusCounts.resolved || 0,
                                statusCounts.rejected || 0
                            ],
                            backgroundColor: ['#3B82F6', '#F59E0B', '#6366F1', '#10B981', '#EF4444'],
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { 
                                    precision: 0,
                                    color: document.documentElement.classList.contains('dark') ? '#94A3B8' : '#64748B'
                                },
                                grid: { color: document.documentElement.classList.contains('dark') ? '#1E293B' : '#F1F5F9' }
                            },
                            x: {
                                ticks: { color: document.documentElement.classList.contains('dark') ? '#94A3B8' : '#64748B' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.admin>
