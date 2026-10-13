<x-layouts.admin>
    <x-slot:header>Complaint Review: {{ $complaint->reference_number }}</x-slot:header>

    @php 
        $badge = $complaint->statusBadge(); 
        $pBadge = $complaint->priorityBadge();
    @endphp

    <div class="space-y-6">
        <!-- Top Back Link & Status Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.complaints.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Back to Complaints Queue</span>
                </a>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black text-[#12345B] dark:text-white">{{ $complaint->reference_number }}</h2>
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                        {{ $badge['label'] }}
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold border {{ $pBadge['bg'] }} {{ $pBadge['text'] }} {{ $pBadge['border'] }}">
                        Priority: {{ $pBadge['label'] }}
                    </span>
                </div>
            </div>

            <div class="text-xs text-slate-400">
                Submitted by <strong>{{ $complaint->resident?->name }}</strong> on {{ $complaint->created_at->format('M d, Y • h:i A') }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Complaint Details, AI Analysis, Status Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Original Complaint Overview -->
                <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-5">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                            <span class="font-bold text-slate-600 dark:text-slate-300">{{ $complaint->category?->name ?? 'General' }}</span>
                            <span>•</span>
                            <span>{{ $complaint->purok?->name ?? 'Purok 5' }}</span>
                            <span>•</span>
                            <span>{{ $complaint->incident_location }}</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#12345B] dark:text-white">
                            {{ $complaint->title }}
                        </h3>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Original Citizen Description</h4>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line">
                            {{ $complaint->original_description }}
                        </div>
                    </div>

                    @if($complaint->attachments->isNotEmpty())
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Attached Photo / Evidence</h4>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($complaint->attachments as $att)
                                    <a href="{{ $att->url() }}" target="_blank" class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center gap-3 hover:border-blue-500">
                                        <span class="text-blue-600 font-bold text-xs">📎</span>
                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate">{{ $att->original_filename }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- AI Analysis Card (Section 10) -->
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
                                    <select name="category_id" class="w-full p-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs bg-slate-50 dark:bg-slate-900">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $complaint->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Override Priority</label>
                                    <select name="final_priority" class="w-full p-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs bg-slate-50 dark:bg-slate-900">
                                        <option value="low" {{ $complaint->final_priority === 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $complaint->final_priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $complaint->final_priority === 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ $complaint->final_priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold">
                                Save Override
                            </button>
                        </form>
                    </div>
                </div>

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
                            <textarea name="public_remarks" rows="3" placeholder="Provide actionable feedback or instructions to the resident..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs">{{ $complaint->resident_visible_remarks }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">
                                Confidential Internal Admin Note (Optional - Hidden from Resident)
                            </label>
                            <textarea name="internal_notes" rows="2" placeholder="Record internal official communication, contractor names, or sensitive notes..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs"></textarea>
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
                        <div class="w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 font-bold text-sm flex items-center justify-center">
                            {{ $complaint->resident?->initials() }}
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
                        <select name="assigned_to" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs">
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
                        <textarea name="note_content" rows="2" placeholder="Add confidential internal memo..." required class="w-full p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs"></textarea>
                        <button type="submit" class="w-full py-2 bg-[#12345B] hover:bg-[#0B2340] text-white rounded-xl text-xs font-bold">
                            Save Note
                        </button>
                    </form>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800 pt-2 space-y-2">
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
</x-layouts.admin>
