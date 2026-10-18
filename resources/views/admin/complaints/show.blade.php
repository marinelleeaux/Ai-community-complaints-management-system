<x-layouts.admin>
    <x-slot:header>Complaint Review: {{ $complaint->reference_number }}</x-slot:header>

    @php 
        $badge = $complaint->statusBadge(); 
        $pBadge = $complaint->priorityBadge();
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
    <div class="print:hidden space-y-6">
        <!-- Top Back Link & Status Header with Print Action Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-[#111C33] p-5 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div>
                <a href="{{ route('admin.complaints.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Back to Complaints Queue</span>
                </a>
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-2xl font-black text-[#12345B] dark:text-white tracking-tight">{{ $complaint->reference_number }}</h2>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }} shadow-2xs">
                        {{ $badge['label'] }}
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold border {{ $pBadge['bg'] }} {{ $pBadge['text'] }} {{ $pBadge['border'] }} shadow-2xs">
                        Priority: {{ $pBadge['label'] }}
                    </span>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                    <img src="{{ $complaint->resident?->avatarUrl() }}" alt="{{ $complaint->resident?->name }}" class="w-5 h-5 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-700 shrink-0">
                    <span>Submitted by <strong class="text-slate-700 dark:text-slate-200">{{ $complaint->resident?->name }}</strong> on {{ $complaint->created_at->format('M d, Y • h:i A') }}</span>
                </div>
            </div>

            <!-- Print Official Record Button -->
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="window.print()" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#12345B] via-blue-700 to-indigo-700 hover:from-[#0B2340] hover:to-blue-900 text-white font-extrabold text-xs shadow-md shadow-blue-900/20 hover:scale-[1.02] active:scale-95 transition-all">
                    <svg class="w-4 h-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Print Official Complaint Record</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Complaint Details, Photos, AI Analysis, Status Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Original Complaint Overview -->
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-5">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-400 mb-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-bold border border-blue-200 dark:border-blue-800">{{ $complaint->category?->name ?? 'General' }}</span>
                            <span>•</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $complaint->purok?->name ?? 'Purok 5' }}</span>
                            <span>•</span>
                            <span>{{ $complaint->incident_location }}</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#12345B] dark:text-white">
                            {{ $complaint->title }}
                        </h3>
                    </div>

                    <!-- Incident Date and Landmark -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-xs">
                        <div>
                            <span class="text-slate-400 block font-medium">Incident Date</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">
                                {{ $complaint->incident_date ? $complaint->incident_date->format('M d, Y') : 'N/A' }}
                                @if($complaint->incident_time) ({{ $complaint->incident_time }}) @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Purok Area</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $complaint->purok?->name ?? 'Purok 5' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Specific Landmark</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 truncate block">{{ $complaint->incident_location }}</span>
                        </div>
                    </div>

                    <!-- Citizen Description -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Original Citizen Description</h4>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line border border-slate-100 dark:border-slate-800">
                            {{ $complaint->original_description }}
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- PHOTO EVIDENCE & ATTACHMENTS GALLERY -->
                    <!-- ========================================================= -->
                    @if($complaint->attachments->isNotEmpty())
                        <div class="pt-2">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Uploaded Evidence & Photographic Records ({{ $complaint->attachments->count() }})</span>
                                </h4>
                                <span class="text-[11px] text-slate-400">Click photo to inspect full size</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($complaint->attachments as $att)
                                    @if($att->isImage())
                                        <!-- Photo Card with Image Preview -->
                                        <div class="group relative rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 transition-all hover:shadow-lg">
                                            <div class="aspect-video w-full overflow-hidden bg-slate-950 flex items-center justify-center relative cursor-pointer"
                                                 onclick="openImageLightbox('{{ $att->url() }}', '{{ addslashes($att->original_filename) }}')">
                                                <img src="{{ $att->url() }}" 
                                                     alt="{{ $att->original_filename }}" 
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
                                                    <p class="font-bold text-slate-700 dark:text-slate-200 truncate">{{ $att->original_filename }}</p>
                                                    <p class="text-[10px] text-slate-400">Photo Evidence • {{ number_format(($att->file_size ?: 12450) / 1024, 0) }} KB</p>
                                                </div>
                                                <a href="{{ $att->url() }}" download="{{ $att->original_filename }}" target="_blank" 
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
                                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $att->original_filename }}</p>
                                                    <p class="text-[10px] text-slate-400">{{ number_format(($att->file_size ?: 1024) / 1024, 0) }} KB</p>
                                                </div>
                                            </div>
                                            <a href="{{ $att->url() }}" target="_blank" download class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shrink-0 transition-colors">
                                                Download
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-dashed border-slate-200 dark:border-slate-800 text-center text-xs text-slate-400">
                            No physical photos or documents were attached with this complaint.
                        </div>
                    @endif
                </div>

                <!-- AI Analysis Card -->
                <div class="bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-[#0E1D38] dark:to-[#132547] rounded-3xl p-6 md:p-8 border border-blue-200/80 dark:border-blue-900/60 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-blue-200/60 dark:border-blue-900/60">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center shadow-xs">AI</span>
                            <h3 class="text-base font-extrabold text-blue-950 dark:text-white">
                                AI Civic Analysis & Triage Recommendations
                            </h3>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300">
                            {{ ucfirst($complaint->ai_analysis_status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Detected Language:</span>
                            <span class="font-extrabold text-slate-800 dark:text-slate-200 ml-1">
                                {{ $complaint->original_language ?? 'Cebuano/Bisaya' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 font-medium">AI Suggested Category:</span>
                            <span class="font-extrabold text-slate-800 dark:text-slate-200 ml-1">
                                {{ $complaint->ai_suggested_category ?? $complaint->category?->name }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 font-medium">AI Suggested Priority:</span>
                            <span class="font-extrabold text-slate-800 dark:text-slate-200 uppercase ml-1">
                                {{ $complaint->ai_suggested_priority ?? 'medium' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Final Applied Priority:</span>
                            <span class="font-extrabold text-slate-800 dark:text-slate-200 uppercase ml-1">
                                {{ $complaint->final_priority }}
                            </span>
                        </div>
                    </div>

                    @if($complaint->english_summary)
                        <div class="p-3.5 rounded-2xl bg-white/70 dark:bg-slate-900/70 border border-blue-100 dark:border-blue-900/40 text-xs">
                            <span class="font-bold text-blue-950 dark:text-blue-300">English Summary:</span>
                            <p class="mt-0.5 text-slate-700 dark:text-slate-300">{{ $complaint->english_summary }}</p>
                        </div>
                    @endif

                    @if($complaint->english_translation && $complaint->english_translation !== $complaint->original_description)
                        <div class="p-3.5 rounded-2xl bg-white/70 dark:bg-slate-900/70 border border-blue-100 dark:border-blue-900/40 text-xs">
                            <span class="font-bold text-blue-950 dark:text-blue-300">English Translation:</span>
                            <p class="mt-0.5 text-slate-700 dark:text-slate-300">{{ $complaint->english_translation }}</p>
                        </div>
                    @endif

                    @if($complaint->priority_explanation)
                        <div class="text-xs text-slate-600 dark:text-slate-400 italic">
                            💡 <strong>AI Justification:</strong> {{ $complaint->priority_explanation }}
                        </div>
                    @endif

                    <!-- Override AI Category & Priority Form -->
                    <div class="pt-3 border-t border-blue-200/60 dark:border-blue-900/60">
                        <button type="button" 
                                onclick="document.getElementById('override-ai-form').classList.toggle('hidden')"
                                class="text-xs font-bold text-blue-700 dark:text-blue-400 hover:underline">
                            ✎ Override AI Category & Priority Recommendations
                        </button>

                        <form id="override-ai-form" method="POST" action="{{ route('admin.complaints.override-ai', $complaint->id) }}" class="hidden mt-3 p-4 bg-white dark:bg-[#111C33] rounded-2xl border border-blue-200 dark:border-slate-800 space-y-3">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Override Category</label>
                                    <select name="category_id" class="w-full p-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $complaint->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Override Priority</label>
                                    <select name="final_priority" class="w-full p-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white">
                                        <option value="low" {{ $complaint->final_priority === 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $complaint->final_priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $complaint->final_priority === 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ $complaint->final_priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                                Save Override
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Complaint Revision History (Before & After) -->
                @if(!empty($complaint->edit_history) && count($complaint->edit_history) > 0)
                    <div class="p-6 md:p-8 rounded-3xl bg-white dark:bg-[#111C33] border border-slate-200/90 dark:border-slate-800 shadow-xs space-y-4">
                        <div class="flex items-center justify-between flex-wrap gap-2 pb-3 border-b border-slate-200 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="p-1.5 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-base font-extrabold text-[#12345B] dark:text-white flex items-center gap-2">
                                        <span>Complainant Edit History (Before & After)</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300">
                                            {{ count($complaint->edit_history) }} {{ count($complaint->edit_history) === 1 ? 'Revision' : 'Revisions' }}
                                        </span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        The complainant modified their submitted incident report. Review the changes below.
                                    </p>
                                </div>
                            </div>
                        </div>

                        @foreach(array_reverse($complaint->edit_history) as $idx => $rev)
                            <div class="p-4 rounded-2xl bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200/70 dark:border-slate-800/80 space-y-3">
                                <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-200/60 dark:border-slate-800">
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

                <!-- Update Status & Public Remarks Form -->
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-5">
                    <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">
                        Update Complaint Status & Official Remarks
                    </h3>

                    <form method="POST" action="{{ route('admin.complaints.update-status', $complaint->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">New Workflow Status</label>
                            <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs font-bold text-[#12345B] dark:text-white">
                                <option value="submitted" {{ $complaint->status === 'submitted' ? 'selected' : '' }}>Submitted (Pending Triage)</option>
                                <option value="under_review" {{ $complaint->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="acknowledged" {{ $complaint->status === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                <option value="in_progress" {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="referred_to_responsible_office" {{ $complaint->status === 'referred_to_responsible_office' ? 'selected' : '' }}>Referred to Responsible Office</option>
                                <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="rejected" {{ $complaint->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">
                                Public Remarks (Visible to Resident in Real-Time)
                            </label>
                            <textarea name="public_remarks" rows="3" placeholder="Provide actionable feedback or instructions to the resident..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100">{{ $complaint->resident_visible_remarks }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">
                                Confidential Internal Admin Note (Optional - Hidden from Resident)
                            </label>
                            <textarea name="internal_notes" rows="2" placeholder="Record internal official communication, contractor names, or sensitive notes..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100"></textarea>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-6 py-3 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white font-bold text-xs shadow-md transition-all">
                                Update Status & Notify Resident
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Col: Resident Info, Assign Official, Internal Notes History -->
            <div class="space-y-6">
                <!-- Resident Profile Info -->
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-4">
                    <h3 class="text-sm font-extrabold text-[#12345B] dark:text-white uppercase tracking-wider">
                        Complainant Details
                    </h3>
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-10 h-10 rounded-2xl overflow-hidden bg-blue-100 dark:bg-blue-900/40 text-blue-600 font-bold text-sm flex items-center justify-center shrink-0">
                            @if($complaint->resident?->avatar_path)
                                <img src="{{ $complaint->resident->avatarUrl() }}" alt="{{ $complaint->resident->name }}" class="w-full h-full object-cover">
                            @else
                                {{ $complaint->resident?->initials() }}
                            @endif
                        </div>
                        <div>
                            <div class="font-extrabold text-sm text-slate-900 dark:text-white">{{ $complaint->resident?->name }}</div>
                            <div class="text-xs text-slate-400">{{ $complaint->resident?->email }}</div>
                        </div>
                    </div>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Phone:</span>
                            <span class="font-bold text-slate-700 dark:text-slate-200">{{ $complaint->resident?->phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Purok:</span>
                            <span class="font-bold text-slate-700 dark:text-slate-200">{{ $complaint->resident?->purok?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Address:</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right max-w-44 truncate">{{ $complaint->resident?->address }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Account Status:</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 uppercase text-[10px]">Verified Resident</span>
                        </div>
                    </div>
                </div>

                <!-- Assign Official Form -->
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3">
                    <h3 class="text-sm font-extrabold text-[#12345B] dark:text-white uppercase tracking-wider">
                        Assigned Official
                    </h3>
                    <form method="POST" action="{{ route('admin.complaints.assign', $complaint->id) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <select name="assigned_to" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-white">
                            <option value="">Select official / barangay officer</option>
                            @foreach($officials as $off)
                                <option value="{{ $off->id }}" {{ $complaint->assigned_to == $off->id ? 'selected' : '' }}>
                                    {{ $off->name }} ({{ ucfirst($off->role) }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-colors">
                            Update Assignment
                        </button>
                    </form>
                </div>

                <!-- Confidential Internal Notes (Hidden from Resident) -->
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-4">
                    <h3 class="text-sm font-extrabold text-[#12345B] dark:text-white uppercase tracking-wider">
                        Confidential Internal Notes
                    </h3>
                    <p class="text-[11px] text-amber-600 dark:text-amber-400 font-medium">
                        🔒 Confidential: Visible strictly to authorized officials. Never sent to resident.
                    </p>

                    <form method="POST" action="{{ route('admin.complaints.add-note', $complaint->id) }}" class="space-y-2">
                        @csrf
                        <textarea name="note_content" rows="2" placeholder="Add confidential internal memo..." required class="w-full p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-white"></textarea>
                        <button type="submit" class="w-full py-2 bg-[#12345B] hover:bg-[#0B2340] text-white rounded-xl text-xs font-bold">
                            Save Note
                        </button>
                    </form>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800 pt-2 space-y-2 max-h-60 overflow-y-auto">
                        @forelse($complaint->internalNotes as $note)
                            <div class="pt-2 text-xs">
                                <div class="flex items-center justify-between text-[10px] text-slate-400">
                                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $note->admin?->name }}</span>
                                    <span>{{ $note->created_at->format('M d, h:i A') }}</span>
                                </div>
                                <p class="text-slate-600 dark:text-slate-300 mt-1">{{ $note->note_content }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-2">No internal notes recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
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
                    OFFICE OF THE PUNONG BARANGAY • LUPONG TAGAPAMAYAPA SECRETARIAT
                </p>
                <p class="text-[9px] text-slate-600 tracking-normal mt-0.5">
                    Barangay Hall Complex, Sayre Highway, North Poblacion, Maramag, Bukidnon 8714
                </p>
            </div>
        </div>

        <!-- Document Title & Case Metadata -->
        <div class="text-center my-3">
            <h2 class="text-base font-black uppercase tracking-wider text-black border-y border-slate-900 py-1 inline-block px-8">
                OFFICIAL BARANGAY COMPLAINT & INCIDENT RECORD DOCKET
            </h2>
            <div class="flex justify-between items-center text-[10px] text-slate-700 mt-2 px-1">
                <span>DOCKET REF NO: <strong class="text-xs text-black">{{ $complaint->reference_number }}</strong></span>
                <span>DATE DOCKETED: <strong class="text-black">{{ $complaint->created_at->format('F d, Y • h:i A') }}</strong></span>
                <span>CURRENT STATUS: <strong class="text-black uppercase">{{ strtoupper(str_replace('_', ' ', $complaint->status)) }}</strong></span>
            </div>
        </div>

        <!-- Case Summary Information Table -->
        <table class="w-full text-[11px] border-collapse border border-slate-900 mb-4">
            <tbody>
                <tr class="border-b border-slate-900">
                    <td class="w-1/4 p-2 bg-slate-100 font-bold border-r border-slate-900">Complainant (Citizen):</td>
                    <td class="w-1/4 p-2 border-r border-slate-900">{{ $complaint->resident?->name }}</td>
                    <td class="w-1/4 p-2 bg-slate-100 font-bold border-r border-slate-900">Verified Contact No.:</td>
                    <td class="w-1/4 p-2">{{ $complaint->resident?->phone }}</td>
                </tr>
                <tr class="border-b border-slate-900">
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Jurisdiction (Purok):</td>
                    <td class="p-2 border-r border-slate-900">{{ $complaint->purok?->name ?? 'Purok 5' }}</td>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Incident Location / Site:</td>
                    <td class="p-2">{{ $complaint->incident_location }}</td>
                </tr>
                <tr class="border-b border-slate-900">
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Date & Time of Incident:</td>
                    <td class="p-2 border-r border-slate-900">
                        {{ $complaint->incident_date ? $complaint->incident_date->format('F d, Y') : 'N/A' }}
                        @if($complaint->incident_time) at {{ $complaint->incident_time }} @endif
                    </td>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Case Classification:</td>
                    <td class="p-2 font-bold">{{ $complaint->category?->name ?? 'General Civic Concern' }}</td>
                </tr>
                <tr>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Priority Level:</td>
                    <td class="p-2 border-r border-slate-900 font-bold uppercase">{{ strtoupper($complaint->final_priority) }}</td>
                    <td class="p-2 bg-slate-100 font-bold border-r border-slate-900">Investigating Official:</td>
                    <td class="p-2">{{ $complaint->assignedOfficial?->name ?? 'Barangay Duty Officer' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Citizen Narrative & Statement -->
        <div class="mb-4 border border-slate-900">
            <div class="bg-slate-100 p-2 font-bold text-xs uppercase border-b border-slate-900 flex justify-between">
                <span>I. CITIZEN'S FORMAL STATEMENT & INCIDENT NARRATIVE</span>
                <span class="text-[10px] font-normal italic">Language: {{ $complaint->original_language ?? 'Cebuano/Bisaya' }}</span>
            </div>
            <div class="p-3 text-xs leading-relaxed whitespace-pre-line text-justify">
                <p class="font-bold mb-1">Subject: {{ $complaint->title }}</p>
                {{ $complaint->original_description }}
            </div>
        </div>

        @if($complaint->english_summary || $complaint->english_translation)
            <!-- AI Translation / Synopsis for Municipal Endorsement -->
            <div class="mb-4 border border-slate-900">
                <div class="bg-slate-100 p-2 font-bold text-xs uppercase border-b border-slate-900">
                    II. OFFICIAL ENGLISH SYNOPSIS & TRIAGE ASSESSMENT
                </div>
                <div class="p-3 text-xs leading-relaxed space-y-2">
                    @if($complaint->english_summary)
                        <p><strong>Executive Summary:</strong> {{ $complaint->english_summary }}</p>
                    @endif
                    @if($complaint->english_translation && $complaint->english_translation !== $complaint->original_description)
                        <p><strong>English Translation of Deposition:</strong> {{ $complaint->english_translation }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Official Barangay Actions Taken -->
        <div class="mb-4 border border-slate-900">
            <div class="bg-slate-100 p-2 font-bold text-xs uppercase border-b border-slate-900">
                III. BARANGAY SECRETARIAT ACTION TAKEN & RESOLUTION NOTES
            </div>
            <div class="p-3 text-xs leading-relaxed">
                <p><strong>Official Actions / Remarks:</strong></p>
                <p class="mt-1">{{ $complaint->resident_visible_remarks ?: 'Incident received and documented into the official Barangay North Poblacion electronic complaint management system. Assigned for field verification and mediation.' }}</p>
            </div>
        </div>

        <!-- Attached Photographic Evidence in Print -->
        @if($complaint->attachments->isNotEmpty())
            <div class="mb-4 border border-slate-900 page-break-inside-avoid">
                <div class="bg-slate-100 p-2 font-bold text-xs uppercase border-b border-slate-900">
                    IV. ATTACHED PHOTOGRAPHIC EVIDENCE & DOCUMENTATION
                </div>
                <div class="p-3 grid grid-cols-2 gap-3">
                    @foreach($complaint->attachments as $att)
                        @if($att->isImage())
                            <div class="border border-slate-400 p-2 text-center">
                                <img src="{{ $att->url() }}" alt="Case Photographic Evidence" class="h-44 w-full object-contain mx-auto mb-1">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-800">Exhibit {{ chr(65 + $loop->index) }} - Case Photographic Evidence</p>
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

        <!-- Signatory Blocks -->
        <div class="mt-8 pt-4 border-t-2 border-slate-900 page-break-inside-avoid">
            <p class="text-[10px] text-center italic mb-6">
                I hereby certify that the statements above are true, accurate, and recorded in the official registry of Barangay North Poblacion, Maramag, Bukidnon.
            </p>

            <div class="grid grid-cols-3 gap-6 text-center text-xs">
                <!-- Complainant Signature -->
                <div class="flex flex-col justify-end">
                    <div class="border-b border-slate-900 w-3/4 mx-auto mb-1"></div>
                    <p class="font-bold text-[11px] uppercase">{{ $complaint->resident?->name }}</p>
                    <p class="text-[9px] text-slate-700">Complainant / Affiant</p>
                </div>

                <!-- Receiving Desk Officer -->
                <div class="flex flex-col justify-end">
                    <div class="border-b border-slate-900 w-3/4 mx-auto mb-1"></div>
                    <p class="font-bold text-[11px] uppercase">JAN AUDITOR</p>
                    <p class="text-[9px] text-slate-700">Barangay Secretary / Receiving Desk</p>
                </div>

                <!-- Punong Barangay -->
                <div class="flex flex-col justify-end">
                    <div class="border-b border-slate-900 w-3/4 mx-auto mb-1"></div>
                    <p class="font-bold text-[11px] uppercase">HON. ANTONIO DELA CRUZ</p>
                    <p class="text-[9px] text-slate-700">Punong Barangay / Lupon Chairman</p>
                </div>
            </div>

            <!-- Official Seal Stamp Box -->
            <div class="flex justify-between items-center text-[9px] text-slate-500 mt-6 pt-2 border-t border-slate-200">
                <span>System Tracking: NPC-ECOMPLAINT-{{ $complaint->id }} • Printed by Barangay Administrator</span>
                <span>Official Dry Seal must be stamped over the Punong Barangay signature to be legally valid</span>
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
</x-layouts.admin>
