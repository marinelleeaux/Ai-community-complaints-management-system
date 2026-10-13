<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_id',
        'action',
        'entity_type',
        'entity_id',
        'safe_metadata',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'safe_metadata' => 'array',
        ];
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public static function log(?string $actorId, string $action, ?string $entityType = null, ?string $entityId = null, ?array $metadata = null): self
    {
        return self::create([
            'actor_id' => $actorId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'safe_metadata' => $metadata,
            'ip_address' => request()->ip(),
        ]);
    }
}
