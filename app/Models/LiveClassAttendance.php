<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $live_class_id
 * @property int $user_id
 * @property string $status
 * @property \Carbon\Carbon|null $notified_at
 * @property \Carbon\Carbon|null $attended_at
 * @property string $access_token
 * @property-read \App\Models\User $user
 */
class LiveClassAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_class_id',
        'user_id',
        'status',
        'notified_at',
        'attended_at',
        'access_token',
    ];

    protected function casts(): array
    {
        return [
            'notified_at' => 'datetime',
            'attended_at' => 'datetime',
        ];
    }

    // Relationships

    public function liveClass(): BelongsTo
    {
        return $this->belongsTo(LiveClass::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helpers

    public function markNotified(): void
    {
        $this->update([
            'status' => 'notified',
            'notified_at' => now(),
        ]);
    }

    public function markAttended(): void
    {
        $this->update([
            'status' => 'attended',
            'attended_at' => now(),
        ]);
    }
}
