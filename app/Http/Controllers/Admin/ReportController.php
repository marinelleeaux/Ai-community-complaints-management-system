<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Purok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['resident', 'category', 'purok', 'assignedOfficial']);

        // Date range filters
        if ($request->filled('start_date')) {
            $query->whereDate('incident_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('incident_date', '<=', $request->end_date);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('purok_id')) {
            $query->where('purok_id', $request->purok_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('final_priority', $request->priority);
        }

        $totalFiltered = (clone $query)->count();
        $resolvedFiltered = (clone $query)->where('status', 'resolved')->count();
        $inProgressFiltered = (clone $query)->whereIn('status', ['in_progress', 'under_review'])->count();
        $resolutionRate = $totalFiltered > 0 ? round(($resolvedFiltered / $totalFiltered) * 100, 1) : 0;

        $complaints = $query->latest('incident_date')->paginate(15)->withQueryString();
        $categories = ComplaintCategory::where('is_active', true)->get();
        $puroks = Purok::where('is_active', true)->get();

        return view('admin.reports.index', compact(
            'complaints',
            'categories',
            'puroks',
            'totalFiltered',
            'resolvedFiltered',
            'inProgressFiltered',
            'resolutionRate'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        AuditLog::log(Auth::id(), 'exported_complaints_csv', 'complaints', null, [
            'filters' => $request->only(['start_date', 'end_date', 'status', 'category_id', 'purok_id']),
        ]);

        $query = Complaint::with(['resident', 'category', 'purok', 'assignedOfficial']);

        if ($request->filled('start_date')) {
            $query->whereDate('incident_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('incident_date', '<=', $request->end_date);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('purok_id')) {
            $query->where('purok_id', $request->purok_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->orderBy('incident_date', 'desc')->get();
        $filename = 'barangay_north_poblacion_complaints_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($complaints) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Reference Number',
                'Title',
                'Category',
                'Purok',
                'Location',
                'Incident Date',
                'Status',
                'Priority',
                'Resident Name',
                'Assigned Official',
                'Date Submitted',
                'Date Resolved',
                'Public Remarks',
            ]);

            foreach ($complaints as $c) {
                fputcsv($handle, [
                    $c->reference_number,
                    $c->title,
                    $c->category?->name ?? 'Uncategorized',
                    $c->purok?->name ?? 'N/A',
                    $c->incident_location,
                    $c->incident_date->format('Y-m-d'),
                    ucfirst(str_replace('_', ' ', $c->status)),
                    ucfirst($c->final_priority),
                    $c->resident?->name ?? 'Anonymous',
                    $c->assignedOfficial?->name ?? 'Unassigned',
                    $c->created_at->format('Y-m-d H:i'),
                    $c->resolved_at ? $c->resolved_at->format('Y-m-d H:i') : 'N/A',
                    $c->resident_visible_remarks ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
