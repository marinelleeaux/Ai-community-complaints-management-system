<x-layouts.resident>
    <x-slot:title>Submit New Complaint</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Back Link & Header -->
        <div>
            <a href="{{ route('resident.complaints.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back to Complaints</span>
            </a>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Submit Community Complaint</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Report community incidents, public infrastructure issues, or sanitation concerns to Barangay North Poblacion.
            </p>
        </div>

        <!-- AI Language Helper Notice -->
        <div class="p-4.5 rounded-2xl bg-gradient-to-r from-blue-50/90 via-indigo-50/90 to-emerald-50/90 dark:from-blue-950/40 dark:via-indigo-950/40 dark:to-emerald-950/40 border border-blue-200/80 dark:border-blue-900/60 flex items-start gap-3.5 shadow-xs">
            <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-blue-600 to-emerald-600 text-white flex items-center justify-center font-black text-xs shrink-0 shadow-xs mt-0.5">
                AI
            </div>
            <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                <span class="font-extrabold text-[#12345B] dark:text-white block mb-0.5">Automated Multilingual Translation & Urgency Triage:</span>
                You may write your complaint title and details in <strong>Cebuano/Bisaya, Tagalog, or English</strong>. Our AI automatically analyzes the text, generates a professional English translation, and determines whether it is <strong>Urgent, High, Medium, or Low</strong> priority to dispatch the appropriate barangay team.
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <form method="POST" action="{{ route('resident.complaints.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Complaint Title / Subject <span class="text-rose-500">*</span>
                    </label>
                    <input id="title" 
                           type="text" 
                           name="title" 
                           value="{{ old('title') }}" 
                           required 
                           placeholder="e.g. Fallen electrical wire on street / Daghan kaayo og basura / Sirado ang dalan"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                    @error('title')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Purok Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Category <span class="text-rose-500">*</span>
                        </label>
                        <select id="category_id" 
                                name="category_id" 
                                required 
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled selected>Select appropriate category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="purok_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Purok Incident Location <span class="text-rose-500">*</span>
                        </label>
                        <select id="purok_id" 
                                name="purok_id" 
                                required 
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled selected>Select Purok</option>
                            @foreach($puroks as $purok)
                                <option value="{{ $purok->id }}" {{ (old('purok_id') == $purok->id || (!old('purok_id') && $user->purok_id == $purok->id)) ? 'selected' : '' }}>
                                    {{ $purok->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('purok_id')
                            <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Specific Location Landmark -->
                <div>
                    <label for="incident_location" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Specific Location / Landmark <span class="text-rose-500">*</span>
                    </label>
                    <input id="incident_location" 
                           type="text" 
                           name="incident_location" 
                           value="{{ old('incident_location') }}" 
                           required 
                           placeholder="e.g. Near Purok 5 Chapel, atubangan sa sari-sari store"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('incident_location')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Incident Date & Time Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="incident_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Incident Date <span class="text-rose-500">*</span>
                        </label>
                        <input id="incident_date" 
                               type="date" 
                               name="incident_date" 
                               value="{{ old('incident_date', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}"
                               required 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('incident_date')
                            <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="incident_time" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Approximate Time (Optional)
                        </label>
                        <input id="incident_time" 
                               type="time" 
                               name="incident_time" 
                               value="{{ old('incident_time') }}" 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Description (Cebuano/Bisaya/Tagalog/English) -->
                <div>
                    <label for="original_description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Detailed Incident Description <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="original_description" 
                              name="original_description" 
                              rows="5" 
                              required 
                              placeholder="Ihulagway pag-ayo ang hitabo, kinsa ang apektado, unsa kadugay na kini nga problema, o unsa nga tabang ang gikinahanglan..."
                              class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 leading-relaxed">{{ old('original_description') }}</textarea>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                        Pwedeng Bisaya, Tagalog, o Iningles. Palihug ibutang ang tinuod ug klaro nga impormasyon.
                    </p>
                    @error('original_description')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo & Document Attachments -->
                <div>
                    <label for="attachments" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Photo Evidence / Supporting Documents (Optional)
                    </label>
                    <input id="attachments" 
                           type="file" 
                           name="attachments[]" 
                           multiple 
                           accept="image/*,.pdf,.doc,.docx"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-5 file:rounded-2xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 dark:file:bg-blue-900/40 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/60 cursor-pointer">
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                        Attach clear photos of the incident, damaged road, garbage pile, or document proof (Max 5MB each).
                    </p>
                    @error('attachments.*')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('resident.complaints.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white font-bold text-sm shadow-lg shadow-blue-950/20 hover:shadow-xl transition-all">
                        Submit Complaint & Generate Tracking Number
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.resident>
