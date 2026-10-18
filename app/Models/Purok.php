<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Purok extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('active_puroks_cached');
        });

        static::deleted(function () {
            Cache::forget('active_puroks_cached');
        });
    }

    public static function getActiveCached()
    {
        return Cache::remember('active_puroks_cached', 300, function () {
            return static::where('is_active', true)->orderBy('name')->get();
        });
    }

    public function residents(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
