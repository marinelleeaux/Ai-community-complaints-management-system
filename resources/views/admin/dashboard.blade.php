<x-layouts.admin>
    <x-slot:header>Official Administrator Dashboard</x-slot:header>

    <div class="space-y-6 animate-fade-in-up">
        <!-- 1. Top Executive Hero Header: Full-Bar Architectural Photo Background + Admin Profile & Live Controls -->
        <div class="relative rounded-3xl overflow-hidden shadow-xl border border-slate-200/80 dark:border-slate-800 bg-slate-100 dark:bg-[#09152C] p-6 sm:p-8 min-h-[230px] flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 group">
            
            <!-- Full-Bleed Barangay Hall Photo Filling the Entire Bar (Copied 1:1 Transparent from Resident Portal) -->
            <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden select-none">
                <img src="{{ asset('images/barangay-hall.png') }}" 
                     alt="Barangay North Poblacion Hall" 
                     class="absolute inset-0 w-full h-full object-cover object-[center_35%] opacity-35 dark:opacity-25 filter contrast-105 saturate-110 brightness-95 dark:brightness-80 transition-transform duration-700 group-hover:scale-[1.01]">
                
                <!-- Linear Scrim Overlay: Soft translucent scrim for optimal contrast and readability -->
                <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/70 via-50% to-transparent dark:from-[#09152C]/90 dark:via-[#09152C]/70 dark:via-50% dark:to-transparent pointer-events-none"></div>
            </div>

            <!-- Left Side: Admin Profile Picture with Glowing Ring + Welcome Headings & Animated Badges -->
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-6 flex-1 max-w-3xl">
                <!-- Admin Profile Picture with Animated Halo & Status Ping -->
                <div class="relative shrink-0 group/avatar">
                    <!-- Glowing Aura / Halo -->
                    <div class="absolute -inset-1.5 rounded-3xl bg-gradient-to-tr from-sky-400 via-blue-500 to-indigo-400 opacity-75 blur-md group-hover/avatar:opacity-100 transition-opacity duration-500 animate-glow-pulse"></div>
                    
                    <!-- Avatar Frame with Reflection Sheen (Matching Resident Portal) -->
                    <div class="relative w-22 h-22 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-3xl bg-white dark:bg-slate-800 p-1.5 shadow-2xl border-2 border-slate-200/90 dark:border-blue-400/40 ring-4 ring-slate-900/5 dark:ring-white/10 group-hover/avatar:scale-105 transition-all duration-300">
                        <div class="relative w-full h-full rounded-2xl overflow-hidden shadow-inner">
                            <img src="{{ Auth::user()->avatarUrl() }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="w-full h-full object-cover">
                            
                            <!-- Glossy Glass Reflection Sheen -->
                            <div class="absolute inset-0 bg-gradient-to-b from-white/30 via-white/10 to-transparent pointer-events-none"></div>
                            <span class="absolute inset-0 w-1/2 h-full bg-gradient-to-r from-transparent via-white/40 to-transparent skew-x-12 -translate-x-full group-hover/avatar:animate-shimmer pointer-events-none"></span>
                        </div>
                        
                        <!-- Verified Administrator Badge with Pulsing Ping -->
                        <div class="absolute -bottom-2 -right-2 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-lg border-2 border-white dark:border-slate-900 animate-bounce-slow" title="Official Administrator Online">
                            <span class="w-2.5 h-2.5 rounded-full bg-white animate-ping absolute opacity-60"></span>
                            <svg class="w-4 h-4 relative z-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Downward Mirrored Reflection -->
                    <div class="absolute -bottom-2.5 left-2 right-2 h-3.5 rounded-b-xl overflow-hidden opacity-30 dark:opacity-20 pointer-events-none transform -scale-y-100 blur-[0.8px] select-none [mask-image:linear-gradient(to_bottom,rgba(0,0,0,0.8),transparent)]">
                        <img src="{{ Auth::user()->avatarUrl() }}" alt="" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Welcome Text & Civic Context -->
                <div class="space-y-2">
                    <!-- Badges Row -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-950/80 border border-blue-200/80 dark:border-blue-800/80 text-blue-700 dark:text-blue-300 shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                            <span>OFFICIAL ADMINISTRATOR</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 dark:bg-purple-950/80 border border-purple-200/80 dark:border-purple-800/80 text-purple-700 dark:text-purple-300">
                            <svg class="w-3 h-3 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span>North Poblacion, Maramag</span>
                        </span>
                    </div>

                    <!-- Welcome, Admin Name! -->
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white tracking-tight drop-shadow-xs">
                        Welcome, {{ Auth::user()->name }}!
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed max-w-xl">
                        Here is your executive live control center for community complaints, pending resident approvals, and public service notices.
                    </p>

                    <!-- Animated Feature Chips Row -->
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 pt-1">
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white/80 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 text-[11px] font-semibold text-slate-700 dark:text-slate-300 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                            <span>Real-Time AI Categorization</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white/80 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 text-[11px] font-semibold text-slate-700 dark:text-slate-300 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>Direct Complaint Resolution</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white/80 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 text-[11px] font-semibold text-slate-700 dark:text-slate-300 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Verified Resident Accounts</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Interactive Action Buttons with Shimmer & Glowing Hover -->
            <div class="relative z-10 flex flex-col sm:flex-row lg:flex-col items-stretch lg:items-end gap-3 shrink-0 w-full lg:w-auto">
                <!-- Primary Action: Go to Complaints Management -->
                <a href="{{ route('admin.complaints.index') }}" 
                   class="relative overflow-hidden inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 text-white font-extrabold text-xs sm:text-sm shadow-xl shadow-blue-600/20 border border-white/20 transition-all duration-300 hover:scale-105 active:scale-95 group/btn">
                    <!-- Shimmer Sweep Effect -->
                    <span class="absolute inset-0 w-1/2 h-full bg-white/20 skew-x-12 -translate-x-full group-hover/btn:animate-shimmer pointer-events-none"></span>
                    <svg class="w-4 h-4 text-blue-100 group-hover/btn:rotate-6 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Complaints Management</span>
                    <span class="text-base leading-none transition-transform group-hover/btn:translate-x-1">&rarr;</span>
                </a>

                <!-- Secondary Action: Post Announcement & Live System Pill -->
                <div class="flex items-center justify-between lg:justify-end gap-2.5">
                    <a href="{{ route('admin.announcements.index') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold border border-slate-200 dark:border-slate-700 shadow-2xs transition-all hover:scale-102">
                        <span>+ Post Announcement</span>
                    </a>
                    <div class="px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/70 border border-emerald-200/80 dark:border-emerald-800 text-[10px] font-bold text-emerald-700 dark:text-emerald-300 flex items-center gap-1.5 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Portal Live</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Eight Stat KPI Cards (2 Rows of 4 Cards Matching Mockup 1:1) -->
        <div class="space-y-4">
            <!-- Row 1: Complaints Operational Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Card 1: Total Complaints -->
                <a href="{{ route('admin.complaints.index') }}" 
                   class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 z-10 group-hover:scale-110 transition-transform shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 z-10">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Complaints</div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $displayTotalComplaints }}</span>
                            <span class="inline-flex items-center text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                &uarr; 12%
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 truncate">All submitted complaints</div>
                    </div>
                    <!-- Background Soft Wave -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-16 pointer-events-none opacity-20">
                        <svg viewBox="0 0 100 60" fill="none" class="w-full h-full"><path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#2563EB"/></svg>
                    </div>
                </a>

                <!-- Card 2: Pending -->
                <a href="{{ route('admin.complaints.index', ['status' => 'submitted']) }}" 
                   class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 flex items-center justify-center shrink-0 z-10 group-hover:scale-110 transition-transform shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 z-10">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pending</div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $displayPendingComplaints }}</span>
                            <span class="inline-flex items-center text-[11px] font-bold text-amber-600 dark:text-amber-400">
                                &uarr; 8%
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 truncate">Awaiting review</div>
                    </div>
                    <!-- Background Soft Wave -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-16 pointer-events-none opacity-20">
                        <svg viewBox="0 0 100 60" fill="none" class="w-full h-full"><path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#F59E0B"/></svg>
                    </div>
                </a>

                <!-- Card 3: In Progress -->
                <a href="{{ route('admin.complaints.index', ['status' => 'in_progress']) }}" 
                   class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/60 text-sky-500 flex items-center justify-center shrink-0 z-10 group-hover:scale-110 transition-transform shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 z-10">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">In Progress</div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $displayInProgress }}</span>
                            <span class="inline-flex items-center text-[11px] font-bold text-sky-600 dark:text-sky-400">
                                &uarr; 6%
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 truncate">Being processed</div>
                    </div>
                    <!-- Background Soft Wave -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-16 pointer-events-none opacity-20">
                        <svg viewBox="0 0 100 60" fill="none" class="w-full h-full"><path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#0EA5E9"/></svg>
                    </div>
                </a>

                <!-- Card 4: Resolved -->
                <a href="{{ route('admin.complaints.index', ['status' => 'resolved']) }}" 
                   class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-500 flex items-center justify-center shrink-0 z-10 group-hover:scale-110 transition-transform shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 z-10">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Resolved</div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $displayResolved }}</span>
                            <span class="inline-flex items-center text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                &uarr; 18%
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 truncate">Successfully closed</div>
                    </div>
                    <!-- Background Soft Wave -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-16 pointer-events-none opacity-20">
                        <svg viewBox="0 0 100 60" fill="none" class="w-full h-full"><path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#10B981"/></svg>
                    </div>
                </a>
            </div>

            <!-- Row 2: Secondary Governance & Resident Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Card 5: Rejected -->
                <a href="{{ route('admin.complaints.index', ['status' => 'rejected']) }}" 
                   class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-500 flex items-center justify-center shrink-0 z-10 group-hover:scale-110 transition-transform shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 z-10">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Rejected</div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $displayRejected }}</span>
                            <span class="inline-flex items-center text-[11px] font-bold text-rose-600 dark:text-rose-400">
                                &uarr; 2%
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 truncate">Not approved</div>
                    </div>
                    <!-- Background Soft Wave -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-16 pointer-events-none opacity-20">
                        <svg viewBox="0 0 100 60" fill="none" class="w-full h-full"><path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#EF4444"/></svg>
                    </div>
                </a>

                <!-- Card 6: Pending Accounts -->
                <a href="{{ route('admin.residents.pending') }}" 
                   class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-2xl p-5 border {{ $displayPendingAccounts > 0 ? 'border-amber-400/60 dark:border-amber-500/40 shadow-xs' : 'border-slate-200/80 dark:border-slate-800 shadow-2xs' }} hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl {{ $displayPendingAccounts > 0 ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-500' : 'bg-blue-50 dark:bg-blue-950/60 text-blue-500' }} flex items-center justify-center shrink-0 z-10 group-hover:scale-110 transition-transform shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 z-10">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pending Accounts</span>
                            @if($displayPendingAccounts > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-white animate-pulse">Action Required</span>
                            @endif
                        </div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $displayPendingAccounts }}</span>
                            @if($displayPendingAccounts > 0)
                                <span class="text-[11px] font-bold text-amber-600 dark:text-amber-400">
                                    Queue active
                                </span>
                            @else
                                <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                    All clear
                                </span>
                            @endif
                        </div>
                        <div class="text-[11px] text-slate-400 truncate flex items-center justify-between mt-0.5">
                            <span>Review resident verifications &rarr;</span>
                        </div>
                    </div>
                    <!-- Background Soft Wave -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-16 pointer-events-none opacity-20">
                        <svg viewBox="0 0 100 60" fill="none" class="w-full h-full"><path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#F59E0B"/></svg>
                    </div>
                </a>

                <!-- Card 7: Verified Complainants -->
                <a href="{{ route('admin.residents.index') }}" 
                   class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-500 flex items-center justify-center shrink-0 z-10 group-hover:scale-110 transition-transform shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 z-10">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Verified Complainants</div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $displayActiveComplainants }}</span>
                            <span class="inline-flex items-center text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                &uarr; 10%
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 truncate">Verified citizen records</div>
                    </div>
                    <!-- Background Soft Wave -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-16 pointer-events-none opacity-20">
                        <svg viewBox="0 0 100 60" fill="none" class="w-full h-full"><path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#6366F1"/></svg>
                    </div>
                </a>

                <!-- Card 8: Barangay Bulletins -->
                <a href="{{ route('admin.announcements.index') }}" 
                   class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-500 flex items-center justify-center shrink-0 z-10 group-hover:scale-110 transition-transform shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 z-10">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Barangay Bulletins</div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $publishedAnnouncements ?? 4 }}</span>
                            <span class="inline-flex items-center text-[11px] font-bold text-purple-600 dark:text-purple-400">
                                Active
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 truncate">Published public notices</div>
                    </div>
                    <!-- Background Soft Wave -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-16 pointer-events-none opacity-20">
                        <svg viewBox="0 0 100 60" fill="none" class="w-full h-full"><path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#8B5CF6"/></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- 3. Three Analytics Charts Row (Matching Mockup 1:1) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- Left Chart: Complaints Overview (Line Chart) -->
            <div class="lg:col-span-5 bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800/60">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <h2 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white">
                            Complaints Overview
                        </h2>
                    </div>

                    <!-- Range Selector Pills -->
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-xl p-1 text-[11px] font-bold">
                        <button type="button" 
                                onclick="setAdminOverviewRange('7D')" 
                                id="admin-btn-range-7D" 
                                class="px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 hover:text-slate-900">
                            7D
                        </button>
                        <button type="button" 
                                onclick="setAdminOverviewRange('30D')" 
                                id="admin-btn-range-30D" 
                                class="px-2.5 py-1 rounded-lg bg-blue-600 text-white shadow-2xs">
                            30D
                        </button>
                        <button type="button" 
                                onclick="setAdminOverviewRange('6M')" 
                                id="admin-btn-range-6M" 
                                class="px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 hover:text-slate-900">
                            6M
                        </button>
                        <button type="button" 
                                onclick="setAdminOverviewRange('Custom')" 
                                id="admin-btn-range-Custom" 
                                class="px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 hover:text-slate-900">
                            Custom
                        </button>
                    </div>
                </div>

                <div class="relative h-60 w-full mt-4">
                    <canvas id="overviewChart"></canvas>
                </div>
            </div>

            <!-- Middle Chart: Complaint Status Distribution (Donut Chart) -->
            <div class="lg:col-span-4 bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800/60">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white">
                        Complaint Status Distribution
                    </h2>
                </div>

                <div class="flex items-center justify-between gap-4 mt-4">
                    <!-- Donut Canvas Container -->
                    <div class="relative w-44 h-44 shrink-0 flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                        <!-- Center Counter (29 / TOTAL) -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">
                                {{ $displayTotalComplaints }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                TOTAL
                            </span>
                        </div>
                    </div>

                    <!-- Legend Items Matching Mockup -->
                    <div class="space-y-3 flex-1 text-xs font-semibold">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="text-slate-600 dark:text-slate-300">Pending</span>
                            </div>
                            <span class="font-extrabold text-slate-900 dark:text-white">
                                {{ $displayPendingComplaints }} ({{ $pendingPct }}%)
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span class="text-slate-600 dark:text-slate-300">In Progress</span>
                            </div>
                            <span class="font-extrabold text-slate-900 dark:text-white">
                                {{ $displayInProgress }} ({{ $inProgressPct }}%)
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-slate-600 dark:text-slate-300">Resolved</span>
                            </div>
                            <span class="font-extrabold text-slate-900 dark:text-white">
                                {{ $displayResolved }} ({{ $resolvedPct }}%)
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span class="text-slate-600 dark:text-slate-300">Rejected</span>
                            </div>
                            <span class="font-extrabold text-slate-900 dark:text-white">
                                {{ $displayRejected }} ({{ $rejectedPct }}%)
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Chart: Complaints by Category (Vertical Bar Chart Matching Mockup) -->
            <div class="lg:col-span-3 bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800/60">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white">
                        Complaints by Category
                    </h2>
                </div>

                <div class="relative h-60 w-full mt-4">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. Complementary Administrative Management Row -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- Card 1: Resolution Performance -->
            <div class="lg:col-span-4 bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800/60">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white">
                        Resolution Performance
                    </h2>
                </div>

                <div class="flex items-center justify-between gap-4 mt-4">
                    <!-- Radial Progress Ring -->
                    <div class="relative w-36 h-36 shrink-0 flex items-center justify-center">
                        <svg class="w-36 h-36 transform -rotate-90">
                            <circle cx="72" cy="72" r="54" stroke="#E2E8F0" stroke-width="12" fill="none" class="dark:stroke-slate-700"/>
                            <circle cx="72" cy="72" r="54" 
                                    stroke="#10B981" 
                                    stroke-width="12" 
                                    stroke-linecap="round"
                                    fill="none" 
                                    stroke-dasharray="339.29"
                                    stroke-dashoffset="{{ 339.29 - (339.29 * 76 / 100) }}"
                                    class="transition-all duration-1000 ease-out"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">
                                76%
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 mt-0.5">
                                Resolved
                            </span>
                        </div>
                    </div>

                    <!-- Breakdown & Average Time -->
                    <div class="space-y-4 flex-1">
                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-slate-600 dark:text-slate-300 font-medium text-[11px]">Resolved</span>
                                </div>
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-[11px]">
                                    {{ $displayResolved }} (76%)
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                    <span class="text-slate-600 dark:text-slate-300 font-medium text-[11px]">Unresolved</span>
                                </div>
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-[11px]">
                                    {{ $displayPendingComplaints + $displayInProgress }} (24%)
                                </span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-1.5 text-slate-400 text-[11px]">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Avg. Resolution Time</span>
                            </div>
                            <div class="text-xl font-black text-slate-900 dark:text-white mt-1">
                                4.8 days
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Quick Actions -->
            <div class="lg:col-span-8 bg-white dark:bg-[#111C33] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800/60">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white">
                        Administrative Shortcuts
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4 flex-1">
                    <a href="{{ route('admin.residents.pending') }}" 
                       class="p-4 rounded-2xl {{ $displayPendingAccounts > 0 ? 'bg-amber-50/80 hover:bg-amber-100/90 dark:bg-amber-950/40 dark:hover:bg-amber-900/60 border border-amber-200/80 dark:border-amber-900/50' : 'bg-blue-50/80 hover:bg-blue-100/90 dark:bg-blue-950/40 dark:hover:bg-blue-900/60 border border-blue-100 dark:border-blue-900/50' }} flex flex-col justify-between transition-all group shadow-2xs">
                        <div class="flex items-center justify-between">
                            <span class="p-2.5 rounded-xl bg-white {{ $displayPendingAccounts > 0 ? 'dark:bg-amber-900/60 text-amber-600 dark:text-amber-300' : 'dark:bg-blue-900/60 text-blue-600 dark:text-blue-300' }} shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            </span>
                            @if($displayPendingAccounts > 0)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-white animate-pulse">{{ $displayPendingAccounts }} Pending</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">All Verified</span>
                            @endif
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs font-black text-slate-800 dark:text-white">Verify Accounts</span>
                            <span class="{{ $displayPendingAccounts > 0 ? 'text-amber-600' : 'text-blue-600' }} group-hover:translate-x-1 transition-transform font-bold">&rarr;</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.complaints.index') }}" 
                       class="p-4 rounded-2xl bg-emerald-50/80 hover:bg-emerald-100/90 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/60 border border-emerald-100 dark:border-emerald-900/50 flex flex-col justify-between transition-all group shadow-2xs">
                        <div class="flex items-center justify-between">
                            <span class="p-2.5 rounded-xl bg-white dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs font-black text-slate-800 dark:text-white">Manage Complaints</span>
                            <span class="text-emerald-600 group-hover:translate-x-1 transition-transform font-bold">&rarr;</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" 
                       class="p-4 rounded-2xl bg-purple-50/80 hover:bg-purple-100/90 dark:bg-purple-950/40 dark:hover:bg-purple-900/60 border border-purple-100 dark:border-purple-900/50 flex flex-col justify-between transition-all group shadow-2xs">
                        <div class="flex items-center justify-between">
                            <span class="p-2.5 rounded-xl bg-white dark:bg-purple-900/60 text-purple-600 dark:text-purple-300 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            </span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs font-black text-slate-800 dark:text-white">Print Reports</span>
                            <span class="text-purple-600 group-hover:translate-x-1 transition-transform font-bold">&rarr;</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4.5. Pending Resident Account Verifications -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/70 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">
                                Pending Resident Account Verifications
                            </h2>
                            @if($pendingApprovals > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-white animate-pulse">
                                    {{ $pendingApprovals }} Awaiting Action
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400">
                            Identity verification and portal authorization queue for newly registered residents
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.residents.pending') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition-colors">
                    <span>Manage Full Verification Queue</span>
                    <span class="font-bold">&rarr;</span>
                </a>
            </div>

            @if(isset($pendingResidentsList) && $pendingResidentsList->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">
                    @foreach($pendingResidentsList as $applicant)
                        <div class="relative p-4 rounded-2xl bg-slate-50/70 dark:bg-slate-900/50 border border-slate-200/70 dark:border-slate-800/80 hover:border-amber-400/60 dark:hover:border-amber-500/40 hover:shadow-md transition-all group flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-xs">
                                            {{ $applicant->initials() }}
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white truncate">{{ $applicant->name }}</h3>
                                            <span class="inline-block text-[10px] font-semibold text-blue-600 dark:text-blue-400">{{ $applicant->purok?->name ?? 'Purok' }}</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300">Pending</span>
                                </div>

                                <div class="mt-3 space-y-1 text-[11px] text-slate-500 dark:text-slate-400">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span class="truncate">{{ $applicant->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Applied {{ $applicant->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Action Form -->
                            <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-800/60 flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.residents.approve', $applicant->id) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirmAction(event, 'Approve {{ addslashes($applicant->name) }} as a verified resident of Barangay North Poblacion?', 'Approve Resident Registration', 'Yes, Approve Resident', false);"
                                            class="w-full py-1.5 px-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-[11px] shadow-xs hover:shadow transition-all flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span>Approve Resident</span>
                                    </button>
                                </form>
                                <a href="{{ route('admin.residents.pending') }}" class="py-1.5 px-3 rounded-xl bg-slate-200/70 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-[11px] transition-colors">
                                    Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-5 py-8 text-center bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-slate-200">All Resident Accounts Verified</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">There are no pending resident registration applications awaiting approval at this time.</p>
                </div>
            @endif
        </div>

        <!-- 5. Recent Complaints Table -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">
                            Recent Complaints
                        </h2>
                        <p class="text-xs text-slate-400">
                            Live queue of recent citizen reports, triage assessments, and resolution statuses
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.complaints.index') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 dark:hover:bg-blue-900/60 transition-colors">
                    <span>View All Complaints</span>
                    <span class="text-sm font-black">&rarr;</span>
                </a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-3">Ref #</th>
                            <th class="py-3 px-3">Title & Complainant</th>
                            <th class="py-3 px-3">Category</th>
                            <th class="py-3 px-3">Priority</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3">Date</th>
                            <th class="py-3 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($recentComplaints as $complaint)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors group">
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <span class="font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2 py-1 rounded-lg">
                                        {{ $complaint->reference_number }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 min-w-[200px]">
                                    <div class="font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors">
                                        {{ $complaint->title }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5 truncate max-w-xs">
                                        @if($complaint->english_summary)
                                            <span class="font-bold text-blue-600 dark:text-blue-400 shrink-0">AI:</span> <span class="text-slate-600 dark:text-slate-300 font-medium truncate">{{ $complaint->english_summary }}</span>
                                        @else
                                            <span>By: {{ $complaint->resident?->name ?? 'Anonymous Citizen' }}</span>
                                            @if($complaint->purok)
                                                <span>&bull; {{ $complaint->purok->name }}</span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $complaint->category?->name ?? 'General' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    @php
                                        $priorityClass = match($complaint->final_priority ?? 'medium') {
                                            'urgent' => 'bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/60',
                                            'high' => 'bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/60',
                                            'medium' => 'bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900/60',
                                            default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wide {{ $priorityClass }}">
                                        {{ ucfirst($complaint->final_priority ?? 'normal') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    @php
                                        $statusClass = match($complaint->status) {
                                            'resolved' => 'bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300',
                                            'rejected' => 'bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300',
                                            'in_progress' => 'bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300',
                                            'under_review' => 'bg-purple-100 dark:bg-purple-950/80 text-purple-700 dark:text-purple-300',
                                            default => 'bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $complaint->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                        {{ $complaint->created_at?->timezone(config('app.timezone', 'Asia/Manila'))->format('M d, Y') ?? 'Recently' }}
                                    </div>
                                    <div class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/></svg>
                                        <span>{{ $complaint->created_at?->timezone(config('app.timezone', 'Asia/Manila'))->format('h:i A') }}</span>
                                        <span class="text-slate-400 text-[10px]">({{ $complaint->created_at?->diffForHumans() }})</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.complaints.show', $complaint) }}" 
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-xs hover:shadow transition-all hover:scale-102 active:scale-95 shrink-0"
                                       title="View Official Complaint Details">
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
                                <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                    No complaints recorded yet in North Poblacion.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Datasets for Admin Complaints Overview Line Chart
        const adminOverviewData = {
            '7D': {
                labels: @json($overview7DLabels),
                data: @json($overview7DData)
            },
            '30D': {
                labels: @json($overview30DLabels),
                data: @json($overview30DData)
            },
            '6M': {
                labels: @json($overview6MLabels),
                data: @json($overview6MData)
            },
            'Custom': {
                labels: ['Sep 01', 'Sep 10', 'Sep 20', 'Sep 30', 'Oct 10', 'Oct 16'],
                data: [6, 11, 14, 18, 23, 29]
            }
        };

        let adminLineChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Line Chart: Complaints Overview
            const ctxOverview = document.getElementById('overviewChart')?.getContext('2d');
            if (ctxOverview) {
                const gradient = ctxOverview.createLinearGradient(0, 0, 0, 220);
                gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
                gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

                adminLineChart = new Chart(ctxOverview, {
                    type: 'line',
                    data: {
                        labels: adminOverviewData['30D'].labels,
                        datasets: [{
                            label: 'Complaints',
                            data: adminOverviewData['30D'].data,
                            borderColor: '#2563EB',
                            borderWidth: 2.5,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#2563EB',
                            pointBorderColor: '#FFFFFF',
                            pointBorderWidth: 2,
                            pointRadius: 4.5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1E293B',
                                titleFont: { size: 11, weight: 'bold' },
                                bodyFont: { size: 11 },
                                padding: 8,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                max: 30,
                                ticks: { stepSize: 10, font: { size: 10 } },
                                grid: { color: 'rgba(226, 232, 240, 0.6)' }
                            },
                            x: {
                                ticks: { font: { size: 10 } },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // 2. Donut Chart: Complaint Status Distribution
            const ctxStatus = document.getElementById('statusChart')?.getContext('2d');
            if (ctxStatus) {
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'In Progress', 'Resolved', 'Rejected'],
                        datasets: [{
                            data: [{{ $displayPendingComplaints }}, {{ $displayInProgress }}, {{ $displayResolved }}, {{ $displayRejected }}],
                            backgroundColor: ['#F59E0B', '#3B82F6', '#10B981', '#EF4444'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }

            // 3. Vertical Bar Chart: Complaints by Category (Matching Mockup with Numbers on top)
            const ctxCategory = document.getElementById('categoryChart')?.getContext('2d');
            if (ctxCategory) {
                const categoryLabels = @json(array_column($categoryBars, 'label'));
                const categoryCounts = @json(array_column($categoryBars, 'count'));
                const categoryColors = @json(array_column($categoryBars, 'color'));

                new Chart(ctxCategory, {
                    type: 'bar',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            data: categoryCounts,
                            backgroundColor: categoryColors,
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1E293B',
                                padding: 8,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                max: 25,
                                ticks: { stepSize: 5, font: { size: 9 } },
                                grid: { color: 'rgba(226, 232, 240, 0.6)' }
                            },
                            x: {
                                display: false
                            }
                        }
                    },
                    plugins: [{
                        id: 'topLabels',
                        afterDatasetsDraw(chart) {
                            const { ctx, data } = chart;
                            ctx.save();
                            ctx.font = 'bold 11px Plus Jakarta Sans, sans-serif';
                            ctx.fillStyle = '#1E293B';
                            ctx.textAlign = 'center';
                            chart.getDatasetMeta(0).data.forEach((bar, index) => {
                                const val = data.datasets[0].data[index];
                                ctx.fillText(val, bar.x, bar.y - 6);
                            });
                            ctx.restore();
                        }
                    }]
                });
            }
        });

        function setAdminOverviewRange(rangeKey) {
            ['7D', '30D', '6M', 'Custom'].forEach(k => {
                const btn = document.getElementById(`admin-btn-range-${k}`);
                if (btn) {
                    if (k === rangeKey) {
                        btn.className = 'px-2.5 py-1 rounded-lg bg-blue-600 text-white shadow-2xs';
                    } else {
                        btn.className = 'px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 hover:text-slate-900';
                    }
                }
            });

            if (adminLineChart && adminOverviewData[rangeKey]) {
                adminLineChart.data.labels = adminOverviewData[rangeKey].labels;
                adminLineChart.data.datasets[0].data = adminOverviewData[rangeKey].data;
                adminLineChart.update();
            }
        }
    </script>
    @endpush
</x-layouts.admin>
