<x-layouts.admin title="Reports & Community Analytics">
    <!-- Print Stylesheet -->
    <style>
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-family: 'Times New Roman', Times, serif !important;
            }
            header, nav, aside, footer, #ai-floating-widget, #toast-container, .print\:hidden {
                display: none !important;
            }
            .print\:block {
                display: block !important;
            }
            .printable-report {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 10px !important;
            }
            @page {
                size: A4 landscape;
                margin: 10mm 15mm;
            }
        }
    </style>

    <div class="space-y-6">

        <!-- Official Printable Letterhead (Only visible during print) -->
        <div class="hidden print:block printable-report font-serif text-black mb-4">
            <div class="border-b-4 border-double border-slate-900 pb-3 mb-4 text-center">
                <!-- Single Official Seal Icon -->
                <div class="w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                    <img src="{{ asset('images/barangay-logo.svg') }}" alt="Barangay North Poblacion Seal" class="w-16 h-16 object-contain">
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-slate-800">Republic of the Philippines • Province of Bukidnon</p>
                    <h1 class="text-lg font-black uppercase text-slate-950 tracking-wider">BARANGAY NORTH POBLACION • MARAMAG</h1>
                    <p class="text-xs font-bold text-slate-900 italic">OFFICIAL CITIZEN COMPLAINTS & INCIDENT ANALYTICS EXECUTIVE SUMMARY</p>
                    <p class="text-[9px] text-slate-600 mt-0.5">
                        Reporting Period: {{ request('start_date', 'All time') }} to {{ request('end_date', now()->format('Y-m-d')) }} • Generated: {{ now()->format('F d, Y • h:i A') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Header & Export Action -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 print:hidden">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin Portal</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-slate-300 font-semibold">Reports & Analytics</span>
                </nav>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Barangay Incident Analytics & Reports</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Official monitoring metrics, Purok complaint summaries, and downloadable CSV records for local governance.</p>
            </div>

            <div class="flex items-center gap-2.5">
                <!-- Print Report Button -->
                <button type="button" 
                        onclick="window.print()" 
                        class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-slate-900 via-blue-900 to-[#12345B] hover:from-black hover:via-blue-950 hover:to-slate-900 text-white font-extrabold text-xs shadow-md shadow-blue-950/20 hover:shadow-lg hover:scale-102 active:scale-95 transition-all border border-blue-400/20 group cursor-pointer"
                        title="Print Executive Report">
                    <div class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-amber-400 group-hover:text-slate-950 text-amber-300 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </div>
                    <span>Print Executive Report</span>
                </button>

                <!-- Export CSV Button -->
                <a href="{{ route('admin.reports.export', request()->query()) }}"
                   class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 hover:from-emerald-700 hover:via-teal-700 hover:to-emerald-800 text-white font-extrabold text-xs shadow-md shadow-emerald-900/20 hover:shadow-lg hover:scale-102 active:scale-95 transition-all border border-emerald-400/20 group cursor-pointer"
                   title="Export Official CSV">
                    <div class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white group-hover:text-emerald-700 text-emerald-200 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span>Export Official CSV</span>
                </a>
            </div>
        </div>

        <!-- Filtered KPI Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Filtered Records</p>
                <p class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ number_format($totalFiltered) }}</p>
                <p class="text-[11px] text-slate-500 mt-1">Total in current selection</p>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <p class="text-[11px] font-bold text-amber-500 uppercase tracking-wider">In Progress / Review</p>
                <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ number_format($inProgressFiltered) }}</p>
                <p class="text-[11px] text-slate-500 mt-1">Pending official resolution</p>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Resolved</p>
                <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($resolvedFiltered) }}</p>
                <p class="text-[11px] text-slate-500 mt-1">Action completed</p>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider">Resolution Rate</p>
                <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $resolutionRate }}%</p>
                <p class="text-[11px] text-slate-500 mt-1">Efficiency index</p>
            </div>
        </div>

        <!-- Filter Controls Form (Hidden on Print) -->
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs print:hidden">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span>Filter Complaint Datasets</span>
            </h2>

            <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <!-- Start Date -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">From Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">To Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Purok -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Purok Area</label>
                    <select name="purok_id" class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                        <option value="">All Puroks</option>
                        @foreach($puroks as $p)
                            <option value="{{ $p->id }}" {{ request('purok_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Category</label>
                    <select name="category_id" class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Complaints Table -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Complaints Registry</h3>
                    <p class="text-xs text-slate-500">Showing {{ $complaints->firstItem() ?? 0 }} - {{ $complaints->lastItem() ?? 0 }} of {{ $complaints->total() }} records</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3">Reference #</th>
                            <th class="px-4 py-3">Title / Subject</th>
                            <th class="px-4 py-3">Resident</th>
                            <th class="px-4 py-3">Purok</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Priority</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Incident Date</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($complaints as $c)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-4 py-3 font-mono font-bold text-blue-600 dark:text-blue-400">
                                    {{ $c->reference_number }}
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate font-medium text-slate-900 dark:text-white" title="{{ $c->title }}">
                                    {{ $c->title }}
                                </td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                                    {{ $c->resident?->name ?? 'Anonymous' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                                    {{ $c->purok?->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                                    {{ $c->category?->name ?? 'Uncategorized' }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $priorityClasses = [
                                            'urgent' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300',
                                            'high' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
                                            'medium' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300',
                                            'low' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $priorityClasses[$c->final_priority] ?? $priorityClasses['medium'] }}">
                                        {{ $c->final_priority }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusClasses = [
                                            'submitted' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300',
                                            'under_review' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
                                            'in_progress' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300',
                                            'resolved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
                                            'rejected' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClasses[$c->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ str_replace('_', ' ', $c->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                        {{ $c->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('M d, Y') }}
                                    </div>
                                    <div class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/></svg>
                                        <span>{{ $c->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('h:i A') }}</span>
                                        <span class="text-slate-400 text-[10px]">({{ $c->created_at->diffForHumans() }})</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.complaints.show', $c->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-xs hover:shadow transition-all hover:scale-102 active:scale-95 shrink-0"
                                       title="View Complaint Details">
                                        <svg class="w-3.5 h-3.5 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>View Details</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate-500">
                                    No complaints match the specified filter parameters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($complaints->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $complaints->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.admin>
