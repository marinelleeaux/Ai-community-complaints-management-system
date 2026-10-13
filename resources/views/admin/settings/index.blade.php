<x-layouts.admin title="System Settings & Master Data">
    <div class="space-y-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin Portal</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-slate-300 font-semibold">Settings</span>
                </nav>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">System Settings & Master Data</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage official Purok zones, complaint classifications, infrastructure connections, and administrative security.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Integration Status Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Supabase Status Card -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.996 0A12 12 0 0 0 0 11.996a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm1.706 17.072l-4.702 3.125c-.717.477-1.636-.12-1.488-.967l1.527-8.706-5.834.787a.89.89 0 0 1-.954-1.222l8.03-9.52c.628-.744 1.79-.317 1.77.653l-.15 7.42 5.068-.683c.773-.104 1.34.697.945 1.365l-4.212 7.745z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white">Supabase Cloud Platform</h3>
                            <p class="text-[11px] text-slate-500">PostgreSQL Database, Auth & Object Storage</p>
                        </div>
                    </div>
                    @if($supabaseStatus['is_configured'])
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            CONNECTED
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300">
                            FALLBACK MODE (LOCAL)
                        </span>
                    @endif
                </div>

                <div class="space-y-2 text-xs font-mono bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between">
                        <span class="text-slate-500">API Endpoint:</span>
                        <span class="text-slate-800 dark:text-slate-200 truncate max-w-xs">{{ $supabaseStatus['url'] ?: 'Configured via .env' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Anon Public Key:</span>
                        <span class="{{ $supabaseStatus['has_anon_key'] ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">
                            {{ $supabaseStatus['has_anon_key'] ? 'Configured' : 'Missing' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Service Role Key:</span>
                        <span class="{{ $supabaseStatus['has_service_key'] ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">
                            {{ $supabaseStatus['has_service_key'] ? 'Configured' : 'Missing' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- AI Engine Status Card -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white">North Poblacion AI Engine</h3>
                            <p class="text-[11px] text-slate-500">Complaint Triage & Assistant (Bisaya / Tagalog / English)</p>
                        </div>
                    </div>
                    @if($aiStatus['is_configured'])
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300">
                            ONLINE ({{ strtoupper($aiStatus['provider']) }})
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300">
                            RULE-BASED NLP (OFFLINE SAFE)
                        </span>
                    @endif
                </div>

                <div class="space-y-2 text-xs font-mono bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Active Provider:</span>
                        <span class="text-slate-800 dark:text-slate-200 capitalize">{{ $aiStatus['provider'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Model Name:</span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $aiStatus['model'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">LGU Context:</span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $aiStatus['barangay'] }}, {{ $aiStatus['municipality'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Master Data Management: Puroks & Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Purok Management -->
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-base text-slate-900 dark:text-white">Official Puroks Directory</h2>
                        <p class="text-xs text-slate-500">Registered residential zones of Barangay North Poblacion</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400 font-mono">{{ $puroks->count() }} Puroks</span>
                </div>

                <!-- Add Purok Form -->
                <form method="POST" action="{{ route('admin.settings.add-purok') }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="New Purok Name (e.g. Purok 13)" required
                           class="flex-1 px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl transition-colors shrink-0">
                        Add Purok
                    </button>
                </form>

                <!-- Purok List -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-96 overflow-y-auto pr-1">
                    @foreach($puroks as $p)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $p->name }}</span>
                                <span class="ml-2 text-[11px] text-slate-400 font-mono">({{ $p->complaints_count }} complaints)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $p->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $p->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <form method="POST" action="{{ route('admin.settings.toggle-purok', $p->id) }}">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white underline transition-colors">
                                        {{ $p->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Category Management -->
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-base text-slate-900 dark:text-white">Complaint Classifications</h2>
                        <p class="text-xs text-slate-500">Standard community issue types for AI classification & triage</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400 font-mono">{{ $categories->count() }} Categories</span>
                </div>

                <!-- Add Category Form -->
                <form method="POST" action="{{ route('admin.settings.add-category') }}" class="space-y-2">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="name" placeholder="Category Name" required
                               class="flex-1 px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl transition-colors shrink-0">
                            Add Category
                        </button>
                    </div>
                    <input type="text" name="description" placeholder="Optional brief description..."
                           class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                </form>

                <!-- Category List -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-96 overflow-y-auto pr-1">
                    @foreach($categories as $cat)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <div class="max-w-xs">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $cat->name }}</span>
                                <span class="ml-2 text-[11px] text-slate-400 font-mono">({{ $cat->complaints_count }})</span>
                                @if($cat->description)
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $cat->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $cat->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <form method="POST" action="{{ route('admin.settings.toggle-category', $cat->id) }}">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white underline transition-colors">
                                        {{ $cat->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Admin Profile & Password Security -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Profile Details -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1">Official Account Information</h3>
                <p class="text-xs text-slate-500 mb-4">Update administrator display name and contact number</p>

                <form method="POST" action="{{ route('admin.settings.update-profile') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Full Official Name</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                               class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Official Email Address</label>
                        <input type="email" value="{{ $admin->email }}" disabled
                               class="w-full px-3 py-2 text-xs rounded-xl bg-slate-100 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 text-slate-500 cursor-not-allowed">
                        <p class="text-[10px] text-slate-400 mt-1">Managed via Supabase Auth credentials</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Contact Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" placeholder="+63 912 345 6789"
                               class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-colors">
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1">Administrator Password</h3>
                <p class="text-xs text-slate-500 mb-4">Ensure strong credential security for the barangay administrative console</p>

                <form method="POST" action="{{ route('admin.settings.update-password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">New Password</label>
                        <input type="password" name="password" required
                               class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white dark:bg-blue-600 dark:hover:bg-blue-700 font-semibold text-xs transition-colors">
                            Update Admin Password
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</x-layouts.admin>
