<x-layouts.resident>
    <x-slot:title>Edit Complaint {{ $complaint->reference_number }}</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
        <!-- Back Link & Header -->
        <div>
            <a href="{{ route('resident.complaints.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back to My Complaints</span>
            </a>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white">Edit Complaint</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Editing incident report <span class="font-mono font-bold text-blue-600 dark:text-blue-400">{{ $complaint->reference_number }}</span>
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                    Status: {{ $complaint->statusBadge()['label'] }}
                </span>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift">
            <form method="POST" action="{{ route('resident.complaints.update', $complaint->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <label for="title" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Complaint Title / Subject</span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <input id="title" 
                           type="text" 
                           name="title" 
                           value="{{ old('title', $complaint->title) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all font-medium">
                    @error('title')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Purok Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- AI Automated Category Badge (Complainant will NOT choose, only AI will choose) -->
                    <div class="p-3.5 rounded-2xl bg-gradient-to-r from-blue-50/90 via-indigo-50/90 to-purple-50/90 dark:from-blue-950/40 dark:via-indigo-950/40 dark:to-purple-950/40 border border-blue-200/80 dark:border-blue-800/60 shadow-xs flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-4 h-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-blue-900 dark:text-blue-200 uppercase tracking-wide">
                                    AI Category
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-blue-600 text-white">
                                    {{ $complaint->category?->name ?? 'AI Assigned' }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-slate-300 mt-1 leading-snug">
                                Automatically analyzed and categorized by AI. When you edit your description, the AI will re-evaluate and assign the category.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="purok_id" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Purok (Barangay North Poblacion)</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <select id="purok_id" 
                                name="purok_id" 
                                required 
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium text-slate-800 dark:text-slate-100">
                            @foreach($puroks as $purok)
                                <option value="{{ $purok->id }}" {{ old('purok_id', $complaint->purok_id) == $purok->id ? 'selected' : '' }}>
                                    {{ $purok->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('purok_id')
                            <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date & Location -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="incident_date" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Incident Date</span>
                        </label>
                        <input id="incident_date" 
                               type="date" 
                               name="incident_date" 
                               value="{{ old('incident_date', $complaint->incident_date ? $complaint->incident_date->format('Y-m-d') : '') }}" 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                        @error('incident_date')
                            <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="incident_location" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Specific Street / Landmark</span>
                        </label>
                        <input id="incident_location" 
                               type="text" 
                               name="incident_location" 
                               value="{{ old('incident_location', $complaint->incident_location) }}" 
                               placeholder="e.g. Near Purok 3 Basketball Court, Sayre Highway"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                        @error('incident_location')
                            <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description / Details -->
                <div>
                    <label for="original_description" class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span>Complaint Details & Statement</span>
                            <span class="text-rose-500">*</span>
                        </div>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal lowercase tracking-normal">
                            (Cebuano, Tagalog, or English)
                        </span>
                    </label>
                    <textarea id="original_description" 
                              name="original_description" 
                              rows="6" 
                              required 
                              class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 leading-relaxed font-medium">{{ old('original_description', $complaint->original_description) }}</textarea>
                    @error('original_description')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('resident.complaints.index') }}" 
                       class="w-full sm:w-auto px-6 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold text-center transition-all">
                        Cancel
                    </a>

                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 text-white text-xs font-extrabold shadow-lg shadow-purple-600/20 transition-all hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Update Complaint</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.resident>
