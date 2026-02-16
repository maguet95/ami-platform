<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'instructor_id',
        'title',
        'description',
        'platform',
        'meeting_url',
        'meeting_password',
        'starts_at',
        'duration_minutes',
        'status',
        'notification_sent',
        'max_attendees',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'notification_sent' => 'boolean',
        ];
    }

    // Relationships

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(LiveClassAttendance::class);
    }

    // Scopes

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')->where('starts_at', '>', now());
    }

    public function scopeNeedsReminder($query)
    {
        return $query->where('status', 'scheduled')
            ->where('notification_sent', false)
            ->where('starts_at', '<=', now()->addMinutes(20))
            ->where('starts_at', '>', now());
    }

    // Helpers

    public function getEndsAt(): \Carbon\Carbon
    {
        return $this->starts_at->copy()->addMinutes($this->duration_minutes);
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'scheduled' && $this->starts_at->isFuture();
    }

    public function isJoinable(): bool
    {
        if ($this->status === 'cancelled') {
            return false;
        }

        $windowStart = $this->starts_at->copy()->subMinutes(15);
        $windowEnd = $this->getEndsAt()->addHours(2);

        return now()->between($windowStart, $windowEnd);
    }

    public function isTokenValid(): bool
    {
        return now()->isBefore($this->getEndsAt()->addHours(2));
    }

    public function getRegisteredCount(): int
    {
        return $this->attendances()->count();
    }

    public function isFull(): bool
    {
        if (is_null($this->max_attendees)) {
            return false;
        }

        return $this->getRegisteredCount() >= $this->max_attendees;
    }

    public function getPlatformLabel(): string
    {
        return match ($this->platform) {
            'zoom' => 'Zoom',
            'google_meet' => 'Google Meet',
            'microsoft_teams' => 'Microsoft Teams',
            'discord' => 'Discord',
            'other' => 'Otra plataforma',
            default => $this->platform,
        };
    }

    public function getPlatformIcon(): string
    {
        return match ($this->platform) {
            'zoom' => 'video-camera',
            'google_meet' => 'computer-desktop',
            'microsoft_teams' => 'chat-bubble-left-right',
            'discord' => 'speaker-wave',
            default => 'link',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'scheduled' => 'Programada',
            'in_progress' => 'En Progreso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => $this->status,
        };
    }

    public function registerStudents(): void
    {
        if (! $this->course_id) {
            return;
        }

        $enrolledUserIds = Enrollment::where('course_id', $this->course_id)
            ->active()
            ->pluck('user_id');

        foreach ($enrolledUserIds as $userId) {
            $this->attendances()->firstOrCreate(
                ['user_id' => $userId],
                ['access_token' => bin2hex(random_bytes(32))]
            );
        }
    }
}
