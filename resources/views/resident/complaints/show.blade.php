<x-layouts.resident>
    <x-slot:title>Complaint {{ $complaint->reference_number }}</x-slot:title>

    @php 
        $badge = $complaint->statusBadge(); 
        $pBadge = $complaint->priorityBadge();
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
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
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-medium">Submitted on {{ $complaint->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>

        <!-- Main Complaint Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm space-y-6">
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
                    <span class="text-slate-400 block font-medium">Purok Assignment</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $complaint->purok?->name ?? 'Purok 5' }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Specific Landmark</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $complaint->incident_location }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Date of Incident</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $complaint->incident_date ? $complaint->incident_date->format('F d, Y') : 'N/A' }}
                        @if($complaint->incident_time) ({{ $complaint->incident_time }}) @endif
                    </span>
                </div>
            </div>

            <!-- Original Incident Description -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Original Citizen Description</h4>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line">
                    {{ $complaint->original_description }}
                </div>
            </div>

            <!-- AI Translation & Summary Card -->
            @if($complaint->english_translation || $complaint->english_summary)
                <div class="p-5 rounded-2xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/50 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-600 text-white font-bold text-[10px] flex items-center justify-center">AI</span>
                            <span class="text-xs font-bold text-blue-900 dark:text-blue-200">AI Civic Analysis (English Translation & Summary)</span>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">
                            Language: {{ $complaint->original_language ?? 'Cebuano/Bisaya' }}
                        </span>
                    </div>
                    @if($complaint->english_summary)
                        <div class="text-xs text-blue-950 dark:text-blue-300">
                            <strong>Summary:</strong> {{ $complaint->english_summary }}
                        </div>
                    @endif
                    @if($complaint->english_translation && $complaint->english_translation !== $complaint->original_description)
                        <div class="text-xs text-slate-600 dark:text-slate-400 pt-2 border-t border-blue-100 dark:border-blue-900/50">
                            <strong>English Translation:</strong> {{ $complaint->english_translation }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Official Barangay Remarks -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Official Barangay Remarks & Action Taken</h4>
                <div class="p-4 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 text-sm text-emerald-900 dark:text-emerald-200">
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
                                <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 flex items-center justify-center shrink-0">
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

        <!-- Status History Timeline -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-lg font-extrabold text-[#12345B] dark:text-white mb-6">
                Complaint Status History & Timeline
            </h3>

            <div class="relative pl-6 space-y-8 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-700">
                @forelse($complaint->statusHistory as $hist)
                    <div class="relative">
                        <div class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-blue-600 border-4 border-white dark:border-[#1E293B]"></div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-sm text-[#12345B] dark:text-white uppercase tracking-wider">
                                    {{ ucfirst(str_replace('_', ' ', $hist->new_status)) }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ $hist->created_at->format('F d, Y • h:i A') }}
                                </span>
                            </div>
                            @if($hist->public_remarks)
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                                    {{ $hist->public_remarks }}
                                </p>
                            @endif
                            <span class="text-[11px] text-slate-400 mt-0.5 block">
                                Updated by: {{ $hist->changer?->name ?? 'System' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">No status updates recorded.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.resident>
