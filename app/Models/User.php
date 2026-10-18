<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'purok_id',
        'role',
        'account_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'avatar_path',
        'theme_preference',
        'supabase_uid',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function ($user) {
            \App\Services\PerformanceCache::clearAdminDashboard();
            \App\Services\PerformanceCache::clearResident($user->id);
        });

        static::deleted(function ($user) {
            \App\Services\PerformanceCache::clearAdminDashboard();
            \App\Services\PerformanceCache::clearResident($user->id);
        });
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isOfficial(): bool
    {
        return in_array($this->role, ['admin', 'official', 'super_admin']);
    }

    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    // Status helpers
    public function isApproved(): bool
    {
        return $this->account_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->account_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->account_status === 'rejected';
    }

    public function isActive(): bool
    {
        return $this->account_status !== 'inactive';
    }

    // Avatar & display helpers
    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        $initials = '';
        if (count($words) >= 2) {
            $initials = mb_substr($words[0], 0, 1) . mb_substr($words[count($words) - 1], 0, 1);
        } elseif (count($words) === 1 && !empty($words[0])) {
            $initials = mb_substr($words[0], 0, 2);
        } else {
            $initials = 'NP';
        }
        return strtoupper($initials);
    }

    public function avatarUrl(): ?string
    {
        if ($this->avatar_path) {
            if (str_starts_with($this->avatar_path, 'http')) {
                return $this->avatar_path;
            }
            if (file_exists(public_path('storage/' . $this->avatar_path)) || file_exists(storage_path('app/public/' . $this->avatar_path))) {
                return asset('storage/' . $this->avatar_path);
            }
            $supabaseUrl = config('supabase.url');
            if ($supabaseUrl && ! str_contains($supabaseUrl, 'your-project')) {
                return rtrim($supabaseUrl, '/') . '/storage/v1/object/public/' . ltrim($this->avatar_path, '/');
            }
        }

        if ($this->isAdmin() || $this->isOfficial()) {
            return asset('images/default-avatar-admin.svg');
        }

        return asset('images/default-avatar-resident.svg');
    }

    // Relationships
    public function purok(): BelongsTo
    {
        return $this->belongsTo(Purok::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'resident_id');
    }

    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'assigned_to');
    }

    public function verificationRequests(): HasMany
    {
        return $this->hasMany(ResidentVerificationRequest::class, 'resident_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'recipient_id')->orderBy('created_at', 'desc');
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function chatSessions(): HasMany
    {
        return $this->hasMany(ChatSession::class, 'resident_id');
    }

    public function notificationPreferences(): HasOne
    {
        return $this->hasOne(NotificationPreference::class, 'user_id');
    }
}
