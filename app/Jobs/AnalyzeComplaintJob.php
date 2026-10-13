<?php

namespace App\Jobs;

use App\Models\Complaint;
use App\Models\Notification;
use App\Models\User;
use App\Services\AI\ComplaintAnalysisService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeComplaintJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(public int $complaintId)
    {
    }

    public function handle(ComplaintAnalysisService $aiService): void
    {
        $complaint = Complaint::find($this->complaintId);
        if (! $complaint) {
            return;
        }

        try {
            $analysis = $aiService->analyze($complaint);

            $complaint->update([
                'original_language' => $analysis['detected_language'] ?? $complaint->original_language,
                'english_translation' => $analysis['english_translation'] ?? $complaint->english_translation,
                'english_summary' => $analysis['english_summary'] ?? $complaint->english_summary,
                'ai_suggested_category' => $analysis['suggested_category'] ?? null,
                'ai_suggested_priority' => $analysis['suggested_priority'] ?? 'medium',
                'final_priority' => in_array($analysis['suggested_priority'] ?? '', ['high', 'urgent']) 
                    ? $analysis['suggested_priority'] 
                    : $complaint->final_priority,
                'priority_explanation' => $analysis['priority_explanation'] ?? null,
                'ai_analysis_status' => 'completed',
            ]);

            // Notify admins if high or urgent priority
            if (in_array($complaint->final_priority, ['high', 'urgent'])) {
                $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'recipient_id' => $admin->id,
                        'title' => strtoupper($complaint->final_priority) . " Priority Complaint: {$complaint->reference_number}",
                        'message' => "AI flagged complaint \"{$complaint->title}\" in {$complaint->incident_location} as {$complaint->final_priority} priority.",
                        'type' => 'urgent_alert',
                        'related_record_type' => 'complaint',
                        'related_record_id' => (string) $complaint->id,
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error("AnalyzeComplaintJob failed for complaint #{$this->complaintId}: " . $e->getMessage());
            $complaint->update(['ai_analysis_status' => 'failed']);
        }
    }
}
