<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AuditLog;
use App\Services\Supabase\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function __construct(protected SupabaseStorageService $storageService)
    {
    }

    public function index(Request $request)
    {
        $query = Announcement::with('author');

        if ($request->filled('status')) {
            $query->where('publication_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $announcements = $query->latest()->paginate(10)->withQueryString();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'publication_status' => ['required', 'in:published,draft,archived'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'event_start' => ['nullable', 'date'],
            'event_end' => ['nullable', 'date', 'after_or_equal:event_start'],
            'event_location' => ['nullable', 'string', 'max:255'],
            'expiration_date' => ['nullable', 'date'],
        ]);

        $admin = Auth::user();
        $coverPath = null;

        if ($request->hasFile('cover_image')) {
            $upload = $this->storageService->upload('announcements', $request->file('cover_image'), 'covers');
            $coverPath = $upload['path'] ?? null;
        }

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'publication_status' => $validated['publication_status'],
            'cover_image_path' => $coverPath,
            'event_start' => $validated['event_start'] ?? null,
            'event_end' => $validated['event_end'] ?? null,
            'event_location' => $validated['event_location'] ?? null,
            'expiration_date' => $validated['expiration_date'] ?? null,
            'created_by' => $admin->id,
            'published_at' => $validated['publication_status'] === 'published' ? now() : null,
        ]);

        AuditLog::log($admin->id, 'created_announcement', 'announcements', (string) $announcement->id, [
            'title' => $announcement->title,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement published successfully.');
    }

    public function edit(int $id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, int $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'publication_status' => ['required', 'in:published,draft,archived'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'event_start' => ['nullable', 'date'],
            'event_end' => ['nullable', 'date', 'after_or_equal:event_start'],
            'event_location' => ['nullable', 'string', 'max:255'],
            'expiration_date' => ['nullable', 'date'],
        ]);

        $coverPath = $announcement->cover_image_path;
        if ($request->hasFile('cover_image')) {
            $upload = $this->storageService->upload('announcements', $request->file('cover_image'), 'covers');
            $coverPath = $upload['path'] ?? $coverPath;
        }

        $publishedAt = $announcement->published_at;
        if ($validated['publication_status'] === 'published' && ! $publishedAt) {
            $publishedAt = now();
        }

        $announcement->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'publication_status' => $validated['publication_status'],
            'cover_image_path' => $coverPath,
            'event_start' => $validated['event_start'] ?? null,
            'event_end' => $validated['event_end'] ?? null,
            'event_location' => $validated['event_location'] ?? null,
            'expiration_date' => $validated['expiration_date'] ?? null,
            'published_at' => $publishedAt,
        ]);

        AuditLog::log(Auth::id(), 'updated_announcement', 'announcements', (string) $announcement->id, [
            'title' => $announcement->title,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(int $id)
    {
        $announcement = Announcement::findOrFail($id);
        $title = $announcement->title;
        $announcement->delete();

        AuditLog::log(Auth::id(), 'deleted_announcement', 'announcements', (string) $id, ['title' => $title]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement removed.');
    }

    public function togglePublish(int $id)
    {
        $announcement = Announcement::findOrFail($id);
        $newStatus = $announcement->publication_status === 'published' ? 'draft' : 'published';
        $announcement->update([
            'publication_status' => $newStatus,
            'published_at' => $newStatus === 'published' ? ($announcement->published_at ?? now()) : $announcement->published_at,
        ]);

        AuditLog::log(Auth::id(), 'toggled_announcement_publish', 'announcements', (string) $id, ['new_status' => $newStatus]);

        return back()->with('success', "Announcement status updated to {$newStatus}.");
    }
}
