<x-layouts.admin>
    <x-slot:header>Official Administrator Dashboard</x-slot:header>

    <div class="space-y-8">
        <!-- Top Stats Row: Key Barangay KPIs -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Complaints -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Complaints</span>
                    <span class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-black text-[#12345B] dark:text-white">{{ $totalComplaints }}</div>
                    <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                        <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $newComplaints }} new</span>
                        <span>•</span>
                        <span>{{ $inProgress }} in progress</span>
                    </div>
                </div>
            </div>

            <!-- Pending Registrations -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending Residents</span>
                    <span class="p-2 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-black text-[#12345B] dark:text-white">{{ $pendingApprovals }}</div>
                    <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                        <a href="{{ route('admin.residents.pending') }}" class="text-amber-600 dark:text-amber-400 font-bold hover:underline">
                            Review applications &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Resolved Cases -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Resolved Issues</span>
                    <span class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-black text-[#12345B] dark:text-white">{{ $resolvedComplaints }}</div>
                    <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                        @php $rate = $totalComplaints > 0 ? round(($resolvedComplaints / $totalComplaints) * 100, 1) : 0; @endphp
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $rate }}% resolution rate</span>
                    </div>
                </div>
            </div>

            <!-- Urgent & High Priority Alerts -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Urgent / High Priority</span>
                    <span class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-black text-[#12345B] dark:text-white">{{ $urgentComplaints + $highPriorityComplaints }}</div>
                    <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                        <span class="text-rose-600 font-bold">{{ $urgentComplaints }} urgent</span>
                        <span>•</span>
                        <span>{{ $highPriorityComplaints }} high</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Visualizations Grid (Real Database Query Data) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Complaints by Category -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">Complaints by Category</h3>
                    <span class="text-xs text-slate-400">Distribution</span>
                </div>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Complaints by Status -->
            <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">Complaints by Status</h3>
                    <span class="text-xs text-slate-400">Workflow progress</span>
                </div>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Pending Resident Approvals Queue & Recent Complaints -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Pending Approvals Action Queue -->
            <div class="lg:col-span-1 bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-extrabold text-[#12345B] dark:text-white uppercase tracking-wider">
                            Pending Residents
                        </h3>
                        <a href="{{ route('admin.residents.pending') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400">
                            View All ({{ $pendingApprovals }})
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800 mt-2">
                        @forelse($pendingResidentsList as $resident)
                            <div class="py-3.5 flex items-center justify-between gap-3">
                                <div>
                                    <h4 class="text-xs font-extrabold text-slate-900 dark:text-white">{{ $resident->name }}</h4>
                                    <div class="text-[11px] text-slate-400">{{ $resident->purok?->name ?? 'Purok' }} • {{ $resident->phone }}</div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <form method="POST" action="{{ route('admin.residents.approve', $resident->id) }}">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 text-emerald-600 dark:text-emerald-400 text-xs font-bold transition-colors" title="Approve">
                                            ✓
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.residents.show', $resident->id) }}" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs" title="Review">
                                        &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-6 text-center">No pending resident applications.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-center">
                    <span class="text-[11px] text-slate-400">Verified residents only may submit official complaints.</span>
                </div>
            </div>

            <!-- Recent Complaints Table -->
            <div class="lg:col-span-2 bg-white dark:bg-[#111C33] rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">
                        Recent Community Incidents
                    </h3>
                    <a href="{{ route('admin.complaints.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400">
                        View All Complaints &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800 mt-2">
                    @forelse($recentComplaints as $complaint)
                        @php $b = $complaint->statusBadge(); @endphp
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-xs text-blue-600 dark:text-blue-400">{{ $complaint->reference_number }}</span>
                                    <span class="text-xs text-slate-300">•</span>
                                    <span class="text-xs text-slate-500 font-semibold truncate">{{ $complaint->resident?->name }}</span>
                                </div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white truncate mt-0.5">
                                    <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="hover:text-blue-600 transition-colors">
                                        {{ $complaint->title }}
                                    </a>
                                </h4>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $b['bg'] }} {{ $b['text'] }} {{ $b['border'] }}">
                                    {{ $b['label'] }}
                                </span>
                                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600 text-xs font-bold">
                                    Manage &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-6 text-center">No complaints recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Category Chart (Doughnut)
            const catCtx = document.getElementById('categoryChart')?.getContext('2d');
            if (catCtx) {
                const catData = @json($categoriesData);
                new Chart(catCtx, {
                    type: 'doughnut',
                    data: {
                        labels: catData.map(d => d.name),
                        datasets: [{
                            data: catData.map(d => d.count),
                            backgroundColor: [
                                '#2563EB', '#16815D', '#F59E0B', '#EF4444', 
                                '#8B5CF6', '#06B6D4', '#EC4899', '#64748B'
                            ],
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                        }
                    }
                });
            }

            // Status Chart (Bar)
            const statusCtx = document.getElementById('statusChart')?.getContext('2d');
            if (statusCtx) {
                const statusCounts = @json($statusCounts);
                new Chart(statusCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Submitted', 'Under Review', 'In Progress', 'Resolved', 'Rejected'],
                        datasets: [{
                            label: 'Number of Complaints',
                            data: [
                                statusCounts.submitted || 0,
                                statusCounts.under_review || 0,
                                statusCounts.in_progress || 0,
                                statusCounts.resolved || 0,
                                statusCounts.rejected || 0
                            ],
                            backgroundColor: ['#3B82F6', '#F59E0B', '#F97316', '#10B981', '#EF4444'],
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.admin>
