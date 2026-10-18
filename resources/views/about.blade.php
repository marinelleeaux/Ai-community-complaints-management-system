<x-layouts.guest title="About the System - AI-Community Complaint Management System">
    <div class="space-y-10 pb-8 animate-fade-in-up relative">
        
        <!-- Ambient Decorative Fluid Wave / Gradient Blobs -->
        <div class="absolute -top-10 left-1/4 w-[480px] h-[480px] bg-gradient-to-br from-blue-400/15 via-cyan-300/10 to-transparent rounded-full blur-3xl pointer-events-none -z-10 blob-float"></div>
        <div class="absolute top-1/2 right-10 w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-3xl pointer-events-none -z-10 blob-float-delayed"></div>

        <!-- 1. Hero Section with Left Typography and Right Barangay Hall Wave Mask -->
        <section class="relative rounded-3xl overflow-hidden bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-xl">
            <div class="grid grid-cols-1 lg:grid-cols-12 items-center min-h-[440px]">
                
                <!-- Left Text Column (lg:col-span-6) -->
                <div class="lg:col-span-6 p-8 md:p-12 lg:p-14 flex flex-col justify-center z-20 relative">
                    <!-- Pill Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-200/70 dark:border-blue-900/60 text-xs font-bold text-blue-700 dark:text-blue-300 self-start mb-6 shadow-2xs">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 3L2 10h20L12 3z"/>
                        </svg>
                        <span>About Our System</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.14]">
                        About the<br>
                        <span class="text-blue-600 dark:text-blue-400">AI-Community</span><br>
                        Complaint Management System
                    </h1>

                    <!-- Subtitle / Mission Statement -->
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 mt-5 max-w-xl leading-relaxed font-normal">
                        The AI-Community Complaint Management System is a modern, intelligent, and citizen-friendly platform designed to make reporting, tracking, and resolving community concerns in North Poblacion, Maramag more efficient, transparent, and accessible for everyone.
                    </p>
                </div>

                <!-- Right Photo Column with Organic S-Curve Wave Mask (lg:col-span-6) -->
                <div class="lg:col-span-6 relative h-72 sm:h-96 lg:h-full min-h-[380px] lg:min-h-[460px] overflow-hidden bg-slate-100 dark:bg-slate-900">
                    <!-- Real Authentic Photo of Barangay Hall -->
                    <img src="{{ asset('images/barangay-hall.jpg') }}" 
                         alt="Barangay North Poblacion Maramag Hall" 
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
                    <svg class="hidden lg:block absolute inset-y-0 -left-1 h-full w-24 xl:w-32 z-25 text-white dark:text-[#0B172E] pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                        <path d="M0 0 L40 0 C 60 15, 60 35, 45 60 C 25 80, 10 90, 0 100 L 0 100 Z" />
                    </svg>

                    <!-- Mobile Gradient Wave (Top Overlay) -->
                    <div class="lg:hidden absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-white dark:from-[#0B172E] via-white/80 dark:via-[#0B172E]/80 to-transparent pointer-events-none z-10"></div>
                </div>

            </div>
        </section>

        <!-- 2. Four Pillars / Feature Cards (Directly matching User Mockup) -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1: Our Purpose -->
            <div class="p-7 rounded-3xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col group">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-5 group-hover:scale-110 transition-transform">
                    <!-- Bullseye / Target Icon -->
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="6"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2.5">
                    Our Purpose
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                    To strengthen communication between residents and the barangay through a fast, efficient, accessible, and transparent complaint management system.
                </p>
            </div>

            <!-- Card 2: Our Vision -->
            <div class="p-7 rounded-3xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col group">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-5 group-hover:scale-110 transition-transform">
                    <!-- Eye Icon -->
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2.5">
                    Our Vision
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                    A progressive and responsive barangay where every concern is heard, addressed, and resolved for a safer and stronger community.
                </p>
            </div>

            <!-- Card 3: Our Mission -->
            <div class="p-7 rounded-3xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col group">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-5 group-hover:scale-110 transition-transform">
                    <!-- Flag Icon -->
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                        <line x1="4" y1="22" x2="4" y2="15"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2.5">
                    Our Mission
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                    To utilize artificial intelligence and modern technology to provide an efficient, inclusive, and transparent complaint management system for all residents.
                </p>
            </div>

            <!-- Card 4: Our Values -->
            <div class="p-7 rounded-3xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col group">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-5 group-hover:scale-110 transition-transform">
                    <!-- Heart Icon -->
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2.5">
                    Our Values
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                    Transparency, Accountability, Service Excellence, Innovation, and Community Participation.
                </p>
            </div>

        </section>

        <!-- 3. Bottom Summary & Quote Banner (Matching User Mockup) -->
        <section class="rounded-3xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 p-6 md:p-8 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-6 md:gap-8">
            
            <!-- Left: Building Emblem + Community Objective Text -->
            <div class="flex items-center gap-5 md:gap-7 flex-1">
                <!-- Large Civic Building Silhouette -->
                <div class="shrink-0 text-blue-200 dark:text-blue-900/50">
                    <svg class="w-14 h-14 md:w-16 md:h-16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7v3h20V7L12 2zm-8 7l8-4 8 4H4zm1 3v8h2v-8H5zm5 0v8h2v-8h-2zm5 0v8h2v-8h-2zm4 0v8h1v-8h-1zm-15 9v2h18v-2H4z"/>
                    </svg>
                </div>
                <!-- Main Objective Paragraph -->
                <p class="text-xs sm:text-sm md:text-[15px] text-slate-700 dark:text-slate-200 leading-relaxed font-normal">
                    The system aims to build a stronger, safer, and more united North Poblacion by empowering residents to report and monitor community issues, helping the barangay to respond promptly and effectively through the use of Artificial Intelligence.
                </p>
            </div>

            <!-- Right: Elegant Quote Box with Vertical Divider -->
            <div class="w-full lg:w-80 shrink-0 border-t lg:border-t-0 lg:border-l border-slate-200 dark:border-slate-700/80 pt-4 lg:pt-0 lg:pl-8 flex flex-col justify-center">
                <div class="flex items-start gap-3">
                    <!-- Double Blue Quotation Mark -->
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 shrink-0 -mt-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <div>
                        <p class="text-sm md:text-base font-bold italic text-blue-900 dark:text-blue-200 leading-snug">
                            &ldquo;A responsive barangay for a better tomorrow.&rdquo;
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                            &mdash; Barangay North Poblacion, Maramag
                        </p>
                    </div>
                </div>
            </div>

        </section>

    </div>
</x-layouts.guest>
