<x-layouts.resident>
    <x-slot:title>Announcements & Events</x-slot:title>

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Community Announcements</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Stay updated with official bulletins, projects, and events in Barangay North Poblacion</p>
        </div>

        <!-- Filter / Search -->
        <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-5 border border-slate-100 dark:border-slate-800 shadow-sm">
            <form method="GET" action="{{ route('resident.announcements.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search announcements..." 
                       class="flex-1 px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select name="category" class="px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white text-xs font-bold transition-colors">
                    Search
                </button>
            </form>
        </div>

        <!-- Announcements Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($announcements as $announcement)
                <div class="bg-white dark:bg-[#1E293B] rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                    @if($announcement->cover_image_path)
                        <img src="{{ $announcement->coverImageUrl() }}" alt="{{ $announcement->title }}" class="w-full h-44 object-cover">
                    @else
                        <div class="w-full h-36 bg-gradient-to-tr from-blue-700 to-indigo-600 p-6 flex flex-col justify-between text-white relative">
                            <span class="px-2.5 py-1 rounded-lg bg-white/20 backdrop-blur-sm text-[10px] font-bold self-start">
                                {{ $announcement->category }}
                            </span>
                            <div class="text-[11px] text-blue-100">
                                Official Bulletin
                            </div>
                        </div>
                    @endif

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-2">
                                <span>{{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : 'Recent' }}</span>
                                @if($announcement->event_start)
                                    <span class="text-blue-600 dark:text-blue-400 font-bold">Event</span>
                                @endif
                            </div>
                            <h3 class="text-base font-extrabold text-[#12345B] dark:text-white leading-snug">
                                <a href="{{ route('resident.announcements.show', $announcement->id) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $announcement->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-3 leading-relaxed">
                                {{ $announcement->description }}
                            </p>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">
                                @if($announcement->event_location)
                                    📍 {{ $announcement->event_location }}
                                @else
                                    Barangay North Poblacion
                                @endif
                            </span>
                            <a href="{{ route('resident.announcements.show', $announcement->id) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                Read More &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-xs text-slate-400">
                    No announcements found matching your criteria.
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $announcements->links() }}
        </div>
    </div>
</x-layouts.resident>
