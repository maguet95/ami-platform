<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasFactory, HasRoles, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->hasRole('admin'),
            'instructor' => $this->hasRole(['instructor', 'admin']),
            default => false,
        };
    }

    public function instructorCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar_url',
        'username',
        'bio',
        'avatar',
        'location',
        'twitter_handle',
        'trading_since',
        'is_profile_public',
        'share_manual_journal',
        'share_automatic_journal',
        'automatic_journal_account_type',
        'total_xp',
        'current_streak',
        'longest_streak',
        'last_active_date',
        'email_notifications',
        'weekly_digest',
        'subscription_expiry_notified_at',
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
            'password' => 'hashed',
            'trading_since' => 'date',
            'is_profile_public' => 'boolean',
            'share_manual_journal' => 'boolean',
            'share_automatic_journal' => 'boolean',
            'last_active_date' => 'date',
            'email_notifications' => 'boolean',
            'weekly_digest' => 'boolean',
            'subscription_expiry_notified_at' => 'datetime',
        ];
    }

    // Enrollment relationships

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses(): HasManyThrough
    {
        return $this->hasManyThrough(Course::class, Enrollment::class, 'user_id', 'id', 'id', 'course_id');
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function isEnrolledIn(Course $course): bool
    {
        return $this->enrollments()->where('course_id', $course->id)->active()->exists();
    }

    // Subscription helpers

    public function hasActiveSubscription(): bool
    {
        try {
            return $this->subscribed('default');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function canAccessCourse(Course $course): bool
    {
        if ($course->is_free) {
            return true;
        }

        if ($this->hasRole(['admin', 'instructor'])) {
            return true;
        }

        return $this->hasActiveSubscription();
    }

    // Journal relationships

    public function tradeEntries(): HasMany
    {
        return $this->hasMany(TradeEntry::class);
    }

    public function journalSummaries(): HasMany
    {
        return $this->hasMany(JournalSummary::class);
    }

    // Manual Journal (Bitacora)

    public function manualTrades(): HasMany
    {
        return $this->hasMany(ManualTrade::class);
    }

    // Gamification relationships

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at');
    }

    public function xpTransactions(): HasMany
    {
        return $this->hasMany(XpTransaction::class);
    }

    public function loginLogs(): HasMany
    {
        return $this->hasMany(UserLoginLog::class);
    }

    // Gamification helpers

    public function addXp(int $amount, string $type, string $description, ?string $refType = null, ?int $refId = null): void
    {
        $this->xpTransactions()->create([
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'reference_type' => $refType,
            'reference_id' => $refId,
        ]);

        $this->increment('total_xp', $amount);
    }

    public function getLevel(): int
    {
        // Every 100 XP = 1 level, minimum level 1
        return max(1, (int) floor($this->total_xp / 100) + 1);
    }

    public function getLevelProgress(): int
    {
        // Percentage towards next level
        return $this->total_xp % 100;
    }

    public function getRank(): int
    {
        return self::where('total_xp', '>', $this->total_xp)
            ->where('is_profile_public', true)
            ->count() + 1;
    }

    public function getCompletedCoursesCount(): int
    {
        return $this->enrollments()->where('status', 'completed')->count();
    }

    public function getCompletedLessonsCount(): int
    {
        return $this->lessonProgress()->where('is_completed', true)->count();
    }

    public function recordLogin(): void
    {
        $today = now()->toDateString();

        $this->loginLogs()->firstOrCreate(['logged_in_at' => $today]);
    }
}
