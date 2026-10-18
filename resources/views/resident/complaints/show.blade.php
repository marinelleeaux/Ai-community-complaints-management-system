<x-layouts.resident>
    <x-slot:title>Complaint {{ $complaint->reference_number }}</x-slot:title>

    @php 
        $badge = $complaint->statusBadge(); 
        $pBadge = $complaint->priorityBadge();

        $steps = [
            ['key' => 'submitted', 'label' => 'Submitted', 'desc' => 'Incident recorded'],
            ['key' => 'under_review', 'label' => 'Under Review', 'desc' => 'Assigned to official'],
            ['key' => 'in_progress', 'label' => 'In Progress', 'desc' => 'Action being taken'],
            ['key' => 'resolved', 'label' => 'Resolved', 'desc' => 'Case completed'],
        ];

        $statusWeights = [
            'submitted' => 1,
            'under_review' => 2,
            'acknowledged' => 2,
            'in_progress' => 3,
            'referred_to_responsible_office' => 3,
            'resolved' => 4,
            'closed' => 4,
            'rejected' => -1,
        ];

        $currentWeight = $statusWeights[$complaint->status] ?? 1;
    @endphp

    <!-- Print Stylesheet -->
    <style>
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-family: 'Times New Roman', Times, serif !important;
            }
            header, nav, aside, footer, #ai-floating-widget, #toast-container, .print-hidden, .print\:hidden {
                display: none !important;
            }
            .print-only, .print\:block {
                display: block !important;
            }
            .printable-record {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 10px !important;
                background: #ffffff !important;
                color: #000000 !important;
                box-shadow: none !important;
                border: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm 15mm 15mm;
            }
        }
    </style>

    <!-- ========================================================================= -->
    <!-- SCREEN INTERFACE (Hidden When Printing) -->
    <!-- ========================================================================= -->
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up print:hidden">
        <!-- Top Navigation & Tracking Reference with Print Action Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-[#1E293B] p-5 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <div>
                <a href="{{ route('resident.complaints.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Back to My Complaints</span>
                </a>
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white">
                        {{ $complaint->reference_number }}
                    </h2>
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }} shadow-xs">
                        {{ $badge['label'] }}
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold border {{ $pBadge['bg'] }} {{ $pBadge['text'] }} {{ $pBadge['border'] }} shadow-2xs">
                        Priority: {{ $pBadge['label'] }}
                    </span>
                </div>
                <div class="text-xs text-slate-400 mt-1">
                    Submitted on {{ $complaint->created_at->format('M d, Y • h:i A') }}
                </div>
            </div>

            <!-- Action Buttons: Edit, Delete, Print -->
            <div class="flex items-center gap-2 flex-wrap">
                @if(in_array($complaint->status, ['submitted', 'under_review']))
                    <!-- Edit Button -->
                    <a href="{{ route('resident.complaints.edit', $complaint->id) }}" 
                       onclick="return confirmAction(event, 'Edit this complaints?', 'Edit Complaint', 'Yes, Edit', false);"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900 font-extrabold text-xs transition-all shadow-2xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit</span>
                    </a>

                    <!-- Delete Button -->
                    <form method="POST" action="{{ route('resident.complaints.destroy', $complaint->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirmAction(event, 'Are you sure you want to delete?', 'Delete Complaint', 'Yes, Delete', true);"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900 font-extrabold text-xs transition-all shadow-2xs cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span>Delete</span>
                        </button>
                    </form>
                @endif

                <button type="button" 
                        onclick="window.print()" 
                        class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-slate-900 via-blue-900 to-[#12345B] hover:from-black hover:via-blue-950 hover:to-slate-900 text-white font-extrabold text-xs shadow-md shadow-blue-950/20 hover:shadow-lg hover:scale-102 active:scale-95 transition-all border border-blue-400/20 group cursor-pointer"
                        title="Print Official Resident Copy">
                    <div class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-amber-400 group-hover:text-slate-950 text-amber-300 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </div>
                    <span>Print Official Resident Copy</span>
                </button>
            </div>
        </div>

        <!-- Progress Timeline Tracker (Visual Workflow Indicator) -->
        @if($complaint->status !== 'rejected')
            <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift">
                <h4 class="text-xs font-extrabold text-[#12345B] dark:text-white uppercase tracking-wider mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Resolution Lifecycle Tracking</span>
                </h4>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative">
                    @foreach($steps as $idx => $step)
                        @php
                            $stepWeight = $idx + 1;
                            $isDone = $currentWeight > $stepWeight;
                            $isCurrent = $currentWeight == $stepWeight;
                            $isPending = ($complaint->status === 'submitted' && $step['key'] === 'submitted');
                        @endphp
                        <div class="flex flex-col items-center text-center space-y-2 relative z-10">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-bold text-xs transition-all shadow-xs
                                {{ $isDone ? 'bg-emerald-600 text-white shadow-emerald-500/20' : ($isPending ? 'bg-amber-500 text-white ring-4 ring-amber-500/20 animate-pulse' : ($isCurrent ? 'bg-blue-600 text-white ring-4 ring-blue-500/20 animate-pulse' : 'bg-slate-100 dark:bg-slate-800 text-slate-400')) }}">
                                @if($isDone)
                                    ✓
                                @elseif($isPending)
                                    <span class="text-[9px] font-black tracking-tight uppercase">Pending</span>
                                @elseif($isCurrent)
                                    <span class="text-[9px] font-black tracking-tight uppercase">Active</span>
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </div>
                            <div>
                                <span class="text-xs font-extrabold block {{ ($isDone || $isCurrent) ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">
                                    @if($isPending)
                                        Pending Review
                                    @else
                                        {{ $step['label'] }}
                                    @endif
                                </span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">
                                    @if($isPending)
                                        Awaiting barangay action
                                    @else
                                        {{ $step['desc'] }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Rejection Alert Banner -->
            <div class="bg-rose-50 dark:bg-rose-950/40 rounded-3xl p-5 border border-rose-200 dark:border-rose-900/60 flex items-center gap-3.5 shadow-sm">
                <span class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black text-sm shrink-0">
                    ✕
                </span>
                <div>
                    <h4 class="text-xs font-bold text-rose-900 dark:text-rose-200 uppercase tracking-wider">Complaint Status: Rejected / Closed</h4>
                    <p class="text-xs text-rose-800 dark:text-rose-300 mt-0.5">This report has been reviewed and dismissed by the Barangay Secretariat. Please check the official remarks below for the reason.</p>
                </div>
            </div>
        @endif

        <!-- Main Complaint Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm space-y-6 hover-lift">
            <!-- Title & Basic Info -->
            <div class="pb-6 border-b border-slate-100 dark:border-slate-800">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        {{ $complaint->category?->name ?? 'General Inquiry' }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $pBadge['bg'] }} {{ $pBadge['text'] }} border {{ $pBadge['border'] }}">
                        Priority: {{ $pBadge['label'] }}
                    </span>
                </div>
                <h3 class="text-xl font-extrabold text-[#12345B] dark:text-white mt-1">
                    {{ $complaint->title }}
                </h3>
            </div>

            <!-- Incident Location & Time Metadata -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 text-xs">
                <div>
                    <span class="text-slate-400 block font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Purok Assignment
                    </span>
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $complaint->purok?->name ?? 'Purok 5' }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Specific Landmark
                    </span>
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $complaint->incident_location }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Date of Incident
                    </span>
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $complaint->incident_date ? $complaint->incident_date->format('F d, Y') : 'N/A' }}
                        @if($complaint->incident_time) ({{ $complaint->incident_time }}) @endif
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Date & Time Submitted
                    </span>
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $complaint->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('M d, Y') }}
                    </span>
                    <span class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold block">
                        {{ $complaint->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('h:i:s A') }} ({{ $complaint->created_at->diffForHumans() }})
                    </span>
                </div>
            </div>

            <!-- Original Incident Description -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Original Citizen Description</h4>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line border border-slate-100 dark:border-slate-800">
                    {{ $complaint->original_description }}
                </div>
            </div>

            <!-- AI Translation & Summary Card -->
            <div class="p-5 md:p-6 rounded-2xl bg-gradient-to-br from-blue-50/90 via-indigo-50/60 to-purple-50/40 dark:from-[#0E1D38] dark:via-[#132547] dark:to-[#171A38] border border-blue-200/80 dark:border-blue-900/60 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-blue-200/60 dark:border-blue-900/60">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">AI</span>
                        <div>
                            <h4 class="text-sm font-extrabold text-blue-950 dark:text-white">
                                AI Civic Analysis & Translation
                            </h4>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">Automated multi-dialect translation and priority triage</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300">
                            {{ $complaint->original_language ?? 'Cebuano/Bisaya' }}
                        </span>
                        @if($complaint->ai_analysis_status)
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $complaint->ai_analysis_status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($complaint->ai_analysis_status) }}
                            </span>
                        @endif
                    </div>
                </div>

                @if($complaint->english_summary)
                    <div class="p-4 rounded-xl bg-white/80 dark:bg-slate-900/80 border border-blue-100 dark:border-blue-900/50">
                        <div class="text-[10px] font-black text-blue-700 dark:text-blue-300 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>English Summary (AI-Generated)</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-relaxed">{{ $complaint->english_summary }}</p>
                    </div>
                @endif

                @if($complaint->priority_explanation)
                    @php
                        $priorityBadgeStyles = [
                            'urgent' => 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900/60 text-rose-800 dark:text-rose-200',
                            'high' => 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-900/60 text-amber-800 dark:text-amber-200',
                            'medium' => 'bg-blue-50 dark:bg-blue-950/40 border-blue-200 dark:border-blue-900/60 text-blue-800 dark:text-blue-200',
                            'low' => 'bg-slate-50 dark:bg-slate-900/60 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300',
                        ];
                        $pExplanationStyle = $priorityBadgeStyles[$complaint->final_priority] ?? $priorityBadgeStyles['medium'];
                    @endphp
                    <div class="p-3.5 rounded-xl border {{ $pExplanationStyle }} text-xs">
                        <div class="font-extrabold uppercase text-[10px] tracking-wider mb-1 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full {{ in_array($complaint->final_priority, ['urgent', 'high']) ? 'bg-rose-500 animate-pulse' : 'bg-blue-500' }}"></span>
                            <span>AI Urgency Assessment: {{ strtoupper($complaint->final_priority) }} Priority</span>
                        </div>
                        <p class="leading-relaxed">{{ $complaint->priority_explanation }}</p>
                    </div>
                @endif

                @if($complaint->english_translation && $complaint->english_translation !== $complaint->original_description)
                    <div class="p-3.5 rounded-xl bg-white/60 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block mb-1">Full English Translation:</span>
                        <p class="leading-relaxed">{{ $complaint->english_translation }}</p>
                    </div>
                @endif
            </div>

            <!-- Official Barangay Remarks -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Official Barangay Remarks & Action Taken</h4>
                <div class="p-4 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 text-sm text-emerald-900 dark:text-emerald-200 leading-relaxed">
                    {{ $complaint->resident_visible_remarks ?: 'No public remarks posted yet. Your complaint is undergoing verification by the Barangay Secretariat.' }}
                </div>
            </div>

            <!-- Complaint Revision History (Before & After) -->
            @if(!empty($complaint->edit_history) && count($complaint->edit_history) > 0)
                <div class="p-5 sm:p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/80 border border-slate-200/90 dark:border-slate-800 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2 pb-3 border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-[#12345B] dark:text-white flex items-center gap-2">
                                    <span>Complaint Edit History (Before & After)</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300">
                                        {{ count($complaint->edit_history) }} {{ count($complaint->edit_history) === 1 ? 'Revision' : 'Revisions' }}
                                    </span>
                                </h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    Tracked modifications made to this incident report.
                                </p>
                            </div>
                        </div>
                    </div>

                    @foreach(array_reverse($complaint->edit_history) as $idx => $rev)
                        <div class="p-4 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200/80 dark:border-slate-800/80 space-y-3">
                            <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-100 dark:border-slate-800">
                                <span class="font-bold text-slate-700 dark:text-slate-300">
                                    Revision on {{ $rev['edited_at_formatted'] ?? 'Recent' }}
                                </span>
                                <span class="text-[11px] text-slate-400">
                                    Edited by: <strong class="text-slate-700 dark:text-slate-200">{{ $rev['editor_name'] ?? 'Complainant' }}</strong>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- BEFORE COLUMN -->
                                <div class="p-3.5 rounded-2xl bg-rose-50/60 dark:bg-rose-950/20 border border-rose-200/60 dark:border-rose-900/40 space-y-2.5">
                                    <div class="flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-rose-700 dark:text-rose-300 pb-1.5 border-b border-rose-200/50 dark:border-rose-900/30">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Before (Previous)</span>
                                    </div>
                                    <div class="space-y-2 text-xs">
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Title</span>
                                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['before']['title'] ?? 'N/A' }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Category</span>
                                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['before']['category_name'] ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Purok</span>
                                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['before']['purok_name'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Date</span>
                                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['before']['incident_date'] ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Location</span>
                                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['before']['incident_location'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Citizen Statement</span>
                                            <p class="font-normal text-slate-700 dark:text-slate-300 whitespace-pre-line bg-white/80 dark:bg-slate-900/80 p-2.5 rounded-xl border border-rose-200/50 dark:border-rose-900/30 text-[11px] leading-relaxed">{{ $rev['before']['original_description'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- AFTER COLUMN -->
                                <div class="p-3.5 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-200/60 dark:border-emerald-900/40 space-y-2.5">
                                    <div class="flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-300 pb-1.5 border-b border-emerald-200/50 dark:border-emerald-900/30">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>After (Edited Version)</span>
                                    </div>
                                    <div class="space-y-2 text-xs">
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Title</span>
                                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['after']['title'] ?? 'N/A' }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Category</span>
                                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['after']['category_name'] ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Purok</span>
                                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['after']['purok_name'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Date</span>
                                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['after']['incident_date'] ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Location</span>
                                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $rev['after']['incident_location'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Citizen Statement</span>
                                            <p class="font-normal text-slate-700 dark:text-slate-300 whitespace-pre-line bg-white/80 dark:bg-slate-900/80 p-2.5 rounded-xl border border-emerald-200/50 dark:border-emerald-900/30 text-[11px] leading-relaxed">{{ $rev['after']['original_description'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- ============================================================= -->
            <!-- PHOTO EVIDENCE & ATTACHMENTS GALLERY -->
            <!-- ============================================================= -->
            @if($complaint->attachments->isNotEmpty())
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Uploaded Photos & Supporting Evidence ({{ $complaint->attachments->count() }})</span>
                        </h4>
                        <span class="text-[11px] text-slate-400">Click photo to view full resolution</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($complaint->attachments as $attachment)
                            @if($attachment->isImage())
                                <!-- Photo Card with Image Preview -->
                                <div class="group relative rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 transition-all hover:shadow-lg">
                                    <div class="aspect-video w-full overflow-hidden bg-slate-950 flex items-center justify-center relative cursor-pointer"
                                         onclick="openImageLightbox('{{ $attachment->url() }}', '{{ addslashes($attachment->original_filename) }}')">
                                        <img src="{{ $attachment->url() }}" 
                                             alt="{{ $attachment->original_filename }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='{{ asset('images/announcement-placeholder.svg') }}'">
                                        
                                        <!-- Hover overlay indicator -->
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white">
                                            <svg class="w-6 h-6 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                            <span class="text-xs font-bold drop-shadow-md">View Full Size</span>
                                        </div>
                                    </div>

                                    <div class="p-3 flex items-center justify-between text-xs border-t border-slate-100 dark:border-slate-800">
                                        <div class="min-w-0 pr-2">
                                            <p class="font-bold text-slate-700 dark:text-slate-200 truncate">{{ $attachment->original_filename }}</p>
                                            <p class="text-[10px] text-slate-400">Photo Evidence • {{ number_format(($attachment->file_size ?: 12450) / 1024, 0) }} KB</p>
                                        </div>
                                        <a href="{{ $attachment->url() }}" download="{{ $attachment->original_filename }}" target="_blank" 
                                           class="p-2 rounded-xl bg-slate-100 hover:bg-blue-50 dark:bg-slate-800 dark:hover:bg-blue-900/40 text-slate-600 hover:text-blue-600 dark:text-slate-300 transition-colors shrink-0" 
                                           title="Download Photo">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- Non-image document attachment -->
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-between hover:border-blue-500 transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs">
                                            DOC
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $attachment->original_filename }}</p>
                                            <p class="text-[10px] text-slate-400">{{ number_format(($attachment->file_size ?: 1024) / 1024, 0) }} KB</p>
                                        </div>
                                    </div>
                                    <a href="{{ $attachment->url() }}" target="_blank" download class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shrink-0 transition-colors">
                                        Download
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- DEDICATED PRINTABLE OFFICIAL BARANGAY RECORD (Print View Only) -->
    <!-- ========================================================================= -->
    <div class="hidden print:block printable-record bg-white text-black p-4 font-serif">
        <!-- Official Letterhead Header with Single Official Logo -->
        <div class="border-b-4 border-double border-slate-900 pb-3 mb-4 text-center">
            <!-- Single Official Seal Icon -->
            <div class="w-20 h-20 mx-auto mb-2 flex items-center justify-center">
                <img src="{{ asset('images/barangay-logo.svg') }}" alt="Barangay North Poblacion Seal" class="w-20 h-20 object-contain">
            </div>

            <!-- Republic & Barangay Heading -->
            <div>
                <p class="text-[11px] uppercase tracking-widest text-slate-800 font-serif">Republic of the Philippines</p>
                <p class="text-[11px] uppercase tracking-widest text-slate-800 font-serif">Province of Bukidnon • Municipality of Maramag</p>
                <h1 class="text-xl font-black uppercase text-slate-950 tracking-wider font-serif mt-0.5">
                    BARANGAY NORTH POBLACION
                </h1>
                <p class="text-xs font-bold text-slate-900 italic font-serif">
                    OFFICE OF THE PUNONG BARANGAY • CITIZEN GRIEVANCE & COMPLAINT DESK
                </p>
                <p class="text-[9px] text-slate-600 tracking-normal mt-0.5">
                    Barangay Hall Complex, Sayre Highway, North Poblacion, Maramag, Bukidnon 8714
                </p>
            </div>
        </div>

        <!-- Document Title & Case Metadata -->
        <div class="text-center my-3">
            <h2 class="text-base font-black uppercase tracking-wider text-black border-y border-slate-900 py-1 inline-block px-8">
                CITIZEN OFFICIAL COMPLAINT & ACKNOWLEDGEMENT DOCKET
            </h2>
            <div class="flex justify-between items-center text-[10px] text-slate-700 mt-2 px-1">
                <span>DOCKET REF NO: <strong class="text-xs text-black">{{ $complaint->reference_number }}</strong></span>
                <span>DATE FILED: <strong class="text-black">{{ $complaint->created_at->format('F d, Y • h:i A') }}</strong></span>
                <span>STATUS: <strong class="text-black uppercase">{{ strtoupper(str_replace('_', ' ', $complaint->status)) }}</strong></span>
            </div>
        </div>

        <!-- Case Summary Information Table -->
        <table class="w-full text-[11px] border-collapse border border-slate-900 mb-4">
            <tbody>
                <tr class="border-b border-slate-900">
                    <td class="w-1/4 p-2 bg-slate-100 font-bold border-r border-slate-900">Complainant (Citizen):</td>
                    <td class="w-1/4 p-2 border-r border-slate-900">{{ $complaint->resident?->name }}</td>
                    <td class="w-1/4 p-2 bg-slate-100 font-bold border-r border-slate-900">Contact Number:</td>
                    <td class="w-1/4 p-2">{{ $complaint->resident?->phone }}</td>
                </tr>
                <tr class="border-b border-slate-900">
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Purok Area:</td>
                    <td class="p-2 border-r border-slate-900">{{ $complaint->purok?->name ?? 'Purok 5' }}</td>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Incident Location:</td>
                    <td class="p-2">{{ $complaint->incident_location }}</td>
                </tr>
                <tr class="border-b border-slate-900">
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Date of Incident:</td>
                    <td class="p-2 border-r border-slate-900 font-bold">
                        {{ $complaint->incident_date ? $complaint->incident_date->format('F d, Y') : 'N/A' }}
                    </td>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Time of Incident:</td>
                    <td class="p-2 font-bold">
                        @if($complaint->incident_time)
                            {{ \Carbon\Carbon::parse($complaint->incident_time)->format('h:i A') }}
                        @else
                            <span class="italic font-normal text-slate-600">Unspecified by Affiant</span>
                        @endif
                    </td>
                </tr>
                <tr class="border-b border-slate-900">
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Date & Time Filed:</td>
                    <td class="p-2 border-r border-slate-900 font-bold">
                        {{ $complaint->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('F d, Y • h:i:s A') }}
                    </td>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Classification:</td>
                    <td class="p-2 font-bold">{{ $complaint->category?->name ?? 'General Civic Concern' }}</td>
                </tr>
                <tr>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Priority Assessment:</td>
                    <td class="p-2 border-r border-slate-900 font-bold uppercase">{{ strtoupper($complaint->final_priority) }}</td>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Resident Address:</td>
                    <td class="p-2 truncate">{{ $complaint->resident?->address }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Citizen Narrative & Statement -->
        <div class="mb-4 border border-slate-900">
            <div class="bg-slate-100 p-2 font-bold text-xs uppercase border-b border-slate-900 flex justify-between">
                <span>I. STATEMENT OF INCIDENT / COMPLAINT NARRATIVE</span>
                <span class="text-[10px] font-normal italic">Language: {{ $complaint->original_language ?? 'Cebuano/Bisaya' }}</span>
            </div>
            <div class="p-3 text-xs leading-relaxed whitespace-pre-line text-justify">
                <p class="font-bold mb-1">Subject: {{ $complaint->title }}</p>
                {{ $complaint->original_description }}
            </div>
        </div>

        @if($complaint->english_summary)
            <div class="mb-4 border border-slate-900">
                <div class="bg-slate-100 p-2 font-bold text-xs uppercase border-b border-slate-900">
                    II. ENGLISH SYNOPSIS
                </div>
                <div class="p-3 text-xs leading-relaxed">
                    <p>{{ $complaint->english_summary }}</p>
                </div>
            </div>
        @endif

        <!-- Official Barangay Actions Taken -->
        <div class="mb-4 border border-slate-900">
            <div class="bg-slate-100 p-2 font-bold text-xs uppercase border-b border-slate-900">
                III. OFFICIAL ACTION & REMARKS POSTED BY BARANGAY
            </div>
            <div class="p-3 text-xs leading-relaxed">
                <p>{{ $complaint->resident_visible_remarks ?: 'Under evaluation and verification by the Barangay Secretariat.' }}</p>
            </div>
        </div>

        <!-- Attached Photographic Evidence in Print -->
        @if($complaint->attachments->isNotEmpty())
            <div class="mb-4 border border-slate-900 page-break-inside-avoid">
                <div class="bg-slate-100 p-2 font-bold text-xs uppercase border-b border-slate-900">
                    IV. ATTACHED PHOTOGRAPHIC EVIDENCE
                </div>
                <div class="p-3 grid grid-cols-2 gap-3">
                    @foreach($complaint->attachments as $attachment)
                        @if($attachment->isImage())
                            <div class="border border-slate-400 p-2 text-center">
                                <img src="{{ $attachment->url() }}" alt="Case Photographic Evidence" class="h-44 w-full object-contain mx-auto mb-1">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-800">Exhibit {{ chr(65 + $loop->index) }} - Photographic Evidence</p>
                            </div>
                        @else
                            <div class="border border-slate-400 p-2 flex items-center gap-2">
                                <span class="font-bold text-xs">[DOCUMENT ATTACHMENT]</span>
                                <span class="text-[10px]">Official Case Exhibit File #{{ $loop->iteration }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Signatory Certification Blocks -->
        <div class="mt-8 pt-4 border-t-2 border-slate-900 page-break-inside-avoid">
            <div class="grid grid-cols-3 gap-6 text-center text-xs">
                <!-- Complainant Signature (Complainant Name Displayed) -->
                <div class="flex flex-col justify-end">
                    <div class="border-b border-slate-900 w-3/4 mx-auto mb-1"></div>
                    <p class="font-bold text-[11px] uppercase">{{ $complaint->resident?->name }}</p>
                    <p class="text-[9px] text-slate-700">Complainant / Citizen Signature</p>
                </div>

                <!-- Barangay Administrator (Administrator Attesting Desk) -->
                <div class="flex flex-col justify-end">
                    <div class="border-b border-slate-900 w-3/4 mx-auto mb-1"></div>
                    <p class="font-bold text-[11px] uppercase">BARANGAY ADMINISTRATOR</p>
                    <p class="text-[9px] text-slate-700">Barangay Administrator / Attesting Officer</p>
                </div>

                <!-- Punong Barangay (No Name Printed - Official Line Only) -->
                <div class="flex flex-col justify-end">
                    <div class="border-b border-slate-900 w-3/4 mx-auto mb-1"></div>
                    <p class="font-bold text-[11px] uppercase">&nbsp;</p>
                    <p class="text-[9px] text-slate-700">Punong Barangay / Lupon Chairman</p>
                </div>
            </div>

            <!-- Tracking Footer -->
            <div class="flex justify-between items-center text-[9px] text-slate-500 mt-6 pt-2 border-t border-slate-200">
                <span>Official Tracking Number: {{ $complaint->reference_number }} • Citizen Copy</span>
                <span>Keep this printed copy for legal reference and verification with the Barangay Lupon</span>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- LIGHTBOX MODAL FOR FULL-SIZE IMAGE PREVIEWS -->
    <!-- ========================================================================= -->
    <div id="image-lightbox-modal" 
         class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden items-center justify-center p-4 transition-all"
         onclick="closeImageLightbox()">
        <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between w-full text-white mb-2 px-2">
                <span id="lightbox-filename" class="text-xs font-bold truncate max-w-md"></span>
                <div class="flex items-center gap-2">
                    <a id="lightbox-download-link" href="#" download class="px-3 py-1 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition-colors">
                        Download
                    </a>
                    <button type="button" onclick="closeImageLightbox()" class="p-1.5 rounded-xl bg-white/20 hover:bg-white/30 text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <img id="lightbox-image" src="" alt="Full preview" class="max-w-full max-h-[80vh] rounded-2xl object-contain shadow-2xl">
        </div>
    </div>

    @push('scripts')
    <script>
        function openImageLightbox(src, filename) {
            const modal = document.getElementById('image-lightbox-modal');
            const img = document.getElementById('lightbox-image');
            const nameEl = document.getElementById('lightbox-filename');
            const downloadLink = document.getElementById('lightbox-download-link');
            
            img.src = src;
            nameEl.textContent = filename;
            downloadLink.href = src;
            downloadLink.download = filename;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageLightbox() {
            const modal = document.getElementById('image-lightbox-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageLightbox();
            }
        });
    </script>
    @endpush
</x-layouts.resident>
