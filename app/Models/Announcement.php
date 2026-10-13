<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'cover_image_path',
        'publication_status',
        'event_start',
        'event_end',
        'event_location',
        'created_by',
        'published_at',
        'expiration_date',
    ];

    protected function casts(): array
    {
        return [
            'event_start' => 'datetime',
            'event_end' => 'datetime',
            'published_at' => 'datetime',
            'expiration_date' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('publication_status', 'published')
            ->where(function ($q) {
                $q->whereNull('expiration_date')
                    ->orWhere('expiration_date', '>=', now());
            });
    }

    public function coverImageUrl(): ?string
    {
        if ($this->cover_image_path) {
            if (str_starts_with($this->cover_image_path, 'http')) {
                return $this->cover_image_path;
            }
            return asset('storage/' . $this->cover_image_path);
        }
        return null;
    }
}
