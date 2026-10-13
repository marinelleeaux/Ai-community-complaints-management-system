<x-layouts.admin>
    <x-slot:header>Announcements & Events Management</x-slot:header>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#12345B] dark:text-white">Barangay Announcements</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Publish community bulletins, advisories, and town hall events</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all self-start sm:self-auto">
                + New Announcement
            </a>
        </div>

        <div class="bg-white dark:bg-[#111C33] rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($announcements as $ann)
                    <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                        <div class="flex items-start gap-4">
                            @if($ann->cover_image_path)
                                <img src="{{ $ann->coverImageUrl() }}" alt="" class="w-16 h-16 rounded-2xl object-cover shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                    NEWS
                                </div>
                            @endif

                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $ann->category }}
                                    </span>
                                    @if($ann->publication_status === 'published')
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Published
                                        </span>
                                    @elseif($ann->publication_status === 'draft')
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            Draft
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600">
                                            Archived
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-base font-extrabold text-[#12345B] dark:text-white">
                                    {{ $ann->title }}
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mt-1 max-w-xl">
                                    {{ $ann->description }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end md:self-auto shrink-0">
                            <form method="POST" action="{{ route('admin.announcements.toggle-publish', $ann->id) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                    {{ $ann->publication_status === 'published' ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.announcements.edit', $ann->id) }}" class="px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-xs font-bold hover:bg-blue-100">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" onsubmit="return confirm('Permanently delete this announcement?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-xl text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-bold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-xs text-slate-400">
                        No announcements posted yet. Click "+ New Announcement" to post an official community bulletin.
                    </div>
                @endforelse
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
