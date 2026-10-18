<x-layouts.guest title="AI-Community Complaint Management System - Barangay North Poblacion">
    <div class="space-y-12 pb-12 animate-fade-in-up relative overflow-hidden">
        
        <!-- Ambient Background Floating Blobs -->
        <div class="absolute top-10 left-10 w-96 h-96 rounded-full bg-blue-400/15 dark:bg-blue-600/10 blur-3xl blob-float pointer-events-none -z-10"></div>
        <div class="absolute top-1/3 right-10 w-[420px] h-[420px] rounded-full bg-cyan-400/15 dark:bg-indigo-600/10 blur-3xl blob-float-delayed pointer-events-none -z-10"></div>
        <div class="absolute bottom-10 left-1/4 w-80 h-80 rounded-full bg-emerald-400/10 dark:bg-blue-500/10 blur-3xl blob-float pointer-events-none -z-10"></div>

        <!-- Hero Section with Organic Blob Wave Curves and Official Barangay Hall Photo -->
        <section class="relative rounded-3xl overflow-hidden bg-white dark:bg-[#0B132B] border border-slate-200/80 dark:border-slate-800 shadow-2xl">
            <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch min-h-[500px]">
                
                <!-- Hero Left Content (lg:col-span-6) -->
                <div class="lg:col-span-6 p-8 md:p-14 flex flex-col justify-center z-20 relative">
                    <!-- Feature Pill Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-200/60 dark:border-blue-900/60 text-[11px] font-black text-blue-700 dark:text-blue-300 uppercase tracking-widest self-start mb-6 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                        <span>AI-POWERED • SMARTER • FASTER SERVICE</span>
                    </div>

                    <!-- Hero Main Headline -->
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-[#12345B] dark:text-white tracking-tight leading-[1.12]">
                        AI-Community<br>
                        Complaint Management<br>
                        System
                    </h1>

                    <!-- Hero Subtitle -->
                    <p class="text-sm md:text-base text-slate-600 dark:text-slate-300 mt-5 max-w-xl leading-relaxed">
                        Report community concerns in your own language. Our AI translates, analyzes, and routes your complaint to the right barangay office — and you can track every step.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center gap-4 mt-8">
                        <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm shadow-xl shadow-blue-600/30 hover:scale-105 transition-all flex items-center gap-2 group">
                            <span>Sign in</span>
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </a>

                        <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-2xl bg-white dark:bg-[#111C33] hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 font-extrabold text-sm shadow-sm hover:scale-105 transition-all flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Register as Resident</span>
                        </a>
                    </div>
                </div>

                <!-- Hero Right: High-Res Barangay Hall Photo with Layered Organic Blob Wave Masks (lg:col-span-6) -->
                <div class="lg:col-span-6 relative min-h-[360px] lg:min-h-full overflow-hidden bg-slate-100 dark:bg-[#071426] flex items-center justify-center">
                    
                    <!-- Real High-Res Photo of Barangay North Poblacion Hall (Centered) -->
                    <img src="{{ asset('images/barangay-hall.jpg') }}" 
                         alt="Barangay Hall North Poblacion Maramag" 
                         class="w-full h-full object-cover object-center scale-100 hover:scale-105 transition-transform duration-700">

                    <!-- Layer 1: Outermost Translucent Cyan Ambient Wave (Desktop) -->
                    <svg class="hidden lg:block absolute inset-y-0 -left-1 h-full w-48 xl:w-56 z-10 text-cyan-300/35 dark:text-cyan-400/25 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                        <path d="M0 0 L85 0 C 105 15, 105 35, 90 60 C 70 80, 55 90, 45 100 L 0 100 Z" />
                    </svg>

                    <!-- Layer 2: Middle Translucent Sky Blue Wave (Desktop) -->
                    <svg class="hidden lg:block absolute inset-y-0 -left-1 h-full w-40 xl:w-48 z-15 text-blue-400/40 dark:text-blue-500/35 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                        <path d="M0 0 L70 0 C 90 15, 90 35, 75 60 C 55 80, 40 90, 30 100 L 0 100 Z" />
                    </svg>

                    <!-- Layer 3: Inner Translucent Blue Accent Wave (Desktop) -->
                    <svg class="hidden lg:block absolute inset-y-0 -left-1 h-full w-32 xl:w-40 z-20 text-blue-500/35 dark:text-blue-600/30 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                        <path d="M0 0 L55 0 C 75 15, 75 35, 60 60 C 40 80, 25 90, 15 100 L 0 100 Z" />
                    </svg>

                    <!-- Layer 4: Solid White / Dark Card Mask that blends into the card (Desktop) -->
                    <svg class="hidden lg:block absolute inset-y-0 -left-1 h-full w-24 xl:w-32 z-25 text-white dark:text-[#0B132B] pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                        <path d="M0 0 L40 0 C 60 15, 60 35, 45 60 C 25 80, 10 90, 0 100 L 0 100 Z" />
                    </svg>

                    <!-- Mobile Gradient Wave (Top to bottom) -->
                    <div class="lg:hidden absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-white dark:from-[#0B132B] via-white/80 dark:via-[#0B132B]/80 to-transparent pointer-events-none z-10"></div>

                    <!-- Floating Decorative Organic Blob Pill -->
                    <div class="absolute bottom-6 right-6 z-30 px-4 py-2 rounded-2xl bg-slate-900/80 backdrop-blur-md border border-white/20 text-white text-xs font-extrabold shadow-lg flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span>Barangay North Poblacion • Maramag, Bukidnon</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4 Feature Cards Section (Directly from User Reference Image) -->
        <section id="how-it-works" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
            
            <!-- Card 1: Multi-Language Support -->
            <div class="p-6 md:p-8 rounded-3xl bg-white dark:bg-[#0B132B] border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all group relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-blue-500/10 group-hover:scale-150 transition-transform"></div>
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30 mb-5 group-hover:scale-105 transition-transform">
                    <!-- Translate Icon (文A) -->
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-[#12345B] dark:text-white leading-snug">
                    Multi-Language Support
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 leading-relaxed">
                    File your complaint in Cebuano, Bisaya, Filipino/Tagalog or English.
                </p>
            </div>

            <!-- Card 2: AI Analysis & Routing -->
            <div class="p-6 md:p-8 rounded-3xl bg-white dark:bg-[#0B132B] border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all group relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-indigo-500/10 group-hover:scale-150 transition-transform"></div>
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30 mb-5 group-hover:scale-105 transition-transform">
                    <!-- AI Chip / Processor Icon -->
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                        <rect x="9" y="9" width="6" height="6"/>
                        <path stroke-linecap="round" d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-[#12345B] dark:text-white leading-snug">
                    AI Analysis & Routing
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 leading-relaxed">
                    AI translates, detects the category, sets a priority, and routes it to the right office.
                </p>
            </div>

            <!-- Card 3: Transparent Review -->
            <div class="p-6 md:p-8 rounded-3xl bg-white dark:bg-[#0B132B] border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all group relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-emerald-500/10 group-hover:scale-150 transition-transform"></div>
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30 mb-5 group-hover:scale-105 transition-transform">
                    <!-- Shield Check Icon -->
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-[#12345B] dark:text-white leading-snug">
                    Transparent Review
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 leading-relaxed">
                    A barangay administrator reviews and validates every complaint before action.
                </p>
            </div>

            <!-- Card 4: Real-Time Tracking -->
            <div class="p-6 md:p-8 rounded-3xl bg-white dark:bg-[#0B132B] border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all group relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-cyan-500/10 group-hover:scale-150 transition-transform"></div>
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30 mb-5 group-hover:scale-105 transition-transform">
                    <!-- Location / Tracking Pin Icon -->
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-[#12345B] dark:text-white leading-snug">
                    Real-Time Tracking
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 leading-relaxed">
                    Follow the status of your complaint from submission to resolution with full history.
                </p>
            </div>
        </section>
    </div>
</x-layouts.guest>
