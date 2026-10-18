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

        if (empty($request->all())) {
            $reportData = \Illuminate\Support\Facades\Cache::remember('admin_reports_default_data', \App\Services\PerformanceCache::FEEDS_TTL, function () use ($query) {
                $stats = (clone $query)->selectRaw("
                    count(*) as total,
                    count(case when status = 'resolved' then 1 end) as resolved,
                    count(case when status in ('in_progress', 'under_review') then 1 end) as in_progress
                ")->first();

                $totalFiltered = (int) ($stats->total ?? 0);
                $resolvedFiltered = (int) ($stats->resolved ?? 0);
                $inProgressFiltered = (int) ($stats->in_progress ?? 0);
                $resolutionRate = $totalFiltered > 0 ? round(($resolvedFiltered / $totalFiltered) * 100, 1) : 0;
                $complaints = $query->latest('incident_date')->paginate(15);

                return compact('totalFiltered', 'resolvedFiltered', 'inProgressFiltered', 'resolutionRate', 'complaints');
            });
            extract($reportData);
        } else {
            $stats = (clone $query)->selectRaw("
                count(*) as total,
                count(case when status = 'resolved' then 1 end) as resolved,
                count(case when status in ('in_progress', 'under_review') then 1 end) as in_progress
            ")->first();

            $totalFiltered = (int) ($stats->total ?? 0);
            $resolvedFiltered = (int) ($stats->resolved ?? 0);
            $inProgressFiltered = (int) ($stats->in_progress ?? 0);
            $resolutionRate = $totalFiltered > 0 ? round(($resolvedFiltered / $totalFiltered) * 100, 1) : 0;
            $complaints = $query->latest('incident_date')->paginate(15)->withQueryString();
        }

        $categories = ComplaintCategory::getActiveCached();
        $puroks = Purok::getActiveCached();

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
        $filename = 'Barangay_North_Poblacion_Complaints_Report_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($complaints) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Case Reference No.',
                'Case Title',
                'Category / Classification',
                'Purok Jurisdiction',
                'Incident Landmark / Location',
                'Incident Date',
                'Incident Time',
                'Date Submitted',
                'Time Submitted',
                'Current Status',
                'Priority Level',
                'Complainant Name',
                'Complainant Contact No.',
                'Complainant Email',
                'Case Description / Narrative',
                'AI English Summary',
                'Investigating Official Assigned',
                'Date Resolved',
                'Barangay Official Remarks & Action Taken',
            ]);

            foreach ($complaints as $c) {
                $incidentTimeFormatted = 'N/A';
                if ($c->incident_time) {
                    try {
                        $incidentTimeFormatted = \Carbon\Carbon::parse($c->incident_time)->format('h:i A');
                    } catch (\Exception) {
                        $incidentTimeFormatted = (string) $c->incident_time;
                    }
                }

                fputcsv($handle, [
                    $c->reference_number,
                    $c->title,
                    $c->category?->name ?? 'Uncategorized',
                    $c->purok?->name ?? 'N/A',
                    $c->incident_location,
                    $c->incident_date ? $c->incident_date->format('Y-m-d') : 'N/A',
                    $incidentTimeFormatted,
                    $c->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('Y-m-d'),
                    $c->created_at->timezone(config('app.timezone', 'Asia/Manila'))->format('h:i:s A'),
                    ucwords(str_replace('_', ' ', $c->status)),
                    strtoupper($c->final_priority),
                    $c->resident?->name ?? 'Anonymous Citizen',
                    $c->resident?->phone ?? 'N/A',
                    $c->resident?->email ?? 'N/A',
                    preg_replace('/\s+/', ' ', trim((string) $c->original_description)),
                    preg_replace('/\s+/', ' ', trim((string) ($c->english_summary ?? ''))),
                    $c->assignedOfficial?->name ?? 'Unassigned / Secretariat',
                    $c->resolved_at ? $c->resolved_at->timezone(config('app.timezone', 'Asia/Manila'))->format('Y-m-d h:i A') : 'Pending Resolution',
                    preg_replace('/\s+/', ' ', trim((string) ($c->resident_visible_remarks ?? 'None'))),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
