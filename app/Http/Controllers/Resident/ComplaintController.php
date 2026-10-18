<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Jobs\AnalyzeComplaintJob;
use App\Models\AuditLog;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\ComplaintStatusHistory;
use App\Models\Notification;
use App\Models\Purok;
use App\Models\User;
use App\Services\AI\ComplaintAnalysisService;
use App\Services\Supabase\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ComplaintController extends Controller
{
    public function __construct(
        protected SupabaseStorageService $storageService,
        protected ComplaintAnalysisService $aiService
    ) {
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Complaint::where('resident_id', $user->id)
            ->with(['category', 'purok']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('reference_number', 'LIKE', "%{$search}%")
                    ->orWhere('original_description', 'LIKE', "%{$search}%")
                    ->orWhere('incident_location', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();
        $categories = ComplaintCategory::getActiveCached();

        return view('resident.complaints.index', compact('complaints', 'categories'));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = ComplaintCategory::getActiveCached();
        $puroks = Purok::getActiveCached();

        return view('resident.complaints.create', compact('categories', 'puroks', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:complaint_categories,id'],
            'original_description' => ['required', 'string', 'min:10'],
            'incident_location' => ['required', 'string', 'max:255'],
            'purok_id' => ['required', 'exists:puroks,id'],
            'incident_date' => ['required', 'date', 'before_or_equal:today'],
            'incident_time' => ['nullable'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp,svg,bmp,pdf,doc,docx', 'max:10240'],
        ]);

        $user = Auth::user();

        // 1. Run AI analysis to automatically determine category, priority, language, and translation
        $tempComplaint = new Complaint([
            'title' => $validated['title'],
            'original_description' => $validated['original_description'],
            'incident_location' => $validated['incident_location'],
            'incident_date' => $validated['incident_date'],
        ]);
        $analysis = $this->aiService->analyze($tempComplaint);

        // Assign category exclusively via AI suggestion
        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($analysis['suggested_category'])) {
            $matched = ComplaintCategory::where('name', $analysis['suggested_category'])->first();
            if (!$matched) {
                $firstWord = explode(' ', $analysis['suggested_category'])[0];
                $matched = ComplaintCategory::where('name', 'LIKE', "%{$firstWord}%")->first();
            }
            $categoryId = $matched?->id;
        }

        if (!$categoryId) {
            $categoryId = ComplaintCategory::where('name', 'General Barangay Inquiry')->value('id') ?? 1;
        }

        $complaint = DB::transaction(function () use ($validated, $user, $request, $analysis, $categoryId) {
            $refNumber = Complaint::generateReferenceNumber();

            $newComplaint = Complaint::create([
                'reference_number' => $refNumber,
                'resident_id' => $user->id,
                'title' => $validated['title'],
                'original_description' => $validated['original_description'],
                'original_language' => $analysis['detected_language'] ?? 'ceb',
                'english_translation' => $analysis['english_translation'] ?? $validated['original_description'],
                'english_summary' => $analysis['english_summary'] ?? null,
                'category_id' => $categoryId,
                'incident_location' => $validated['incident_location'],
                'purok_id' => $validated['purok_id'],
                'incident_date' => $validated['incident_date'],
                'incident_time' => $validated['incident_time'] ?? null,
                'ai_suggested_category' => $analysis['suggested_category'] ?? null,
                'ai_suggested_priority' => $analysis['suggested_priority'] ?? 'medium',
                'final_priority' => $analysis['suggested_priority'] ?? 'medium',
                'priority_explanation' => $analysis['priority_explanation'] ?? null,
                'status' => 'submitted',
                'ai_analysis_status' => 'completed',
            ]);

            // Save status history record
            ComplaintStatusHistory::create([
                'complaint_id' => $newComplaint->id,
                'previous_status' => null,
                'new_status' => 'submitted',
                'changed_by' => $user->id,
                'public_remarks' => 'Complaint successfully submitted by resident.',
            ]);

            // Process file uploads if present
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $upload = $this->storageService->upload('complaint-attachments', $file, 'complaints/' . $newComplaint->id);
                    ComplaintAttachment::create([
                        'complaint_id' => $newComplaint->id,
                        'uploaded_by' => $user->id,
                        'storage_path' => $upload['path'],
                        'original_filename' => $upload['filename'],
                        'content_type' => $upload['mime'],
                        'file_size' => $upload['size'],
                    ]);
                }
            }

            // Notify all admins of newly submitted complaint
            $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'recipient_id' => $admin->id,
                    'title' => "New Complaint: {$newComplaint->reference_number}",
                    'message' => "Resident {$user->name} submitted a new complaint: \"{$newComplaint->title}\".",
                    'type' => 'new_complaint',
                    'related_record_type' => 'complaint',
                    'related_record_id' => (string) $newComplaint->id,
                ]);
            }

            AuditLog::log($user->id, 'submitted_complaint', 'complaints', (string) $newComplaint->id, [
                'reference_number' => $newComplaint->reference_number,
                'title' => $newComplaint->title,
            ]);

            return $newComplaint;
        });

        // Bust all caches immediately for both roles so live graphics update
        \App\Services\PerformanceCache::clearAdminDashboard();
        \App\Services\PerformanceCache::clearResident($user->id);

        // Queue AI analysis for external enrichment if configured
        try {
            AnalyzeComplaintJob::dispatch($complaint->id);
        } catch (\Exception $e) {
            // Ensure complaint remains saved even if AI queue dispatch fails
        }

        return redirect()->route('resident.complaints.show', $complaint->id)
            ->with('success', "Complaint submitted successfully! Your tracking reference number is {$complaint->reference_number}.");
    }

    public function show(int|string $id)
    {
        $complaint = Complaint::with(['category', 'purok', 'attachments', 'statusHistory.changer'])
            ->findOrFail($id);

        Gate::authorize('view', $complaint);

        return view('resident.complaints.show', compact('complaint'));
    }

    public function edit(int|string $id)
    {
        $complaint = Complaint::with(['category', 'purok', 'attachments'])
            ->where('id', $id)
            ->where('resident_id', Auth::id())
            ->firstOrFail();

        Gate::authorize('update', $complaint);

        $categories = ComplaintCategory::getActiveCached();
        $puroks = Purok::getActiveCached();

        return view('resident.complaints.edit', compact('complaint', 'categories', 'puroks'));
    }

    public function update(Request $request, int|string $id)
    {
        $complaint = Complaint::with(['category', 'purok'])
            ->where('id', $id)
            ->where('resident_id', Auth::id())
            ->firstOrFail();

        Gate::authorize('update', $complaint);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:complaint_categories,id',
            'purok_id' => 'required|exists:puroks,id',
            'incident_date' => 'nullable|date',
            'incident_location' => 'nullable|string|max:255',
            'original_description' => 'required|string|min:10',
        ]);

        $beforeData = [
            'title' => $complaint->title,
            'category_name' => $complaint->category?->name ?? 'General Inquiry',
            'purok_name' => $complaint->purok?->name ?? 'Barangay Wide',
            'incident_date' => $complaint->incident_date ? $complaint->incident_date->format('M d, Y') : 'Not specified',
            'incident_location' => $complaint->incident_location ?? 'Not specified',
            'original_description' => $complaint->original_description,
        ];

        // AI re-categorization and analysis
        $tempComplaint = new Complaint([
            'title' => $validated['title'],
            'original_description' => $validated['original_description'],
            'incident_location' => $validated['incident_location'] ?? $complaint->incident_location,
            'incident_date' => $validated['incident_date'] ?? $complaint->incident_date,
        ]);
        $analysis = $this->aiService->analyze($tempComplaint);

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($analysis['suggested_category'])) {
            $matched = ComplaintCategory::where('name', $analysis['suggested_category'])->first();
            if (!$matched) {
                $firstWord = explode(' ', $analysis['suggested_category'])[0];
                $matched = ComplaintCategory::where('name', 'LIKE', "%{$firstWord}%")->first();
            }
            $categoryId = $matched?->id;
        }
        if (!$categoryId) {
            $categoryId = $complaint->category_id;
        }

        $newCategory = ComplaintCategory::find($categoryId);
        $newPurok = Purok::find($validated['purok_id']);
        $formattedNewDate = !empty($validated['incident_date']) ? \Carbon\Carbon::parse($validated['incident_date'])->format('M d, Y') : 'Not specified';

        $afterData = [
            'title' => $validated['title'],
            'category_name' => $newCategory?->name ?? 'General Inquiry',
            'purok_name' => $newPurok?->name ?? 'Barangay Wide',
            'incident_date' => $formattedNewDate,
            'incident_location' => $validated['incident_location'] ?? 'Not specified',
            'original_description' => $validated['original_description'],
        ];

        $history = $complaint->edit_history ?? [];
        $history[] = [
            'edited_at' => now()->toIso8601String(),
            'edited_at_formatted' => now()->format('M d, Y • h:i A'),
            'editor_name' => Auth::user()->name,
            'before' => $beforeData,
            'after' => $afterData,
        ];

        $complaint->update([
            'title' => $validated['title'],
            'category_id' => $categoryId,
            'purok_id' => $validated['purok_id'],
            'incident_date' => $validated['incident_date'],
            'incident_location' => $validated['incident_location'],
            'original_description' => $validated['original_description'],
            'original_language' => $analysis['detected_language'] ?? $complaint->original_language,
            'english_translation' => $analysis['english_translation'] ?? $validated['original_description'],
            'english_summary' => $analysis['english_summary'] ?? $complaint->english_summary,
            'ai_suggested_category' => $analysis['suggested_category'] ?? $complaint->ai_suggested_category,
            'ai_suggested_priority' => $analysis['suggested_priority'] ?? $complaint->ai_suggested_priority,
            'final_priority' => $analysis['suggested_priority'] ?? $complaint->final_priority,
            'priority_explanation' => $analysis['priority_explanation'] ?? $complaint->priority_explanation,
            'edit_history' => $history,
        ]);

        \App\Services\PerformanceCache::clearAdminDashboard();
        \App\Services\PerformanceCache::clearResident(Auth::id());

        AuditLog::log(Auth::id(), 'updated_complaint', 'complaints', (string) $complaint->id, [
            'reference_number' => $complaint->reference_number,
            'title' => $complaint->title,
            'before' => $beforeData,
            'after' => $afterData,
        ]);

        try {
            AnalyzeComplaintJob::dispatch($complaint->id);
        } catch (\Exception $e) {
            // Keep going even if queue error
        }

        return redirect()->route('resident.complaints.show', $complaint->id)
            ->with('success', "Complaint {$complaint->reference_number} updated successfully.");
    }

    public function destroy(int|string $id)
    {
        $complaint = Complaint::where('id', $id)
            ->where('resident_id', Auth::id())
            ->firstOrFail();

        Gate::authorize('delete', $complaint);

        $ref = $complaint->reference_number;
        $residentId = $complaint->resident_id;

        // Clean up attachments
        foreach ($complaint->attachments as $att) {
            try {
                $this->storageService->deleteFile($att->file_path);
            } catch (\Exception $e) {
                // Ignore storage delete errors
            }
            $att->delete();
        }

        $complaint->delete();

        // Clear dashboard and feed caches immediately so live graphics follow
        \App\Services\PerformanceCache::clearAdminDashboard();
        \App\Services\PerformanceCache::clearResident($residentId);

        AuditLog::log(Auth::id(), 'deleted_complaint', 'complaints', (string) $id, [
            'reference_number' => $ref,
        ]);

        return redirect()->route('resident.complaints.index')
            ->with('success', "Complaint {$ref} deleted successfully.");
    }

    public function history()
    {
        $user = Auth::user();
        $history = ComplaintStatusHistory::whereHas('complaint', function ($q) use ($user) {
            $q->where('resident_id', $user->id);
        })
            ->with(['complaint', 'changer'])
            ->latest()
            ->paginate(15);

        return view('resident.complaints.history', compact('history'));
    }
}
