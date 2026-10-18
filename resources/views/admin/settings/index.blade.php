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
                <p class="text-xs text-slate-500 mb-4">Update administrator display name, photo, and contact number</p>

                <form method="POST" action="{{ route('admin.settings.update-profile') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Profile Picture Upload & Preview -->
                    <div class="flex items-center gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="relative w-16 h-16 rounded-2xl overflow-hidden ring-4 ring-blue-500/20 shadow-sm bg-slate-100 dark:bg-slate-800 shrink-0">
                            <img id="admin-avatar-preview" src="{{ $admin->avatarUrl() }}" alt="{{ $admin->name }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <label for="admin-avatar-input" class="cursor-pointer inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 text-blue-700 dark:text-blue-300 font-bold text-xs transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Change Profile Picture</span>
                            </label>
                            <input type="file" id="admin-avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewAdminAvatar(this)">
                            <p class="text-[10px] text-slate-400 mt-1">Supports JPG, PNG, WEBP, SVG up to 5MB</p>
                        </div>
                    </div>

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

    @push('scripts')
    <script>
        function previewAdminAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('admin-avatar-preview');
                    if (preview) {
                        preview.src = e.target.result;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-layouts.admin>
