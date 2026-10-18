<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'resident_id',
        'title',
        'original_description',
        'original_language',
        'english_translation',
        'english_summary',
        'category_id',
        'incident_location',
        'purok_id',
        'incident_date',
        'incident_time',
        'ai_suggested_category',
        'ai_suggested_priority',
        'final_priority',
        'priority_explanation',
        'status',
        'assigned_to',
        'resident_visible_remarks',
        'ai_analysis_status',
        'resolved_at',
        'edit_history',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'resolved_at' => 'datetime',
            'edit_history' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function ($complaint) {
            \App\Services\PerformanceCache::clearAdminDashboard();
            if ($complaint->resident_id) {
                \App\Services\PerformanceCache::clearResident($complaint->resident_id);
            }
        });

        static::deleted(function ($complaint) {
            \App\Services\PerformanceCache::clearAdminDashboard();
            if ($complaint->resident_id) {
                \App\Services\PerformanceCache::clearResident($complaint->resident_id);
            }
        });
    }

    /**
     * Generate unique reference number in format: NPC-YYYY-XXXX
     */
    public static function generateReferenceNumber(): string
    {
        $year = date('Y');
        $prefix = "NPC-{$year}-";

        return DB::transaction(function () use ($prefix, $year) {
            $latest = self::where('reference_number', 'LIKE', "{$prefix}%")
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $nextSequence = 1;
            if ($latest && preg_match('/NPC-\d{4}-(\d+)/', $latest->reference_number, $matches)) {
                $nextSequence = ((int) $matches[1]) + 1;
            }

            return sprintf('NPC-%s-%04d', $year, $nextSequence);
        });
    }

    // Relationships
    public function resident(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function purok(): BelongsTo
    {
        return $this->belongsTo(Purok::class, 'purok_id');
    }

    public function assignedOfficial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(ComplaintStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function internalNotes(): HasMany
    {
        return $this->hasMany(ComplaintInternalNote::class)->orderBy('created_at', 'desc');
    }

    // Status UI helpers
    public function statusBadge(): array
    {
        return match ($this->status) {
            'submitted' => [
                'label' => 'Pending',
                'bg' => 'bg-blue-50 dark:bg-blue-950/60',
                'text' => 'text-blue-700 dark:text-blue-300',
                'border' => 'border-blue-200 dark:border-blue-800',
                'icon' => 'hourglass',
            ],
            'under_review' => [
                'label' => 'Under Review',
                'bg' => 'bg-amber-50 dark:bg-amber-950/60',
                'text' => 'text-amber-700 dark:text-amber-300',
                'border' => 'border-amber-200 dark:border-amber-800',
                'icon' => 'eye',
            ],
            'acknowledged' => [
                'label' => 'Acknowledged',
                'bg' => 'bg-indigo-50 dark:bg-indigo-950/60',
                'text' => 'text-indigo-700 dark:text-indigo-300',
                'border' => 'border-indigo-200 dark:border-indigo-800',
                'icon' => 'check',
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'bg' => 'bg-orange-50 dark:bg-orange-950/60',
                'text' => 'text-orange-700 dark:text-orange-300',
                'border' => 'border-orange-200 dark:border-orange-800',
                'icon' => 'clock',
            ],
            'referred_to_responsible_office' => [
                'label' => 'Referred',
                'bg' => 'bg-purple-50 dark:bg-purple-950/60',
                'text' => 'text-purple-700 dark:text-purple-300',
                'border' => 'border-purple-200 dark:border-purple-800',
                'icon' => 'send',
            ],
            'resolved' => [
                'label' => 'Resolved',
                'bg' => 'bg-emerald-50 dark:bg-emerald-950/60',
                'text' => 'text-emerald-700 dark:text-emerald-300',
                'border' => 'border-emerald-200 dark:border-emerald-800',
                'icon' => 'check-circle',
            ],
            'closed' => [
                'label' => 'Closed',
                'bg' => 'bg-slate-100 dark:bg-slate-800',
                'text' => 'text-slate-700 dark:text-slate-300',
                'border' => 'border-slate-300 dark:border-slate-700',
                'icon' => 'archive',
            ],
            'rejected' => [
                'label' => 'Rejected',
                'bg' => 'bg-rose-50 dark:bg-rose-950/60',
                'text' => 'text-rose-700 dark:text-rose-300',
                'border' => 'border-rose-200 dark:border-rose-800',
                'icon' => 'x-circle',
            ],
            default => [
                'label' => ucfirst($this->status),
                'bg' => 'bg-gray-100 dark:bg-gray-800',
                'text' => 'text-gray-700 dark:text-gray-300',
                'border' => 'border-gray-300 dark:border-gray-700',
                'icon' => 'help-circle',
            ],
        };
    }

    public function priorityBadge(): array
    {
        return match ($this->final_priority) {
            'urgent' => [
                'label' => 'Urgent',
                'bg' => 'bg-red-100 dark:bg-red-950/80',
                'text' => 'text-red-700 dark:text-red-300',
                'border' => 'border-red-300 dark:border-red-800',
            ],
            'high' => [
                'label' => 'High',
                'bg' => 'bg-orange-100 dark:bg-orange-950/80',
                'text' => 'text-orange-700 dark:text-orange-300',
                'border' => 'border-orange-300 dark:border-orange-800',
            ],
            'medium' => [
                'label' => 'Medium',
                'bg' => 'bg-amber-100 dark:bg-amber-950/80',
                'text' => 'text-amber-700 dark:text-amber-300',
                'border' => 'border-amber-300 dark:border-amber-800',
            ],
            'low' => [
                'label' => 'Low',
                'bg' => 'bg-emerald-100 dark:bg-emerald-950/80',
                'text' => 'text-emerald-700 dark:text-emerald-300',
                'border' => 'border-emerald-300 dark:border-emerald-800',
            ],
            default => [
                'label' => ucfirst($this->final_priority),
                'bg' => 'bg-gray-100 dark:bg-gray-800',
                'text' => 'text-gray-700 dark:text-gray-300',
                'border' => 'border-gray-300 dark:border-gray-700',
            ],
        };
    }
}
