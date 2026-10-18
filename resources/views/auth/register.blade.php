<x-layouts.guest title="Create an Account">
    <div class="relative max-w-xl w-full mx-auto my-4 animate-fade-in-up">
        
        <!-- Ambient Decorative Floating Blobs -->
        <div class="absolute -top-12 -left-12 w-64 h-64 rounded-full bg-blue-500/15 dark:bg-blue-600/15 blur-3xl blob-float pointer-events-none -z-10"></div>
        <div class="absolute -bottom-12 -right-12 w-72 h-72 rounded-full bg-emerald-400/15 dark:bg-indigo-600/15 blur-3xl blob-float-delayed pointer-events-none -z-10"></div>

        <div class="bg-white dark:bg-[#0B132B] rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl p-8 md:p-12 relative overflow-hidden">
            
            <!-- Subtle Top Corner Blob Accent -->
            <div class="absolute -top-20 -right-20 w-44 h-44 rounded-full bg-blue-500/10 dark:bg-blue-400/10 blur-2xl pointer-events-none"></div>

            <!-- Brand Logo Header -->
            <x-brand-logo size="md" />

            <div class="mt-8 mb-6">
                <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white tracking-tight">
                    Create an Account
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Register as a resident to file and track your community complaints.
                </p>
            </div>

            <form method="POST" action="/register" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Full Name (User Icon) -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               placeholder="Full Name"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                    </div>
                    @error('name')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5 pl-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address (Envelope Icon) -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="Email Address"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5 pl-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number (Phone Icon) -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <input id="phone" 
                               type="text" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required 
                               placeholder="Contact Number (e.g. 0917-123-4567)"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                    </div>
                    @error('phone')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5 pl-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Purok Selection & Street Address Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <select id="purok_id" 
                                name="purok_id" 
                                required 
                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                            <option value="" disabled selected>Select Purok</option>
                            @foreach($puroks as $purok)
                                <option value="{{ $purok->id }}" {{ old('purok_id') == $purok->id ? 'selected' : '' }}>
                                    {{ $purok->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('purok_id')
                            <p class="text-xs text-rose-500 font-semibold mt-1.5 pl-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input id="address" 
                               type="text" 
                               name="address" 
                               value="{{ old('address', 'North Poblacion, Maramag, Bukidnon') }}" 
                               required 
                               placeholder="Street / House Address"
                               class="w-full px-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                        @error('address')
                            <p class="text-xs text-rose-500 font-semibold mt-1.5 pl-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Create Password (Lock Icon + Eye Toggle) -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               placeholder="Create Password (min 8 characters)"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5 pl-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               placeholder="Confirm Password"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                    </div>
                </div>

                <!-- Terms & Privacy Agreement Checkbox -->
                <div class="pt-2">
                    <label class="flex items-start gap-2.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" 
                               name="privacy_agreement" 
                               value="1" 
                               checked 
                               required 
                               class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 mt-0.5 shrink-0">
                        <span>
                            I agree to the <a href="#" class="font-bold text-blue-600 dark:text-blue-400 underline">Terms of Service</a> and <a href="#" class="font-bold text-blue-600 dark:text-blue-400 underline">Privacy Policy</a>.
                        </span>
                    </label>
                    @error('privacy_agreement')
                        <p class="text-xs text-rose-500 font-semibold mt-1 pl-6">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Create Account Button -->
                <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm shadow-xl shadow-blue-600/30 hover:scale-[1.01] transition-all flex items-center justify-center gap-2 group mt-4">
                    <span>Create Account</span>
                </button>
            </form>

            <!-- Footer: Sign In Link -->
            <div class="mt-8 text-center text-xs text-slate-500 dark:text-slate-400">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-extrabold text-blue-600 hover:text-blue-700 dark:text-blue-400 ml-1 hover:underline">
                    Sign in
                </a>
            </div>
        </div>
    </div>

</x-layouts.guest>
