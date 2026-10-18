<x-layouts.admin>
    <x-slot:header>Complainants Directory Management</x-slot:header>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Complainants Directory</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Directory of verified complainants and citizens of Barangay North Poblacion</p>
            </div>
            <a href="{{ route('admin.residents.pending') }}" 
               class="group inline-flex items-center gap-2.5 px-4.5 py-2.5 rounded-2xl bg-gradient-to-r from-amber-500/15 via-orange-500/10 to-amber-500/5 hover:from-amber-500 hover:to-orange-600 text-amber-700 dark:text-amber-300 hover:text-white dark:hover:text-white border border-amber-300/60 dark:border-amber-700/60 font-bold text-xs transition-all duration-300 shadow-xs hover:shadow-lg hover:shadow-amber-500/20 hover:scale-[1.02] active:scale-95 self-start sm:self-auto">
                <span class="w-6 h-6 rounded-xl bg-amber-500/20 group-hover:bg-white/20 flex items-center justify-center text-amber-600 dark:text-amber-300 group-hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span>Pending Approvals Queue</span>
                @if(($pendingResidentsCount ?? 0) > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 group-hover:bg-white text-white group-hover:text-amber-700 shadow-2xs">
                        {{ $pendingResidentsCount }}
                    </span>
                @else
                    <span class="text-amber-500 group-hover:text-white transition-transform group-hover:translate-x-0.5">&rarr;</span>
                @endif
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <form method="GET" action="{{ route('admin.residents.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-6">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search complainant name, email, phone, or street address..." 
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="sm:col-span-3">
                    <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Account Statuses</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved / Verified</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="sm:col-span-3 flex gap-2">
                    <select name="purok_id" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Puroks</option>
                        @foreach($puroks as $p)
                            <option value="{{ $p->id }}" {{ request('purok_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-bold transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Directory Table -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 uppercase font-black tracking-wider text-[10px]">
                            <th class="p-4 pl-6">Complainant / Citizen</th>
                            <th class="p-4">Email Address</th>
                            <th class="p-4">Phone</th>
                            <th class="p-4">Purok Area</th>
                            <th class="p-4">Account Status</th>
                            <th class="p-4">Date Registered</th>
                            <th class="p-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($residents as $res)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-4 pl-6 font-bold text-slate-900 dark:text-white flex items-center gap-3">
                                    <img src="{{ $res->avatarUrl() }}" alt="{{ $res->name }}" class="w-9 h-9 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700 shrink-0">
                                    <div>
                                        <a href="{{ route('admin.residents.show', $res->id) }}" class="hover:text-blue-600 block font-extrabold">
                                            {{ $res->name }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-normal">ID: #{{ $res->id }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-slate-600 dark:text-slate-300">{{ $res->email }}</td>
                                <td class="p-4 text-slate-600 dark:text-slate-300">{{ $res->phone }}</td>
                                <td class="p-4 font-semibold text-slate-700 dark:text-slate-200">{{ $res->purok?->name ?? 'N/A' }}</td>
                                <td class="p-4">
                                    @if($res->account_status === 'approved')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            Approved
                                        </span>
                                    @elseif($res->account_status === 'rejected')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                        {{ $res->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('M d, Y') }}
                                    </div>
                                    <div class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/></svg>
                                        <span>{{ $res->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('h:i A') }}</span>
                                        <span class="text-slate-400 text-[10px]">({{ $res->created_at->diffForHumans() }})</span>
                                    </div>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.residents.show', $res->id) }}" 
                                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-xs hover:shadow transition-all hover:scale-102 active:scale-95 shrink-0"
                                           title="View Complainant Details">
                                            <svg class="w-3.5 h-3.5 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>View Details</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.residents.destroy', $res->id) }}" onsubmit="return confirmAction(event, 'Are you sure you want to delete this account?', 'Delete Complainant Account', 'Yes, Delete Account');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-bold transition-colors" title="Delete Account">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-xs text-slate-400">
                                    No complainants found matching the search criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $residents->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
