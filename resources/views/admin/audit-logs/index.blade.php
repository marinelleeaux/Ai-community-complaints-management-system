<x-layouts.admin title="System Audit Logs">
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin Portal</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-slate-300 font-semibold">Audit Logs</span>
                </nav>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">System Security & Audit Trail</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Immutable administrative audit log of official actions, complaint updates, user approvals, and exports.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Audit Logging Active
                </span>
            </div>
        </div>

        <!-- Filter / Search Bar -->
        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by action, official name, entity type or ID..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="sm:w-48">
                    <input type="text" name="action" value="{{ request('action') }}" placeholder="Action keyword..."
                           class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-colors">
                        Search Logs
                    </button>
                    <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3">Timestamp</th>
                            <th class="px-4 py-3">Actor / Official</th>
                            <th class="px-4 py-3">Action Performed</th>
                            <th class="px-4 py-3">Entity Target</th>
                            <th class="px-4 py-3">Payload / Metadata</th>
                            <th class="px-4 py-3">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-4 py-3 text-slate-500 font-mono whitespace-nowrap">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($log->actor)
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ $log->actor->name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $log->actor->role }}</div>
                                    @else
                                        <span class="text-slate-400 italic">System / Anonymous</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $action = $log->action;
                                        $badgeColor = 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300';
                                        if (str_contains($action, 'approve')) {
                                            $badgeColor = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300';
                                        } elseif (str_contains($action, 'reject') || str_contains($action, 'delete')) {
                                            $badgeColor = 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300';
                                        } elseif (str_contains($action, 'create') || str_contains($action, 'register')) {
                                            $badgeColor = 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300';
                                        } elseif (str_contains($action, 'update') || str_contains($action, 'status')) {
                                            $badgeColor = 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300';
                                        } elseif (str_contains($action, 'export')) {
                                            $badgeColor = 'bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300';
                                        }
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md font-mono text-[11px] font-bold {{ $badgeColor }}">
                                        {{ $log->action }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($log->entity_type)
                                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $log->entity_type }}</span>
                                        @if($log->entity_id)
                                            <span class="text-[10px] text-slate-400 font-mono">#{{ Str::limit($log->entity_id, 8) }}</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 max-w-xs truncate text-[11px] font-mono text-slate-600 dark:text-slate-400" title="{{ json_encode($log->safe_metadata) }}">
                                    @if($log->safe_metadata)
                                        {{ Str::limit(json_encode($log->safe_metadata), 60) }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    No audit log entries recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.admin>
