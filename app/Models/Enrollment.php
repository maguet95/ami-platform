<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'enrolled_at',
        'completed_at',
        'expires_at',
        'progress_percent',
    ];

    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'completed_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helpers

    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percent' => 100,
        ]);
    }

    public function recalculateProgress(): void
    {
        $course = $this->course;
        $totalLessons = $course->lessons()->where('is_published', true)->count();

        if ($totalLessons === 0) {
            $this->update(['progress_percent' => 0]);

            return;
        }

        $completedLessons = LessonProgress::where('user_id', $this->user_id)
            ->where('is_completed', true)
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->count();

        $percent = (int) round(($completedLessons / $totalLessons) * 100);
        $this->update(['progress_percent' => $percent]);

        if ($percent >= 100) {
            $this->markCompleted();
        }
    }
}
