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
        $categories = ComplaintCategory::where('is_active', true)->get();

        return view('resident.complaints.index', compact('complaints', 'categories'));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = ComplaintCategory::where('is_active', true)->get();
        $puroks = Purok::where('is_active', true)->get();

        return view('resident.complaints.create', compact('categories', 'puroks', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:complaint_categories,id'],
            'original_description' => ['required', 'string', 'min:10'],
            'incident_location' => ['required', 'string', 'max:255'],
            'purok_id' => ['required', 'exists:puroks,id'],
            'incident_date' => ['required', 'date', 'before_or_equal:today'],
            'incident_time' => ['nullable', 'date_format:H:i'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf,doc,docx', 'max:5120'],
        ]);

        $user = Auth::user();

        $complaint = DB::transaction(function () use ($validated, $user, $request) {
            $refNumber = Complaint::generateReferenceNumber();

            $newComplaint = Complaint::create([
                'reference_number' => $refNumber,
                'resident_id' => $user->id,
                'title' => $validated['title'],
                'original_description' => $validated['original_description'],
                'original_language' => 'ceb', // Will be detected/refined by AI
                'category_id' => $validated['category_id'],
                'incident_location' => $validated['incident_location'],
                'purok_id' => $validated['purok_id'],
                'incident_date' => $validated['incident_date'],
                'incident_time' => $validated['incident_time'] ?? null,
                'final_priority' => 'medium',
                'status' => 'submitted',
                'ai_analysis_status' => 'pending',
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

        // Run or queue AI analysis
        try {
            // Run queued analysis job or immediate sync if queue is sync
            AnalyzeComplaintJob::dispatch($complaint->id);
        } catch (\Exception $e) {
            // Ensure complaint remains saved even if AI queue dispatch fails
        }

        return redirect()->route('resident.complaints.show', $complaint->id)
            ->with('success', "Complaint submitted successfully! Your tracking reference number is {$complaint->reference_number}.");
    }

    public function show(int $id)
    {
        $complaint = Complaint::with(['category', 'purok', 'attachments', 'statusHistory.changer'])
            ->findOrFail($id);

        Gate::authorize('view', $complaint);

        return view('resident.complaints.show', compact('complaint'));
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
