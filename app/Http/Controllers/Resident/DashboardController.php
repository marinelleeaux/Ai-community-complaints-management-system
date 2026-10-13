<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Authentic resident metrics directly from Supabase/PostgreSQL
        $totalComplaints = Complaint::where('resident_id', $user->id)->count();
        $inProgressCount = Complaint::where('resident_id', $user->id)
            ->whereIn('status', ['in_progress', 'under_review', 'acknowledged'])
            ->count();
        $resolvedCount = Complaint::where('resident_id', $user->id)
            ->where('status', 'resolved')
            ->count();
        $rejectedCount = Complaint::where('resident_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        // Recent complaints list matching reference image
        $recentComplaints = Complaint::where('resident_id', $user->id)
            ->with(['category', 'purok'])
            ->orderBy('incident_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $latestAnnouncements = Announcement::published()
            ->latest()
            ->take(3)
            ->get();

        $unreadCount = $user->unreadNotificationsCount();

        return view('resident.dashboard', compact(
            'user',
            'totalComplaints',
            'inProgressCount',
            'resolvedCount',
            'rejectedCount',
            'recentComplaints',
            'latestAnnouncements',
            'unreadCount'
        ));
    }
}
