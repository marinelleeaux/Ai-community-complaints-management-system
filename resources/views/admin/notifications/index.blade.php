<x-layouts.admin>
    <x-slot:header>Official Notifications</x-slot:header>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Admin Notification Center</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real-time alerts, citizen complaints, and resident registration logs</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                @if($notifications->isNotEmpty())
                    <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/40 dark:hover:bg-blue-900/60 text-xs font-bold text-blue-700 dark:text-blue-300 transition-colors">
                            Mark All as Read
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.notifications.unread-all') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 transition-colors">
                            Mark All as Unread
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.notifications.delete-all') }}" onsubmit="return confirmAction(event, 'Are you sure you want to delete all notifications?', 'Delete All Notifications', 'Yes, Delete All');">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-xs font-bold text-rose-600 dark:text-rose-400 transition-colors">
                            Delete All
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-[#111C33] rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($notifications as $notif)
                    <div class="py-4 flex items-start justify-between gap-4 {{ $notif->is_read ? 'opacity-75' : 'bg-blue-50/50 dark:bg-blue-950/20 -mx-4 px-4 rounded-2xl' }}">
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
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-extrabold text-[#12345B] dark:text-white">
                                        {{ $notif->title }}
                                    </h3>
                                    @if(! $notif->is_read)
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                                    {{ $notif->message }}
                                </p>
                                <span class="text-[11px] text-slate-400 mt-1 block">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center gap-1.5">
                            @if(! $notif->is_read)
                                <form method="POST" action="{{ route('admin.notifications.read', $notif->id) }}">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Mark as Read">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.notifications.unread', $notif->id) }}">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Mark as Unread">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.notifications.destroy', $notif->id) }}" onsubmit="return confirmAction(event, 'Are you sure you want to delete this notification?', 'Delete Notification', 'Yes, Delete');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors" title="Delete notification">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-400">
                        No notifications yet.
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
