<x-layouts.admin>
    <x-slot:header>Resident Directory Management</x-slot:header>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Resident Directory</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage registered citizens of Barangay North Poblacion, approvals, and access controls</p>
            </div>
            <a href="{{ route('admin.residents.pending') }}" class="px-5 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs transition-colors self-start sm:self-auto">
                Review Pending Queue &rarr;
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <form method="GET" action="{{ route('admin.residents.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-6">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search resident name, email, phone, or street address..." 
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="sm:col-span-3">
                    <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Account Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved / Active</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive / Deactivated</option>
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
                            <th class="p-4 pl-6">Resident Name</th>
                            <th class="p-4">Email Address</th>
                            <th class="p-4">Phone</th>
                            <th class="p-4">Purok</th>
                            <th class="p-4">Account Status</th>
                            <th class="p-4">Registered Date</th>
                            <th class="p-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($residents as $res)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-4 pl-6 font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ $res->initials() }}
                                    </div>
                                    <a href="{{ route('admin.residents.show', $res->id) }}" class="hover:text-blue-600">
                                        {{ $res->name }}
                                    </a>
                                </td>
                                <td class="p-4 text-slate-600 dark:text-slate-300">{{ $res->email }}</td>
                                <td class="p-4 text-slate-600 dark:text-slate-300">{{ $res->phone }}</td>
                                <td class="p-4 font-semibold text-slate-700 dark:text-slate-200">{{ $res->purok?->name ?? 'N/A' }}</td>
                                <td class="p-4">
                                    @if($res->account_status === 'approved')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            Approved
                                        </span>
                                    @elseif($res->account_status === 'pending')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            Pending
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
                                <td class="p-4 text-slate-400">{{ $res->created_at->format('M d, Y') }}</td>
                                <td class="p-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($res->account_status === 'pending')
                                            <form method="POST" action="{{ route('admin.residents.approve', $res->id) }}">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700">Approve</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.residents.show', $res->id) }}" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-xs text-slate-400">
                                    No residents found matching the search criteria.
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
