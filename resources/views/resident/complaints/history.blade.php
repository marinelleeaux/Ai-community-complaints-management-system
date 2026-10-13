<x-layouts.resident>
    <x-slot:title>Complaint History</x-slot:title>

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Complaint Activity History</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Chronological record of status changes across all your submitted complaints</p>
        </div>

        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($history as $item)
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-xs text-blue-600 dark:text-blue-400">
                                    {{ $item->complaint?->reference_number }}
                                </span>
                                <span class="text-xs text-slate-400">•</span>
                                <span class="font-bold text-sm text-[#12345B] dark:text-white">
                                    {{ $item->complaint?->title }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-600 dark:text-slate-300 mt-1">
                                Status changed to: <strong>{{ ucfirst(str_replace('_', ' ', $item->new_status)) }}</strong>
                                @if($item->public_remarks)
                                    — "{{ $item->public_remarks }}"
                                @endif
                            </div>
                        </div>
                        <div class="text-xs text-slate-400 text-right shrink-0">
                            {{ $item->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-400">
                        No complaint activity history yet.
                    </div>
                @endforelse
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                {{ $history->links() }}
            </div>
        </div>
    </div>
</x-layouts.resident>
