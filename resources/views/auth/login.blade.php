<x-layouts.guest title="Sign In">
    <div class="relative max-w-5xl w-full mx-auto my-4 animate-fade-in-up">
        
        <!-- Ambient Decorative Floating Blobs behind card -->
        <div class="absolute -top-12 -left-12 w-64 h-64 rounded-full bg-blue-500/15 dark:bg-blue-600/15 blur-3xl blob-float pointer-events-none -z-10"></div>
        <div class="absolute -bottom-12 -right-12 w-72 h-72 rounded-full bg-cyan-400/15 dark:bg-indigo-600/15 blur-3xl blob-float-delayed pointer-events-none -z-10"></div>

        <div class="bg-white dark:bg-[#0B132B] rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
            
            <!-- Left Column: Sign In Form (lg:col-span-7) -->
            <div class="lg:col-span-7 p-8 md:p-12 flex flex-col justify-between relative z-10">
                <div>
                    <!-- Brand Logo Header -->
                    <x-brand-logo size="md" />

                    <div class="mt-8 mb-6">
                        <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white tracking-tight">
                            Welcome back!
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Sign in to your account to continue.
                        </p>
                    </div>


                    <!-- Flash Messages -->
                    @if(session('error'))
                        <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2.5 shadow-2xs">
                            <span class="w-4 h-4 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">!</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-4 p-3.5 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-semibold flex items-center gap-2.5 shadow-2xs">
                            <span class="w-4 h-4 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">i</span>
                            <span>{{ session('info') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div id="flash-success-toast" class="flash-auto-dismiss mb-4 p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2.5 shadow-2xs transition-all duration-500">
                            <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">✓</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Client-side Dynamic Error Box -->
                    <div id="js-error-box" class="hidden mb-4 p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2.5 shadow-2xs">
                        <span class="w-4 h-4 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">!</span>
                        <span id="js-error-text"></span>
                    </div>

                    <!-- Sign In Form -->
                    <form id="login-form" method="POST" action="/login" onsubmit="handleLoginSubmit(event)" class="space-y-4">
                        @csrf

                        <!-- Email Input (User Silhouette Icon) -->
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input id="email" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus 
                                       placeholder="Enter your email address"
                                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                            </div>
                            @error('email')
                                <p class="text-xs text-rose-500 font-semibold mt-1.5 pl-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Input (Padlock Icon + Eye Toggle) -->
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
                                       placeholder="Enter your password"
                                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] border border-slate-200 dark:border-slate-700/80 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-2xs">
                            </div>
                            @error('password')
                                <p class="text-xs text-rose-500 font-semibold mt-1.5 pl-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center text-slate-600 dark:text-slate-400 cursor-pointer font-medium">
                                <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 mr-2">
                                Remember me
                            </label>
                            <a href="{{ route('password.request') }}" class="font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Sign In Button with Interactive State -->
                        <button type="submit" 
                                id="login-submit-btn"
                                class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm shadow-xl shadow-blue-600/25 hover:shadow-blue-600/35 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 group mt-2">
                            <span id="btn-label">Sign in to Portal</span>
                            <svg id="btn-arrow" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            <svg id="btn-spinner" class="hidden animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>


                </div>

                <!-- Footer: Register link -->
                <div class="mt-8 text-center text-xs text-slate-500 dark:text-slate-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-extrabold text-blue-600 hover:text-blue-700 dark:text-blue-400 ml-1 hover:underline">
                        Register as resident
                    </a>
                </div>
            </div>

            <!-- Right Column: Visual Showcase Card with Blob Design & Barangay Hall Photo (lg:col-span-5) -->
            <div class="lg:col-span-5 relative overflow-hidden bg-gradient-to-br from-[#12345B] via-[#0B2340] to-[#06152B] text-white p-8 md:p-10 flex flex-col justify-between">
                
                <!-- Floating Decorative Ambient Blobs inside the right card -->
                <div class="absolute -top-10 -right-10 w-48 h-48 rounded-full bg-blue-500/25 blur-2xl blob-float pointer-events-none"></div>
                <div class="absolute bottom-1/3 -left-10 w-40 h-40 rounded-full bg-cyan-400/20 blur-2xl blob-float-delayed pointer-events-none"></div>

                <!-- Top Official Portal Tag -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-[10px] font-extrabold uppercase tracking-wider text-blue-100 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>North Poblacion Official</span>
                    </div>

                    <div class="text-[10px] font-bold text-blue-200/70 tracking-widest uppercase">
                        Maramag, Bukidnon
                    </div>
                </div>

                <!-- Center: Organic Blob Masked Photo Showcase of Barangay North Poblacion Hall -->
                <div class="relative z-10 my-6">
                    <div class="relative w-full h-48 rounded-3xl overflow-hidden border-2 border-white/20 shadow-2xl group">
                        <!-- High Resolution Real Photo -->
                        <img src="{{ asset('images/barangay-hall.jpg') }}" 
                             alt="Barangay Hall North Poblacion" 
                             class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">

                        <!-- Organic Wave / Curve Overlay Mask -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B2340]/90 via-[#0B2340]/20 to-transparent"></div>
                        
                        <!-- Floating Badge on Picture -->
                        <div class="absolute bottom-3 left-3 px-3 py-1 rounded-xl bg-black/60 backdrop-blur-md text-[10px] font-bold text-white border border-white/20 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Barangay Hall Facility</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Content: Headline, Subtext, & 3 Feature Bullets -->
                <div class="relative z-10">
                    <h3 class="text-xl md:text-2xl font-black leading-snug tracking-tight text-white drop-shadow-md">
                        Smarter Barangay Services for a Stronger Community
                    </h3>
                    <p class="text-xs text-blue-100/90 mt-2 leading-relaxed">
                        AI-powered, multilingual, and transparent complaint management for a better North Poblacion, Maramag.
                    </p>

                    <!-- 3 Bullets with Circular Blue Translucent Badges -->
                    <div class="mt-5 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-500/30 backdrop-blur-md border border-white/20 flex items-center justify-center text-blue-200 shrink-0 shadow-xs">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-extrabold text-white">Report Easily</div>
                                <div class="text-[11px] text-blue-200/80">In your own language (Cebuano, Bisaya, English)</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-500/30 backdrop-blur-md border border-white/20 flex items-center justify-center text-blue-200 shrink-0 shadow-xs">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-extrabold text-white">Track Progress</div>
                                <div class="text-[11px] text-blue-200/80">Real-time updates & instant notifications</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-500/30 backdrop-blur-md border border-white/20 flex items-center justify-center text-blue-200 shrink-0 shadow-xs">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-extrabold text-white">Build a Better Community</div>
                                <div class="text-[11px] text-blue-200/80">Together for a cleaner, safer Barangay</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function handleLoginSubmit(event) {
            if (event) event.preventDefault();
            const btn = document.getElementById('login-submit-btn');
            const label = document.getElementById('btn-label');
            const arrow = document.getElementById('btn-arrow');
            const spinner = document.getElementById('btn-spinner');
            const errorBox = document.getElementById('js-error-box');
            const errorText = document.getElementById('js-error-text');

            if (errorBox) errorBox.classList.add('hidden');
            if (label) label.textContent = 'Verifying Credentials...';
            if (arrow) arrow.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
            if (btn) btn.disabled = true;

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const tokenEl = document.querySelector('input[name="_token"]');
            const token = tokenEl ? tokenEl.value : '';

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        _token: token,
                        email: email,
                        password: password,
                        remember: true
                    })
                });

                const data = await response.json();

                if (response.ok && data.success && data.redirect) {
                    if (label) label.textContent = 'Access Granted! Entering portal...';
                    window.location.replace(data.redirect);
                    return;
                }

                // Show error message cleanly on screen
                const message = data.message || (data.errors && data.errors.email ? data.errors.email[0] : 'Invalid credentials. The username or password you entered is incorrect. Please check your credentials and try again.');
                if (errorBox && errorText) {
                    errorText.textContent = message;
                    errorBox.classList.remove('hidden');
                } else {
                    alert(message);
                }
            } catch (err) {
                // If fetch fails (e.g. offline), fallback to standard form submit
                document.getElementById('login-form').submit();
                return;
            } finally {
                if (label) label.textContent = 'Sign in to Portal';
                if (arrow) arrow.classList.remove('hidden');
                if (spinner) spinner.classList.add('hidden');
                if (btn) btn.disabled = false;
            }
        }

        // 5-Second Silent Auto-Dismiss for Flash Success / Logout Toast
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('flash-success-toast');
            if (toast) {
                setTimeout(function() {
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(function() {
                        if (toast && toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 500);
                }, 5000);
            }
        });
    </script>
</x-layouts.guest>
