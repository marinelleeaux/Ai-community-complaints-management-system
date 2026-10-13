<x-layouts.resident>
    <x-slot:title>My Profile</x-slot:title>

    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Resident Profile</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage your personal community records, profile picture, and verification details</p>
        </div>

        <!-- Profile Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm space-y-6">
            
            <form method="POST" action="{{ route('resident.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Profile Photo Avatar with Live Preview -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-5 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="relative group w-20 h-20 shrink-0">
                        @if($user->avatarUrl())
                            <img id="avatar-preview-img" src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-3xl object-cover ring-4 ring-blue-500/20 shadow-md">
                        @else
                            <div id="avatar-preview-fallback" class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-[#12345B] via-blue-700 to-emerald-600 text-white font-black text-2xl flex items-center justify-center shadow-md ring-4 ring-blue-500/20">
                                {{ $user->initials() }}
                            </div>
                            <img id="avatar-preview-img" src="" alt="Preview" class="hidden w-20 h-20 rounded-3xl object-cover ring-4 ring-blue-500/20 shadow-md">
                        @endif

                        <label for="avatar-input" class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center cursor-pointer shadow-md transition-transform transform hover:scale-110" title="Upload New Photo">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </label>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-extrabold text-[#12345B] dark:text-white">{{ $user->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                ✓ Verified Resident
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $user->email }} • {{ $user->purok?->name ?? 'Purok Resident' }}</p>
                        
                        <div class="mt-2.5 flex items-center gap-2">
                            <label for="avatar-input" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 cursor-pointer transition-colors">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span>Change Profile Photo</span>
                            </label>
                            <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                            <span class="text-[11px] text-slate-400">JPG, PNG up to 2MB</span>
                        </div>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Full Official Name
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                        @error('name')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Mobile Contact Number
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}" 
                                   required 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                            @error('phone')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Purok Residence
                            </label>
                            <select name="purok_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                                @foreach($puroks as $purok)
                                    <option value="{{ $purok->id }}" {{ (old('purok_id', $user->purok_id) == $purok->id) ? 'selected' : '' }}>
                                        {{ $purok->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('purok_id')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Complete Residence Address
                        </label>
                        <input type="text" 
                               name="address" 
                               value="{{ old('address', $user->address) }}" 
                               required 
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                        @error('address')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white font-bold text-xs shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                        Save Profile & Photo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('avatar-preview-img');
                    const fallback = document.getElementById('avatar-preview-fallback');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (fallback) fallback.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-layouts.resident>
