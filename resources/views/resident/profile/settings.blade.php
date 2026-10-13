<x-layouts.resident>
    <x-slot:title>Settings & Preferences</x-slot:title>

    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Settings & Preferences</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Customize your interface theme, security, and notification settings</p>
        </div>

        <!-- Profile Information Summary Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    @if($user->avatarUrl())
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-blue-500/30">
                    @else
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#12345B] to-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md">
                            {{ $user->initials() }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">{{ $user->name }}</h3>
                        <p class="text-xs text-slate-400">{{ $user->email }} • {{ $user->purok?->name ?? 'Verified Resident' }}</p>
                    </div>
                </div>
                <a href="{{ route('resident.profile') }}" class="px-4 py-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 text-blue-600 dark:text-blue-300 font-bold text-xs transition-colors">
                    Edit Profile Photo & Info
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-1">
                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                    <span class="block text-slate-400 font-medium text-[11px]">Contact Phone</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ $user->phone ?: 'None provided' }}</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                    <span class="block text-slate-400 font-medium text-[11px]">Purok Residence</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ $user->purok?->name ?? 'North Poblacion' }}</span>
                </div>
            </div>
        </div>

        <!-- Security / Password Update Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
            <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">Security & Password</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Ensure your account is using a strong, unique password.</p>

            <form method="POST" action="{{ route('resident.settings.password') }}" class="space-y-4 pt-2">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Current Password
                    </label>
                    <input type="password" 
                           name="current_password" 
                           required 
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('current_password')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            New Password
                        </label>
                        <input type="password" 
                               name="password" 
                               required 
                               placeholder="Min 8 characters"
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Confirm New Password
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               required 
                               placeholder="Repeat new password"
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white font-bold text-xs shadow-md transition-all">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Privacy Information -->
        <div class="p-5 rounded-3xl bg-slate-100/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 space-y-1.5">
            <h4 class="font-bold text-slate-700 dark:text-slate-300">Republic Act No. 10173 (Data Privacy Act of 2012)</h4>
            <p>
                All personal information and community incident descriptions submitted through this system are processed in accordance with the Data Privacy Act of 2012. Records are confidential and used solely by authorized Barangay North Poblacion officials for municipal incident response.
            </p>
        </div>
    </div>
</x-layouts.resident>
