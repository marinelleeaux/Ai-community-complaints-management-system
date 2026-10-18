<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintInternalNote;
use App\Models\ComplaintStatusHistory;
use App\Models\Notification;
use App\Models\Purok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['resident', 'category', 'purok', 'assignedOfficial']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('reference_number', 'LIKE', "%{$search}%")
                    ->orWhere('original_description', 'LIKE', "%{$search}%")
                    ->orWhere('incident_location', 'LIKE', "%{$search}%")
                    ->orWhereHas('resident', fn ($rq) => $rq->where('name', 'LIKE', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('final_priority', $request->priority);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('purok_id')) {
            $query->where('purok_id', $request->purok_id);
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();
        $categories = ComplaintCategory::getActiveCached();
        $puroks = Purok::getActiveCached();

        return view('admin.complaints.index', compact('complaints', 'categories', 'puroks'));
    }

    public function show(int $id)
    {
        $complaint = Complaint::with([
            'resident.purok',
            'category',
            'purok',
            'attachments.uploader',
            'statusHistory.changer',
            'internalNotes.admin',
            'assignedOfficial',
        ])->findOrFail($id);

        $categories = ComplaintCategory::where('is_active', true)->get();
        $officials = User::whereIn('role', ['admin', 'official', 'super_admin'])->get();

        return view('admin.complaints.show', compact('complaint', 'categories', 'officials'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:submitted,under_review,acknowledged,in_progress,referred_to_responsible_office,resolved,closed,rejected'],
            'public_remarks' => ['nullable', 'string', 'max:1000'],
            'internal_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::user();
        $complaint = Complaint::findOrFail($id);
        $prevStatus = $complaint->status;

        DB::transaction(function () use ($complaint, $validated, $admin, $prevStatus) {
            $resolvedAt = ($validated['status'] === 'resolved' && ! $complaint->resolved_at) ? now() : $complaint->resolved_at;

            $complaint->update([
                'status' => $validated['status'],
                'resident_visible_remarks' => $validated['public_remarks'] ?? $complaint->resident_visible_remarks,
                'resolved_at' => $resolvedAt,
            ]);

            // Save status history
            ComplaintStatusHistory::create([
                'complaint_id' => $complaint->id,
                'previous_status' => $prevStatus,
                'new_status' => $validated['status'],
                'changed_by' => $admin->id,
                'public_remarks' => $validated['public_remarks'] ?? null,
                'internal_notes' => $validated['internal_notes'] ?? null,
            ]);

            // Save internal note if provided
            if (! empty($validated['internal_notes'])) {
                ComplaintInternalNote::create([
                    'complaint_id' => $complaint->id,
                    'admin_id' => $admin->id,
                    'note_content' => $validated['internal_notes'],
                ]);
            }

            // Notify resident of status update
            $statusBadge = $complaint->statusBadge();
            Notification::create([
                'recipient_id' => $complaint->resident_id,
                'title' => "Complaint Status Updated: {$complaint->reference_number}",
                'message' => "Your complaint \"{$complaint->title}\" is now {$statusBadge['label']}." . ($validated['public_remarks'] ? " Remarks: {$validated['public_remarks']}" : ""),
                'type' => 'complaint_status_updated',
                'related_record_type' => 'complaint',
                'related_record_id' => (string) $complaint->id,
            ]);

            AuditLog::log($admin->id, 'updated_complaint_status', 'complaints', (string) $complaint->id, [
                'previous_status' => $prevStatus,
                'new_status' => $validated['status'],
                'reference_number' => $complaint->reference_number,
            ]);
        });

        if ($request->expectsJson() || $request->wantsJson()) {
            $complaint->refresh();
            return response()->json([
                'success' => true,
                'message' => "Complaint {$complaint->reference_number} is now {$complaint->statusBadge()['label']}.",
                'status' => $complaint->status,
                'status_badge' => $complaint->statusBadge(),
            ]);
        }

        return back()->with('success', "Complaint status successfully updated to {$complaint->statusBadge()['label']}.");
    }

    public function overrideAi(Request $request, int $id)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:complaint_categories,id'],
            'final_priority' => ['required', 'in:low,medium,high,urgent'],
            'priority_explanation' => ['nullable', 'string', 'max:500'],
        ]);

        $admin = Auth::user();
        $complaint = Complaint::findOrFail($id);

        $complaint->update([
            'category_id' => $validated['category_id'],
            'final_priority' => $validated['final_priority'],
            'priority_explanation' => $validated['priority_explanation'] ?? $complaint->priority_explanation,
        ]);

        AuditLog::log($admin->id, 'override_ai_complaint_classification', 'complaints', (string) $complaint->id, [
            'category_id' => $validated['category_id'],
            'final_priority' => $validated['final_priority'],
        ]);

        return back()->with('success', 'AI classification recommendations successfully overridden.');
    }

    public function assign(Request $request, int $id)
    {
        $validated = $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        $admin = Auth::user();
        $complaint = Complaint::findOrFail($id);
        $assignee = User::findOrFail($validated['assigned_to']);

        $complaint->update(['assigned_to' => $assignee->id]);

        Notification::create([
            'recipient_id' => $assignee->id,
            'title' => "Complaint Assigned: {$complaint->reference_number}",
            'message' => "You have been assigned to handle complaint \"{$complaint->title}\".",
            'type' => 'complaint_assigned',
            'related_record_type' => 'complaint',
            'related_record_id' => (string) $complaint->id,
        ]);

        AuditLog::log($admin->id, 'assigned_complaint', 'complaints', (string) $complaint->id, [
            'assigned_to' => $assignee->name,
        ]);

        return back()->with('success', "Complaint assigned to {$assignee->name}.");
    }

    public function addInternalNote(Request $request, int $id)
    {
        $validated = $request->validate([
            'note_content' => ['required', 'string', 'max:2000'],
        ]);

        $admin = Auth::user();
        $complaint = Complaint::findOrFail($id);

        ComplaintInternalNote::create([
            'complaint_id' => $complaint->id,
            'admin_id' => $admin->id,
            'note_content' => $validated['note_content'],
        ]);

        AuditLog::log($admin->id, 'added_internal_note', 'complaints', (string) $complaint->id);

        return back()->with('success', 'Confidential internal note recorded.');
    }
}
