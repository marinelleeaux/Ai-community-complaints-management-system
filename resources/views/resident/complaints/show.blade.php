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

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
        <!-- Top Navigation & Tracking Reference -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('resident.complaints.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Back to My Complaints</span>
                </a>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white">
                        {{ $complaint->reference_number }}
                    </h2>
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }} shadow-xs">
                        {{ $badge['label'] }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-medium">Submitted on {{ $complaint->created_at->format('M d, Y h:i A') }}</span>
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
                            $isDone = $currentWeight >= $stepWeight;
                            $isCurrent = $currentWeight == $stepWeight;
                        @endphp
                        <div class="flex flex-col items-center text-center space-y-2 relative z-10">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-xs transition-all shadow-xs
                                {{ $isDone ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}
                                {{ $isCurrent ? 'ring-4 ring-emerald-500/20 animate-pulse' : '' }}">
                                @if($isDone)
                                    ✓
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </div>
                            <div>
                                <span class="text-xs font-extrabold block {{ $isDone ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">
                                    {{ $step['label'] }}
                                </span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">
                                    {{ $step['desc'] }}
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
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 text-xs">
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
            </div>

            <!-- Original Incident Description -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Original Citizen Description</h4>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line border border-slate-100 dark:border-slate-800">
                    {{ $complaint->original_description }}
                </div>
            </div>

            <!-- AI Translation & Summary Card -->
            @if($complaint->english_translation || $complaint->english_summary)
                <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50/70 to-indigo-50/70 dark:from-blue-950/40 dark:to-indigo-950/40 border border-blue-100 dark:border-blue-900/50 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-600 text-white font-bold text-[10px] flex items-center justify-center shadow-xs">AI</span>
                            <span class="text-xs font-bold text-blue-900 dark:text-blue-200">AI Civic Analysis (English Translation & Summary)</span>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">
                            Language: {{ $complaint->original_language ?? 'Cebuano/Bisaya' }}
                        </span>
                    </div>
                    @if($complaint->english_summary)
                        <div class="text-xs text-blue-950 dark:text-blue-300">
                            <strong>English Summary:</strong> {{ $complaint->english_summary }}
                        </div>
                    @endif
                    @if($complaint->priority_explanation)
                        <div class="text-xs text-amber-900 dark:text-amber-200 bg-amber-50 dark:bg-amber-950/40 p-3 rounded-xl border border-amber-200/80 dark:border-amber-800/80">
                            <strong>AI Urgency Assessment ({{ strtoupper($complaint->final_priority) }}):</strong> {{ $complaint->priority_explanation }}
                        </div>
                    @endif
                    @if($complaint->english_translation && $complaint->english_translation !== $complaint->original_description)
                        <div class="text-xs text-slate-600 dark:text-slate-400 pt-2 border-t border-blue-100 dark:border-blue-900/50">
                            <strong>Full English Translation:</strong> {{ $complaint->english_translation }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Official Barangay Remarks -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Official Barangay Remarks & Action Taken</h4>
                <div class="p-4 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 text-sm text-emerald-900 dark:text-emerald-200 leading-relaxed">
                    {{ $complaint->resident_visible_remarks ?: 'No public remarks posted yet. Your complaint is undergoing verification by the Barangay Secretariat.' }}
                </div>
            </div>

            <!-- Attached Evidence Photos / Files -->
            @if($complaint->attachments->isNotEmpty())
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Submitted Evidence & Attachments</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($complaint->attachments as $attachment)
                            <a href="{{ $attachment->url() }}" target="_blank" class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center gap-3 hover:border-blue-500 transition-colors group">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate group-hover:text-blue-600">
                                        {{ $attachment->original_filename }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">Click to view/download</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.resident>
