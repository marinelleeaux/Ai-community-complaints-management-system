<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'uploaded_by',
        'storage_path',
        'original_filename',
        'content_type',
        'file_size',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        if (str_starts_with($this->storage_path, 'http')) {
            return $this->storage_path;
        }

        $supabaseUrl = config('supabase.url');
        if (str_starts_with($this->storage_path, 'complaint-attachments/') || str_starts_with($this->storage_path, 'avatars/') || str_starts_with($this->storage_path, 'announcements/')) {
            if ($supabaseUrl && ! str_contains($supabaseUrl, 'your-project')) {
                return rtrim($supabaseUrl, '/') . '/storage/v1/object/public/' . ltrim($this->storage_path, '/');
            }
        }

        if (file_exists(public_path('storage/' . $this->storage_path)) || file_exists(storage_path('app/public/' . $this->storage_path))) {
            return asset('storage/' . $this->storage_path);
        }

        if ($supabaseUrl && ! str_contains($supabaseUrl, 'your-project')) {
            return rtrim($supabaseUrl, '/') . '/storage/v1/object/public/' . ltrim($this->storage_path, '/');
        }

        return asset('images/announcement-placeholder.svg');
    }

    public function isImage(): bool
    {
        if ($this->content_type && str_starts_with($this->content_type, 'image/')) {
            return true;
        }
        $ext = strtolower(pathinfo($this->original_filename, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'heic', 'heif', 'avif']);
    }
}
