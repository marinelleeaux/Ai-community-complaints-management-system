<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::published()->with('author');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $announcements = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = Announcement::published()->distinct()->pluck('category');

        return view('resident.announcements.index', compact('announcements', 'categories'));
    }

    public function show(int $id)
    {
        $announcement = Announcement::published()->findOrFail($id);
        return view('resident.announcements.show', compact('announcement'));
    }
}
