<x-layouts.resident>
    <x-slot:title>My Profile</x-slot:title>

    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Resident Profile</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage your personal community records and verification details</p>
        </div>

        <!-- Profile Card -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm space-y-6">
            <!-- Header Badges -->
            <div class="flex items-center gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
                <div class="w-16 h-16 rounded-3xl bg-blue-600/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 font-black text-xl flex items-center justify-center shrink-0">
                    {{ $user->initials() }}
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-[#12345B] dark:text-white">{{ $user->name }}</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            Verified Resident
                        </span>
                        <span class="text-xs text-slate-400">{{ $user->email }}</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('resident.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Full Name
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Mobile Phone Number
                        </label>
                        <input type="text" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               required 
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('phone')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Purok Assignment
                        </label>
                        <select name="purok_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('address')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Update Avatar Image (Optional)
                    </label>
                    <input type="file" 
                           name="avatar" 
                           accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/40 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100">
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-3 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white font-bold text-xs shadow-md transition-all">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.resident>
