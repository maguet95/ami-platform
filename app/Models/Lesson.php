<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'content',
        'video_url',
        'video_provider',
        'type',
        'duration_minutes',
        'sort_order',
        'is_published',
        'is_free_preview',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_free_preview' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Lesson $lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });
    }

    // Relationships

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // Helpers

    public function getCourse(): Course
    {
        return $this->module->course;
    }

    public function getFormattedDuration(): string
    {
        if ($this->duration_minutes < 60) {
            return $this->duration_minutes . ' min';
        }

        $hours = intdiv($this->duration_minutes, 60);
        $mins = $this->duration_minutes % 60;

        return $hours . 'h ' . ($mins > 0 ? $mins . 'min' : '');
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'video' => 'Video',
            'text' => 'Texto',
            'quiz' => 'Quiz',
            default => $this->type,
        };
    }
}
