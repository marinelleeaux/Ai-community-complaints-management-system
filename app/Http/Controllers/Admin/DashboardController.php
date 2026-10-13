<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Purok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Resident KPI Statistics
        $totalResidents = User::where('role', 'resident')->count();
        $pendingApprovals = User::where('role', 'resident')->where('account_status', 'pending')->count();
        $approvedResidents = User::where('role', 'resident')->where('account_status', 'approved')->count();
        $rejectedResidents = User::where('role', 'resident')->where('account_status', 'rejected')->count();
        $inactiveResidents = User::where('account_status', 'inactive')->count();

        // 2. Complaint KPI Statistics
        $totalComplaints = Complaint::count();
        $newComplaints = Complaint::where('status', 'submitted')->count();
        $underReview = Complaint::where('status', 'under_review')->count();
        $inProgress = Complaint::where('status', 'in_progress')->count();
        $resolvedComplaints = Complaint::where('status', 'resolved')->count();
        $highPriorityComplaints = Complaint::where('final_priority', 'high')->count();
        $urgentComplaints = Complaint::where('final_priority', 'urgent')->count();
        $publishedAnnouncements = Announcement::where('publication_status', 'published')->count();

        // 3. Chart.js Datasets
        // A. Complaints by Category
        $categoriesData = ComplaintCategory::withCount('complaints')
            ->where('is_active', true)
            ->get()
            ->map(fn ($cat) => [
                'name' => $cat->name,
                'count' => $cat->complaints_count,
            ]);

        // B. Complaints by Status
        $statuses = ['submitted', 'under_review', 'in_progress', 'resolved', 'rejected'];
        $statusCounts = [];
        foreach ($statuses as $st) {
            $statusCounts[$st] = Complaint::where('status', $st)->count();
        }

        // C. Complaints by Priority
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $priorityCounts = [];
        foreach ($priorities as $pr) {
            $priorityCounts[$pr] = Complaint::where('final_priority', $pr)->count();
        }

        // D. Complaints by Purok
        $purokData = Purok::withCount('complaints')
            ->orderBy('name')
            ->get()
            ->map(fn ($p) => [
                'name' => $p->name,
                'count' => $p->complaints_count,
            ]);

        // 4. Queues & Feeds
        $recentComplaints = Complaint::with(['resident', 'category', 'purok'])
            ->latest()
            ->take(6)
            ->get();

        $pendingResidentsList = User::where('role', 'resident')
            ->where('account_status', 'pending')
            ->with('purok')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalResidents',
            'pendingApprovals',
            'approvedResidents',
            'rejectedResidents',
            'inactiveResidents',
            'totalComplaints',
            'newComplaints',
            'underReview',
            'inProgress',
            'resolvedComplaints',
            'highPriorityComplaints',
            'urgentComplaints',
            'publishedAnnouncements',
            'categoriesData',
            'statusCounts',
            'priorityCounts',
            'purokData',
            'recentComplaints',
            'pendingResidentsList'
        ));
    }
}
