<x-layouts.resident>
    <x-slot:title>Notifications</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Notification Center</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real-time status updates and official barangay notifications</p>
            </div>
            @if($notifications->where('is_read', false)->isNotEmpty())
                <form method="POST" action="{{ route('resident.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 transition-colors">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($notifications as $notif)
                    <div class="py-4 flex items-start justify-between gap-4 {{ $notif->is_read ? '' : 'bg-blue-50/50 dark:bg-blue-950/20 -mx-4 px-4 rounded-2xl' }}">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-2xl {{ $notif->is_read ? 'bg-slate-100 dark:bg-slate-800 text-slate-500' : 'bg-blue-600 text-white' }} flex items-center justify-center shrink-0 mt-0.5 font-bold text-xs">
                                @if($notif->type === 'urgent_alert')
                                    🚨
                                @elseif($notif->type === 'complaint_status_updated')
                                    🔔
                                @else
                                    📌
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#12345B] dark:text-white">
                                    {{ $notif->title }}
                                </h3>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                                    {{ $notif->message }}
                                </p>
                                <span class="text-[11px] text-slate-400 mt-1 block">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center gap-2">
                            @if(! $notif->is_read)
                                <form method="POST" action="{{ route('resident.notifications.read', $notif->id) }}">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Mark as Read">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-400">
                        No notifications yet.
                    </div>
                @endforelse
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-layouts.resident>
