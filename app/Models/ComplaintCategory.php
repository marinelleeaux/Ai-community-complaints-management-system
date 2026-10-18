<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class ComplaintCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
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
            Cache::forget('active_categories_cached');
        });

        static::deleted(function () {
            Cache::forget('active_categories_cached');
        });
    }

    public static function getActiveCached()
    {
        return Cache::remember('active_categories_cached', 300, function () {
            return static::where('is_active', true)->orderBy('name')->get();
        });
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'category_id');
    }
}
