<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'title',
        'message',
        'type',
        'related_record_type',
        'related_record_id',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function ($notification) {
            if ($notification->recipient_id) {
                \App\Services\PerformanceCache::clearResident($notification->recipient_id);
            }
        });

        static::deleted(function ($notification) {
            if ($notification->recipient_id) {
                \App\Services\PerformanceCache::clearResident($notification->recipient_id);
            }
        });
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}
