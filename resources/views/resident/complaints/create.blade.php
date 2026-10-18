<x-layouts.resident>
    <x-slot:title>Submit New Complaint</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
        <!-- Back Link & Header -->
        <div>
            <a href="{{ route('resident.complaints.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back to Complaints</span>
            </a>
            <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white">Submit Community Complaint</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Report community incidents, public infrastructure issues, or sanitation concerns to Barangay North Poblacion.
            </p>
        </div>

        <!-- AI Language Helper Notice -->
        <div class="p-5 rounded-3xl bg-gradient-to-r from-blue-50/90 via-indigo-50/90 to-emerald-50/90 dark:from-blue-950/40 dark:via-indigo-950/40 dark:to-emerald-950/40 border border-blue-200/80 dark:border-blue-900/60 flex items-start gap-3.5 shadow-sm hover-lift">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-xs shrink-0 shadow-md shadow-blue-500/20 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
            </div>
            <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                <span class="font-extrabold text-[#12345B] dark:text-white block mb-0.5 text-sm">Automated Multilingual Translation & Urgency Triage:</span>
                You may write your complaint title and details in <strong>Cebuano/Bisaya, Tagalog, or English</strong>. Our AI automatically analyzes the text, generates a professional English translation, and determines whether it is <strong>Urgent, High, Medium, or Low</strong> priority to dispatch the appropriate barangay team.
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm hover-lift">
            <form method="POST" action="{{ route('resident.complaints.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

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
                           value="{{ old('title') }}" 
                           required 
                           placeholder="e.g. Fallen electrical wire on street / Daghan kaayo og basura / Sirado ang dalan"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('title')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Purok Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Category</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <select id="category_id" 
                                name="category_id" 
                                required 
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <label for="purok_id" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Purok Location</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <select id="purok_id" 
                                name="purok_id" 
                                required 
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
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

                <!-- Interactive "Others" Category Notice Box -->
                <div id="others-category-notice" class="hidden p-5 rounded-3xl bg-gradient-to-r from-amber-50/90 to-orange-50/90 dark:from-amber-950/40 dark:to-orange-950/40 border border-amber-200 dark:border-amber-900/60 shadow-xs animate-fade-in-up">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-amber-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h5 class="text-xs font-black text-amber-900 dark:text-amber-200 uppercase tracking-wide">
                                Custom Complaint Topic (Others)
                            </h5>
                            <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                                You selected <strong>"Others"</strong>. Please write what you want to complain about in the <strong>Complaint Title / Subject</strong> and clearly describe the incident details below so our Barangay team can attend to your concern.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Specific Location Landmark -->
                <div>
                    <label for="incident_location" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Specific Landmark / Street Details</span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <input id="incident_location" 
                           type="text" 
                           name="incident_location" 
                           value="{{ old('incident_location') }}" 
                           required 
                           placeholder="e.g. Near Purok 5 Chapel, atubangan sa sari-sari store"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('incident_location')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Incident Date & Time Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="incident_date" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Incident Date</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input id="incident_date" 
                               type="date" 
                               name="incident_date" 
                               value="{{ old('incident_date', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}"
                               required 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('incident_date')
                            <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="incident_time" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Approximate Time (Optional)</span>
                        </label>
                        <input id="incident_time" 
                               type="time" 
                               name="incident_time" 
                               value="{{ old('incident_time') }}" 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Detailed Description -->
                <div>
                    <label for="original_description" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <span>Detailed Incident Description</span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="original_description" 
                              name="original_description" 
                              rows="5" 
                              required 
                              placeholder="Ihulagway pag-ayo ang hitabo, kinsa ang apektado, unsa kadugay na kini nga problema, o unsa nga tabang ang gikinahanglan..."
                              class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 leading-relaxed">{{ old('original_description') }}</textarea>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                        Pwedeng Bisaya, Tagalog, o Iningles. Palihug ibutang ang tinuod ug klaro nga impormasyon.
                    </p>
                    @error('original_description')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo & Document Attachments Dropzone -->
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Photo Evidence / Supporting Documents (Optional)</span>
                    </label>
                    
                    <div id="dropzone-container" class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-400 rounded-3xl p-6 text-center transition-all bg-slate-50/50 dark:bg-slate-900/40">
                        <input id="attachments" 
                               type="file" 
                               name="attachments[]" 
                               multiple 
                               accept="image/*,.pdf,.doc,.docx"
                               class="hidden"
                               onchange="previewComplaintFiles(this)">
                        
                        <label for="attachments" class="cursor-pointer flex flex-col items-center">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-2 shadow-xs group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                Click or drag & drop to upload incident photos or documents
                            </span>
                            <span class="text-[11px] text-slate-400 mt-1">
                                Supports JPG, PNG, WEBP, GIF, SVG, BMP, PDF up to 10MB each • Instant visual preview
                            </span>
                        </label>
                        
                        <!-- Visual Image & Document Preview Grid -->
                        <div id="file-list-preview" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 hidden"></div>
                    </div>

                    @error('attachments.*')
                        <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('resident.complaints.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#12345B] to-blue-700 hover:from-[#0B2340] hover:to-blue-800 text-white font-extrabold text-sm shadow-lg shadow-blue-950/30 hover:scale-[1.02] transition-all flex items-center gap-2">
                        <span>Submit Complaint & Generate Tracking Number</span>
                        <svg class="w-4 h-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const othersNotice = document.getElementById('others-category-notice');

            function checkCategory() {
                if (!categorySelect || !othersNotice) return;
                const selectedText = categorySelect.options[categorySelect.selectedIndex]?.text?.trim()?.toLowerCase() || '';
                if (selectedText.includes('other')) {
                    othersNotice.classList.remove('hidden');
                } else {
                    othersNotice.classList.add('hidden');
                }
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', checkCategory);
                checkCategory();
            }
        });

        function previewComplaintFiles(input) {
            const preview = document.getElementById('file-list-preview');
            preview.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                preview.classList.remove('hidden');

                Array.from(input.files).forEach((file, index) => {
                    const card = document.createElement('div');
                    card.className = 'relative rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-2 shadow-xs flex flex-col items-center text-center';

                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.className = 'w-full h-24 object-cover rounded-xl mb-1.5';
                        img.alt = file.name;
                        
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                        card.appendChild(img);
                    } else {
                        const iconBox = document.createElement('div');
                        iconBox.className = 'w-full h-24 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex flex-col items-center justify-center font-bold text-xs mb-1.5';
                        iconBox.innerHTML = `
                            <svg class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>${file.name.split('.').pop().toUpperCase()}</span>
                        `;
                        card.appendChild(iconBox);
                    }

                    const name = document.createElement('p');
                    name.className = 'text-[10px] font-bold text-slate-700 dark:text-slate-200 truncate w-full';
                    name.textContent = file.name;
                    card.appendChild(name);

                    const size = document.createElement('span');
                    size.className = 'text-[9px] text-slate-400';
                    size.textContent = `${(file.size / 1024).toFixed(0)} KB`;
                    card.appendChild(size);

                    preview.appendChild(card);
                });
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-layouts.resident>
