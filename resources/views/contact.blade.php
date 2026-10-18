<x-layouts.guest title="Contact Us - AI-Community Complaint Management System">
    <div class="space-y-10 pb-10 animate-fade-in-up relative">

        <!-- Ambient Background Floating Blobs -->
        <div class="absolute -top-10 left-10 w-96 h-96 rounded-full bg-blue-400/15 dark:bg-blue-600/10 blur-3xl pointer-events-none -z-10 blob-float"></div>
        <div class="absolute top-1/2 right-10 w-96 h-96 rounded-full bg-cyan-400/15 dark:bg-indigo-600/10 blur-3xl pointer-events-none -z-10 blob-float-delayed"></div>

        <!-- 1. Main 3-Column Section (Directly from User Reference Screenshot) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">
            
            <!-- Left Column: Header & Info Cards (lg:col-span-3) -->
            <div class="lg:col-span-3 flex flex-col justify-between space-y-5">
                <div>
                    <!-- Pill Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-200/60 dark:border-blue-900/60 text-xs font-black text-blue-700 dark:text-blue-300 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        <span>GET IN TOUCH</span>
                    </div>

                    <!-- Heading -->
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight mt-3">
                        Contact Us
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal mt-2">
                        Have questions, suggestions, or need assistance? We're here to help. Reach out to us through the following channels, and we'll get back to you as soon as possible.
                    </p>
                </div>

                <!-- 3 Stacked Info Cards -->
                <div class="space-y-3">
                    <!-- Phone Card -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center gap-3.5 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Phone</div>
                            <div class="text-xs font-black text-slate-900 dark:text-white mt-0.5">+63 917 123 4567</div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500">Mon &ndash; Fri, 8:00 AM &ndash; 5:00 PM</div>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center gap-3.5 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Email</div>
                            <div class="text-xs font-black text-slate-900 dark:text-white mt-0.5 truncate">northpoblacion.maramag@gmail.com</div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500">We reply within 1&ndash;2 business days.</div>
                        </div>
                    </div>

                    <!-- Address Card -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center gap-3.5 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Address</div>
                            <div class="text-xs font-black text-slate-900 dark:text-white mt-0.5 leading-snug">North Poblacion, Maramag, Bukidnon, Philippines</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Column: "Send Us a Message" Form Card (lg:col-span-5) -->
            <div class="lg:col-span-5 p-7 md:p-8 rounded-3xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <!-- Form Header with Blue Airplane Icon -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                            <svg class="w-6 h-6 transform -rotate-45 -translate-y-0.5 translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-900 dark:text-white leading-tight">Send Us a Message</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Fill out the form below and we'll get back to you shortly.</p>
                        </div>
                    </div>

                    <!-- Flash Message -->
                    @if(session('success'))
                        <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Contact Form -->
                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Row 1: Full Name & Email Address -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       required
                                       value="{{ old('name') }}"
                                       placeholder="Full Name"
                                       class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>

                            <!-- Email Address -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       required
                                       value="{{ old('email') }}"
                                       placeholder="Email Address"
                                       class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                        </div>

                        <!-- Row 2: Subject -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="subject" 
                                   required
                                   value="{{ old('subject') }}"
                                   placeholder="Subject"
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all">
                        </div>

                        <!-- Row 3: Message -->
                        <div class="relative">
                            <div class="absolute top-3.5 left-3.5 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <textarea name="message" 
                                      rows="4" 
                                      required
                                      placeholder="Your Message..."
                                      class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all resize-none">{{ old('message') }}</textarea>
                        </div>

                        <!-- Row 4: Submit Button -->
                        <button type="submit" 
                                class="w-full py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-lg shadow-blue-600/25 hover:shadow-xl transition-all flex items-center justify-center gap-2 group">
                            <svg class="w-4 h-4 transform -rotate-45 -translate-y-0.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Clean Barangay Hall Photo (No Blob) (lg:col-span-4) -->
            <div class="lg:col-span-4 relative rounded-3xl overflow-hidden bg-slate-100 dark:bg-[#071426] shadow-sm min-h-[360px] lg:min-h-full flex items-center justify-center border border-slate-200/80 dark:border-slate-800 group">
                <!-- Clean High-Res Barangay Hall Photo -->
                <img src="{{ asset('images/barangay-hall.jpg') }}" 
                     alt="Barangay Hall North Poblacion Maramag" 
                     class="w-full h-full object-cover object-center scale-100 group-hover:scale-105 transition-transform duration-700">
            </div>

        </div>

        <!-- 2. Bottom Full-Width 4-Column Card (Directly from User Screenshot) -->
        <div class="rounded-3xl bg-white dark:bg-[#0B172E] border border-slate-200/80 dark:border-slate-800 p-6 md:p-7 shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-center">
            
            <!-- Col 1: Our Location -->
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 dark:text-white">Our Location</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">
                        North Poblacion, Maramag, Bukidnon, Philippines
                    </p>
                </div>
            </div>

            <!-- Col 2: Call Us -->
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 dark:text-white">Call Us</h4>
                    <p class="text-[11px] font-bold text-slate-800 dark:text-slate-200 mt-0.5">+63 917 123 4567</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Mon &ndash; Fri, 8:00 AM &ndash; 5:00 PM</p>
                </div>
            </div>

            <!-- Col 3: Email Us -->
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-950/70 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h4 class="text-xs font-black text-slate-900 dark:text-white">Email Us</h4>
                    <p class="text-[11px] font-bold text-slate-800 dark:text-slate-200 mt-0.5 truncate">northpoblacion.maramag@gmail.com</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">We reply within 1&ndash;2 business days.</p>
                </div>
            </div>

            <!-- Col 4: Stylized Map Card with Red Marker & Google Maps Link -->
            <div class="relative h-24 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center group">
                <!-- SVG Stylized Map Background (Roads, Greenery, Grid) -->
                <svg class="absolute inset-0 w-full h-full object-cover text-emerald-100 dark:text-slate-800" viewBox="0 0 240 100" preserveAspectRatio="none" fill="currentColor">
                    <rect width="240" height="100" fill="#f1f5f9" class="dark:fill-[#0d1b2a]"/>
                    <!-- Green park areas -->
                    <path d="M0,0 L60,0 L40,40 L0,20 Z" fill="#dcfce7" class="dark:fill-[#132a13]/40"/>
                    <path d="M180,60 L240,40 L240,100 L160,100 Z" fill="#dcfce7" class="dark:fill-[#132a13]/40"/>
                    <!-- Road lines -->
                    <path d="M-10,50 Q100,60 250,30" stroke="#cbd5e1" class="dark:stroke-slate-700" stroke-width="8" fill="none"/>
                    <path d="M110,0 L130,100" stroke="#cbd5e1" class="dark:stroke-slate-700" stroke-width="6" fill="none"/>
                    <path d="M40,100 Q120,40 180,0" stroke="#cbd5e1" class="dark:stroke-slate-700" stroke-width="5" fill="none"/>
                </svg>

                <!-- Red Pin Marker & Label -->
                <div class="relative z-10 flex flex-col items-center">
                    <svg class="w-6 h-6 text-rose-500 drop-shadow-md animate-bounce" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span class="text-[9px] font-black text-slate-800 dark:text-slate-200 bg-white/90 dark:bg-slate-900/90 px-1.5 py-0.5 rounded shadow-2xs mt-0.5">
                        North Poblacion, Maramag
                    </span>
                </div>

                <!-- Google Maps Link Button -->
                <a href="https://maps.google.com/?q=Barangay+North+Poblacion+Maramag+Bukidnon" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="absolute top-2 right-2 z-20 px-2.5 py-1 rounded-lg bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-700 text-[10px] font-black text-blue-600 dark:text-blue-400 shadow-xs hover:bg-blue-600 hover:text-white transition-all flex items-center gap-1">
                    <span>View on Google Maps</span>
                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>

        </div>

    </div>
</x-layouts.guest>
