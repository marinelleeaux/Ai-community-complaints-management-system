<x-layouts.resident>
    <x-slot:title>Dashboard</x-slot:title>

    <div class="space-y-6 animate-fade-in-up">
        <!-- Top Resident Hero Header: Full-Bar Architectural Photo Background + Clean Profile & Actions (No Blobs) -->
        <div class="relative rounded-3xl overflow-hidden shadow-xl border border-slate-200/80 dark:border-slate-800 bg-slate-100 dark:bg-[#09152C] p-6 md:p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 group min-h-[230px]">

            <!-- Full-Bleed Barangay Hall Photo Filling the Entire Bar (User Provided Architectural Panorama - Transparent) -->
            <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden select-none">
                <img src="{{ asset('images/barangay-hall.png') }}?v={{ file_exists(public_path('images/barangay-hall.png')) ? filemtime(public_path('images/barangay-hall.png')) : time() }}" 
                     alt="Barangay North Poblacion Hall" 
                     class="absolute inset-0 w-full h-full object-cover object-[center_35%] opacity-35 dark:opacity-25 filter contrast-105 saturate-110 brightness-95 dark:brightness-80 transition-transform duration-700 group-hover:scale-[1.01]">
                
                <!-- Linear Scrim Overlay: Soft translucent scrim for optimal contrast and readability -->
                <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/70 via-50% to-transparent dark:from-[#09152C]/90 dark:via-[#09152C]/70 dark:via-50% dark:to-transparent pointer-events-none"></div>
            </div>

            <!-- Left Side: Profile Avatar + Greeting -->
            <div class="relative z-10 flex-1 max-w-2xl">
                <div class="flex items-start gap-4 sm:gap-5">
                    <!-- Resident Profile Picture Avatar with Reflection & Animations -->
                    <div class="relative shrink-0 group/avatar">
                        <!-- Animated Glowing Halo -->
                        <div class="absolute -inset-1.5 rounded-2xl bg-gradient-to-tr from-blue-500/40 via-indigo-500/30 to-purple-500/40 blur-md opacity-70 group-hover/avatar:opacity-100 transition-opacity duration-500 animate-pulse"></div>

                        <!-- Avatar Container with Glass Reflection Sheen & Hover Lift -->
                        <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white dark:bg-slate-800 p-1 shadow-xl border-2 border-slate-200/90 dark:border-slate-700 overflow-visible transition-all duration-300 group-hover/avatar:-translate-y-1 group-hover/avatar:scale-105 group-hover/avatar:shadow-2xl">
                            <!-- Inner Image Box -->
                            <div class="relative w-full h-full rounded-xl overflow-hidden shadow-xs">
                                <img src="{{ Auth::user()->avatarUrl() }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover/avatar:scale-110">
                                
                                <!-- Glossy Top Glass Reflection Sheen -->
                                <div class="absolute inset-0 bg-gradient-to-b from-white/30 via-white/10 to-transparent pointer-events-none"></div>

                                <!-- Diagonal Interactive Shimmer Sweep -->
                                <span class="absolute inset-0 w-1/2 h-full bg-gradient-to-r from-transparent via-white/40 to-transparent skew-x-12 -translate-x-full group-hover/avatar:animate-shimmer pointer-events-none"></span>
                            </div>

                            <!-- Overlapping Emerald Verified Checkmark Badge with Status Pulse -->
                            <div class="absolute -bottom-1.5 -right-1.5 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-md border-2 border-white dark:border-slate-900 z-20 group-hover/avatar:scale-110 transition-transform" title="Verified Resident Account">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping absolute opacity-60"></span>
                                <span class="relative z-10">✓</span>
                            </div>
                        </div>

                        <!-- Realistic Downward Mirrored Reflection Beneath Avatar -->
                        <div class="absolute -bottom-2.5 left-2 right-2 h-3.5 rounded-b-xl overflow-hidden opacity-30 dark:opacity-20 pointer-events-none transform -scale-y-100 blur-[0.8px] select-none [mask-image:linear-gradient(to_bottom,rgba(0,0,0,0.8),transparent)]">
                            <img src="{{ Auth::user()->avatarUrl() }}" alt="" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Greeting & User Info -->
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wide bg-gradient-to-r from-emerald-100 to-teal-100 dark:from-emerald-950/80 dark:to-teal-950/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/80 shadow-2xs">
                                VERIFIED RESIDENT
                            </span>
                            <span class="flex items-center gap-1 text-xs font-bold text-blue-700 dark:text-blue-300">
                                <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $user->purok?->name ?? 'Purok 1' }}, North Poblacion</span>
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black text-[#12345B] dark:text-white tracking-tight">
                            Welcome, {{ $user->name ?? 'Maria Santos' }}!
                        </h1>
                        <p class="text-sm sm:text-base text-slate-800 dark:text-slate-100 font-bold mt-2 max-w-xl leading-relaxed drop-shadow-xs">
                            Here is the live status of your submitted complaints and official barangay notices.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Architectural Heritage Badge + Prominent "+ File New Complaint" Button -->
            <div class="relative z-10 flex flex-col sm:flex-row lg:flex-col items-center lg:items-end gap-3 shrink-0 w-full lg:w-auto">
                <!-- Architectural Heritage Badge: Ensuring Barangay Hall Name is Unmistakably Visible -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/85 dark:bg-[#111C33]/90 backdrop-blur-md border border-purple-200 dark:border-purple-800/80 text-[#12345B] dark:text-purple-200 shadow-md shadow-purple-950/5">
                    <span class="w-2 h-2 rounded-full bg-purple-600 dark:bg-purple-400 animate-pulse"></span>
                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider">
                        Barangay North Poblacion Hall
                    </span>
                </div>

                <!-- Primary Action: File New Complaint with Shimmer Sweep & Blue-Purple Gradient -->
                <a href="{{ route('resident.complaints.create') }}" 
                   class="relative overflow-hidden inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 text-white font-extrabold text-xs sm:text-sm shadow-xl shadow-purple-600/30 border border-white/40 transition-all duration-300 hover:scale-105 active:scale-95 group/btn">
                    <span class="absolute inset-0 w-1/2 h-full bg-white/25 skew-x-12 -translate-x-full group-hover/btn:animate-shimmer pointer-events-none"></span>
                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-base font-extrabold leading-none">+</span>
                    <span>File New Complaint</span>
                </a>
            </div>
        </div>

        <!-- 4 Top KPI Stat Cards with Background Waves (Matching Mockup) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- 1. Total Complaints Submitted -->
            <div class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 z-10 shadow-2xs group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0 z-10">
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 truncate">
                        Total Complaints Submitted
                    </div>
                    <div class="flex items-baseline gap-2 mt-1">
                        <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-none">
                            {{ $displayTotal }}
                        </div>
                        <span class="inline-flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            {{ $totalTrend }}
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        + 2 from last month
                    </div>
                </div>
                <!-- Soft Blue Ambient Wave in Background -->
                <div class="absolute -right-4 -bottom-4 w-28 h-20 pointer-events-none opacity-20">
                    <svg viewBox="0 0 100 60" fill="none" class="w-full h-full">
                        <path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#2563EB"/>
                    </svg>
                </div>
            </div>

            <!-- 2. Pending -->
            <div class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 dark:text-amber-400 flex items-center justify-center shrink-0 z-10 shadow-2xs group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0 z-10">
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 truncate">
                        Pending
                    </div>
                    <div class="flex items-baseline gap-2 mt-1">
                        <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-none">
                            {{ $displayPending }}
                        </div>
                        <span class="inline-flex items-center text-xs font-bold text-amber-500 dark:text-amber-400">
                            {{ $pendingTrend }}
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        Awaiting review
                    </div>
                </div>
                <!-- Soft Amber Wave -->
                <div class="absolute -right-4 -bottom-4 w-28 h-20 pointer-events-none opacity-20">
                    <svg viewBox="0 0 100 60" fill="none" class="w-full h-full">
                        <path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#F59E0B"/>
                    </svg>
                </div>
            </div>

            <!-- 3. In Review -->
            <div class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 z-10 shadow-2xs group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0 z-10">
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 truncate">
                        In Review
                    </div>
                    <div class="flex items-baseline gap-2 mt-1">
                        <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-none">
                            {{ $displayInReview }}
                        </div>
                        <span class="inline-flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            {{ $inReviewTrend }}
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        Being processed
                    </div>
                </div>
                <!-- Soft Cyan Wave -->
                <div class="absolute -right-4 -bottom-4 w-28 h-20 pointer-events-none opacity-20">
                    <svg viewBox="0 0 100 60" fill="none" class="w-full h-full">
                        <path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#0EA5E9"/>
                    </svg>
                </div>
            </div>

            <!-- 4. Resolved -->
            <div class="relative overflow-hidden bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-blue-purple hover:-translate-y-1 reflection-glass transition-all duration-300 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 z-10 shadow-2xs group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0 z-10">
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 truncate">
                        Resolved
                    </div>
                    <div class="flex items-baseline gap-2 mt-1">
                        <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-none">
                            {{ $displayResolved }}
                        </div>
                        <span class="inline-flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            {{ $resolvedTrend }}
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        Successfully closed
                    </div>
                </div>
                <!-- Soft Emerald Wave -->
                <div class="absolute -right-4 -bottom-4 w-28 h-20 pointer-events-none opacity-20">
                    <svg viewBox="0 0 100 60" fill="none" class="w-full h-full">
                        <path d="M0 40 Q 30 10, 60 30 T 100 20 L 100 60 L 0 60 Z" fill="#10B981"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Middle Row: 3 Analytics Cards (Line Chart, Donut Chart, Quick Actions) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- 1. My Complaints Overview (Line Chart) -->
            <div class="lg:col-span-5 bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <h2 class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white">
                            My Complaints Overview
                        </h2>
                    </div>

                    <!-- Range Selector Pills -->
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-xl p-1 text-[11px] font-bold">
                        <button type="button" 
                                onclick="setOverviewRange('7D')" 
                                id="btn-range-7D" 
                                class="px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 hover:text-slate-900">
                            7D
                        </button>
                        <button type="button" 
                                onclick="setOverviewRange('30D')" 
                                id="btn-range-30D" 
                                class="px-2.5 py-1 rounded-lg bg-blue-600 text-white shadow-2xs">
                            30D
                        </button>
                        <button type="button" 
                                onclick="setOverviewRange('6M')" 
                                id="btn-range-6M" 
                                class="px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 hover:text-slate-900">
                            6M
                        </button>
                        <button type="button" 
                                onclick="setOverviewRange('1Y')" 
                                id="btn-range-1Y" 
                                class="px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 hover:text-slate-900">
                            1Y
                        </button>
                    </div>
                </div>

                <div class="relative h-56 w-full mt-4">
                    <canvas id="residentOverviewChart"></canvas>
                </div>
            </div>

            <!-- 2. Complaint Status Distribution (Donut Chart) -->
            <div class="lg:col-span-4 bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    <h2 class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white">
                        Complaint Status Distribution
                    </h2>
                </div>

                <div class="flex items-center justify-between gap-4 mt-2">
                    <!-- Donut Canvas Container -->
                    <div class="relative w-44 h-44 shrink-0 flex items-center justify-center">
                        <canvas id="residentStatusChart"></canvas>
                        <!-- Center Counter -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">
                                {{ $displayTotal }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                TOTAL
                            </span>
                        </div>
                    </div>

                    <!-- Legend Items -->
                    <div class="space-y-3 flex-1 text-xs font-semibold">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="text-slate-600 dark:text-slate-300">Pending</span>
                            </div>
                            <span class="font-extrabold text-slate-900 dark:text-white">
                                {{ $displayPending }} ({{ $pendingPct }}%)
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span class="text-slate-600 dark:text-slate-300">In Review</span>
                            </div>
                            <span class="font-extrabold text-slate-900 dark:text-white">
                                {{ $displayInReview }} ({{ $inReviewPct }}%)
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
                    </div>
                </div>
            </div>

            <!-- 3. Quick Actions (2x2 Grid Matching Mockup) -->
            <div class="lg:col-span-3 bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h2 class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white">
                        Quick Actions
                    </h2>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-4 flex-1">
                    <!-- File a New Complaint -->
                    <a href="{{ route('resident.complaints.create') }}" 
                       class="p-3.5 rounded-2xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/50 hover:bg-blue-100/80 transition-all flex flex-col justify-between group shadow-2xs">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">File a<br>New Complaint</span>
                            <span class="text-blue-600 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </div>
                    </a>

                    <!-- View My Complaints -->
                    <a href="{{ route('resident.complaints.index') }}" 
                       class="p-3.5 rounded-2xl bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/50 hover:bg-emerald-100/80 transition-all flex flex-col justify-between group shadow-2xs">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">View My<br>Complaints</span>
                            <span class="text-emerald-600 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </div>
                    </a>

                    <!-- View Announcements -->
                    <a href="{{ route('resident.announcements.index') }}" 
                       class="p-3.5 rounded-2xl bg-amber-50/70 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/50 hover:bg-amber-100/80 transition-all flex flex-col justify-between group shadow-2xs">
                        <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">View<br>Announcements</span>
                            <span class="text-amber-600 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </div>
                    </a>

                    <!-- Ask AI Chatbot -->
                    <a href="{{ route('resident.chatbot') }}" 
                       class="p-3.5 rounded-2xl bg-purple-50/70 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/50 hover:bg-purple-100/80 transition-all flex flex-col justify-between group shadow-2xs">
                        <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center shadow-xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">Ask<br>AI Chatbot</span>
                            <span class="text-purple-600 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Row: 3 Content Cards (Recent Complaints, Announcements, AI Chatbot Assistant) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- 1. Recent Complaints Table -->
            <div class="lg:col-span-5 bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h2 class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white">
                                Recent Complaints
                            </h2>
                        </div>
                        <a href="{{ route('resident.complaints.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-1">
                            <span>View All</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-100 dark:border-slate-800 text-[11px]">
                                    <th class="py-2.5 font-semibold">#</th>
                                    <th class="py-2.5 font-semibold">Complaint Title</th>
                                    <th class="py-2.5 font-semibold">Date Submitted</th>
                                    <th class="py-2.5 font-semibold">Status</th>
                                    <th class="py-2.5 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-medium">
                                @forelse($recentComplaints as $index => $complaint)
                                    @php
                                        $displayNum = '# ' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                                        $status = $complaint->status;
                                        $isPending = $status === 'pending' || $status === 'submitted';
                                        $isResolved = $status === 'resolved';
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                        <td class="py-3 text-slate-500 font-bold">{{ $displayNum }}</td>
                                        <td class="py-3 font-semibold text-slate-900 dark:text-white max-w-[150px] truncate" title="{{ $complaint->title }}">
                                            {{ $complaint->title }}
                                        </td>
                                        <td class="py-3 whitespace-nowrap">
                                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                                {{ $complaint->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('M d, Y') }}
                                            </div>
                                            <div class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1 mt-0.5">
                                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/></svg>
                                                <span>{{ $complaint->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('h:i A') }}</span>
                                                <span class="text-slate-400 text-[10px]">({{ $complaint->created_at->diffForHumans() }})</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @if($isPending)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200/60">
                                                    <svg class="w-3 h-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l3 3"/></svg>
                                                    Pending
                                                </span>
                                            @elseif($isResolved)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Resolved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/60">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    In Review
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-right whitespace-nowrap">
                                            <a href="{{ route('resident.complaints.show', $complaint->id) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-xs hover:shadow transition-all hover:scale-102 active:scale-95 shrink-0"
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
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
                                        <td class="py-3 text-slate-500 font-bold"># 001</td>
                                        <td class="py-3 font-semibold text-slate-900 dark:text-white">Streetlight Not Working</td>
                                        <td class="py-3 text-slate-500 whitespace-nowrap">Oct 16, 2026</td>
                                        <td class="py-3">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200/60">
                                                <svg class="w-3 h-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l3 3"/></svg>
                                                Pending
                                            </span>
                                        </td>
                                        <td class="py-3 text-right">
                                            <a href="{{ route('resident.complaints.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-600 hover:text-white text-xs font-bold transition-all shadow-2xs">
                                                <span>View Details</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. Announcements Card -->
            <div class="lg:col-span-4 bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                            <h2 class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white">
                                Announcements
                            </h2>
                        </div>
                        <a href="{{ route('resident.announcements.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-1">
                            <span>View All</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    @php
                        $ann = $latestAnnouncements->first();
                        $annTitle = $ann ? $ann->title : 'Barangay Assembly Meeting';
                        $annDate = $ann && $ann->published_at ? $ann->published_at->format('M d, Y') : 'Oct 16, 2026';
                        $annDesc = $ann ? Str::limit(strip_tags($ann->description), 110) : 'Please be informed about the upcoming barangay assembly this October. All residents are highly encouraged to attend and participate in local governance.';
                        $annLink = $ann ? route('resident.announcements.show', $ann->id) : route('resident.announcements.index');
                    @endphp

                    <div class="flex gap-3.5 items-start p-2 rounded-2xl group hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                        <img src="{{ asset('images/barangay-hall.jpg') }}" 
                             alt="{{ $annTitle }}" 
                             class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl object-cover shrink-0 border border-slate-200/70 dark:border-slate-700 shadow-2xs group-hover:scale-105 transition-transform">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-1">
                                <h3 class="font-black text-xs text-slate-900 dark:text-white leading-snug group-hover:text-blue-600 transition-colors">
                                    {{ $annTitle }}
                                </h3>
                                <button type="button" class="text-slate-400 hover:text-slate-600 p-0.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/></svg>
                                </button>
                            </div>
                            <div class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                {{ $annDate }}
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 mt-1 leading-snug">
                                {{ $annDesc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. AI Chatbot Assistant Preview Card (Matching Mockup 1:1) -->
            <div id="ai-assistant-widget" class="lg:col-span-3 bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800 mb-3.5">
                        <div class="w-6 h-6 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h2 class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white">
                            AI Chatbot Assistant
                        </h2>
                    </div>

                    <!-- Assistant Friendly Card matching Screenshot -->
                    <div class="p-3.5 rounded-2xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-100/80 dark:border-blue-900/40 flex items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <!-- Cute Friendly Robot Avatar -->
                            <div class="w-11 h-11 rounded-2xl bg-blue-100 dark:bg-blue-900/60 flex items-center justify-center shrink-0 shadow-2xs overflow-hidden">
                                <img src="{{ asset('images/ai-robot-avatar.svg') }}" alt="AI Robot" class="w-10 h-10 object-contain">
                            </div>
                            <div class="leading-tight">
                                <div class="text-xs font-black text-[#12345B] dark:text-white flex items-center gap-1">
                                    <span>Hi {{ strtok($user->name ?? 'Maria', ' ') }}!</span>
                                    <span>👋</span>
                                </div>
                                <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                                    How can I help you today?
                                </div>
                            </div>
                        </div>

                        <!-- Direct Arrow Button to AI Chatbot Page -->
                        <a href="{{ route('resident.chatbot') }}" 
                           class="w-9 h-9 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-600/30 transition-all hover:scale-110 active:scale-95"
                           title="Open Chatbot Portal">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Dynamic Response Box (Shows answers to typed questions) -->
                    <div id="assistant-response-box" class="hidden p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 mb-3 max-h-32 overflow-y-auto">
                        <div class="font-bold text-blue-600 dark:text-blue-400 mb-1 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                            AI Assistant:
                        </div>
                        <div id="assistant-response-text" class="leading-relaxed text-[11px]"></div>
                    </div>
                </div>

                <!-- Inline Quick Input Form -->
                <form id="assistant-quick-form" onsubmit="submitAssistantMessage(event)" class="relative mt-2">
                    <input type="text" 
                           id="assistant-input"
                           placeholder="Ask AI anything..." 
                           required
                           class="w-full pl-3.5 pr-11 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-2xs">
                    <button type="submit" 
                            id="assistant-send-btn"
                            class="absolute inset-y-1 right-1 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition-colors shadow-2xs">
                        <svg class="w-3.5 h-3.5 rotate-45 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Overview Line Chart Range Data matching screenshot
        const overviewDatasets = {
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
            '1Y': {
                labels: @json($overview1YLabels),
                data: @json($overview1YData)
            }
        };

        let overviewChartInstance = null;
        let statusChartInstance = null;

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Initialize Line Chart (My Complaints Overview)
            const ctxOverview = document.getElementById('residentOverviewChart')?.getContext('2d');
            if (ctxOverview) {
                const gradient = ctxOverview.createLinearGradient(0, 0, 0, 200);
                gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
                gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

                overviewChartInstance = new Chart(ctxOverview, {
                    type: 'line',
                    data: {
                        labels: overviewDatasets['30D'].labels,
                        datasets: [{
                            label: 'Complaints',
                            data: overviewDatasets['30D'].data,
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
                                max: 8,
                                ticks: { stepSize: 2, font: { size: 10 } },
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

            // 2. Initialize Donut Chart (Complaint Status Distribution)
            const ctxStatus = document.getElementById('residentStatusChart')?.getContext('2d');
            if (ctxStatus) {
                statusChartInstance = new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'In Review', 'Resolved'],
                        datasets: [{
                            data: [{{ $displayPending }}, {{ $displayInReview }}, {{ $displayResolved }}],
                            backgroundColor: ['#F59E0B', '#3B82F6', '#10B981'],
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
        });

        function setOverviewRange(rangeKey) {
            ['7D', '30D', '6M', '1Y'].forEach(k => {
                const btn = document.getElementById(`btn-range-${k}`);
                if (btn) {
                    if (k === rangeKey) {
                        btn.className = 'px-2.5 py-1 rounded-lg bg-blue-600 text-white shadow-2xs';
                    } else {
                        btn.className = 'px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 hover:text-slate-900';
                    }
                }
            });

            if (overviewChartInstance && overviewDatasets[rangeKey]) {
                overviewChartInstance.data.labels = overviewDatasets[rangeKey].labels;
                overviewChartInstance.data.datasets[0].data = overviewDatasets[rangeKey].data;
                overviewChartInstance.update();
            }
        }

        async function submitAssistantMessage(e) {
            e.preventDefault();
            const input = document.getElementById('assistant-input');
            const btn = document.getElementById('assistant-send-btn');
            const resBox = document.getElementById('assistant-response-box');
            const resText = document.getElementById('assistant-response-text');
            const msg = input.value.trim();
            if (!msg) return;

            btn.disabled = true;
            btn.innerHTML = `<span class="animate-spin inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full"></span>`;

            resBox.classList.remove('hidden');
            resText.innerHTML = `<span class="text-slate-400 italic">Consulting Barangay North Poblacion assistant...</span>`;

            try {
                const response = await fetch("{{ route('resident.chatbot.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: msg })
                });

                const data = await response.json();
                if (data.success && data.reply) {
                    resText.innerHTML = data.reply.replace(/\n/g, '<br>');
                } else {
                    resText.innerHTML = `<span class="text-rose-500">Could not retrieve answer. Please try again.</span>`;
                }
            } catch (err) {
                resText.innerHTML = `<span class="text-slate-600 dark:text-slate-300">To submit a complaint, click "+ File New Complaint", enter your details, purok location, and photos. Our Barangay Council reviews all complaints within 24-48 hours.</span>`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-3.5 h-3.5 rotate-45 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>`;
                input.value = '';
            }
        }
    </script>
    @endpush
</x-layouts.resident>
