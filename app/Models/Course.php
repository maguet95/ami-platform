<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $instructor_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $short_description
 * @property string|null $image
 * @property string $level
 * @property string|null $price
 * @property string $currency
 * @property string $status
 * @property int|null $duration_hours
 * @property int $sort_order
 * @property bool $is_featured
 * @property bool $is_free
 * @property string $access_type
 * @property \Carbon\Carbon|null $published_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Module> $modules
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'title',
        'slug',
        'description',
        'short_description',
        'image',
        'level',
        'price',
        'currency',
        'status',
        'duration_hours',
        'sort_order',
        'is_featured',
        'is_free',
        'access_type',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
            'access_type' => 'string',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    // Relationships

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('sort_order');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Module::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function liveClasses(): HasMany
    {
        return $this->hasMany(LiveClass::class);
    }

    // Scopes

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeNotExclusive($query)
    {
        return $query->where('access_type', '!=', 'exclusive');
    }

    // Helpers

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function getLessonCount(): int
    {
        return $this->lessons()->count();
    }

    public function getModuleCount(): int
    {
        return $this->modules()->count();
    }

    public function getFormattedPrice(): string
    {
        if ($this->access_type === 'free') {
            return 'Gratis';
        }

        return '$'.number_format((float) $this->price, 2).' '.$this->currency;
    }

    public function getAccessLabel(): string
    {
        return match ($this->access_type) {
            'free' => 'Gratuito',
            'exclusive' => 'Exclusivo',
            default => 'Premium',
        };
    }

    public function getLevelLabel(): string
    {
        return match ($this->level) {
            'beginner' => 'Principiante',
            'intermediate' => 'Intermedio',
            'advanced' => 'Avanzado',
            default => $this->level,
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Borrador',
            'published' => 'Publicado',
            'archived' => 'Archivado',
            default => $this->status,
        };
    }
}
