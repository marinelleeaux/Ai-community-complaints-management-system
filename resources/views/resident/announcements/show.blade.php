<x-layouts.resident>
    <x-slot:title>{{ $announcement->title }}</x-slot:title>

    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <a href="{{ route('resident.announcements.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back to Announcements</span>
            </a>
            <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 mb-2">
                {{ $announcement->category }}
            </span>
            <h2 class="text-2xl md:text-3xl font-black text-[#12345B] dark:text-white leading-tight">
                {{ $announcement->title }}
            </h2>
            <div class="text-xs text-slate-400 mt-2">
                Published on {{ $announcement->published_at ? $announcement->published_at->format('F d, Y') : 'Recent' }} • By {{ $announcement->author?->name ?? 'Barangay Office' }}
            </div>
        </div>

        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm space-y-6">
            @if($announcement->cover_image_path)
                <img src="{{ $announcement->coverImageUrl() }}" alt="{{ $announcement->title }}" class="w-full h-72 object-cover rounded-2xl shadow-sm">
            @endif

            @if($announcement->event_start)
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block">Event Schedule</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-sm mt-0.5 block">
                            {{ $announcement->event_start->format('M d, Y • h:i A') }}
                            @if($announcement->event_end)
                                to {{ $announcement->event_end->format('h:i A') }}
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Venue / Location</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-sm mt-0.5 block">
                            {{ $announcement->event_location ?? 'Barangay North Poblacion Hall' }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="text-sm md:text-base text-slate-700 dark:text-slate-200 leading-relaxed whitespace-pre-line">
                {{ $announcement->description }}
            </div>
        </div>
    </div>
</x-layouts.resident>
