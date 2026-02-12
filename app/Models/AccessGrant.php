<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AccessGrant extends Model
{
    // Duration types
    const DURATION_LIFETIME = 'lifetime';
    const DURATION_1_MONTH = '1_month';
    const DURATION_3_MONTHS = '3_months';
    const DURATION_6_MONTHS = '6_months';
    const DURATION_1_YEAR = '1_year';

    // Statuses
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_REVOKED = 'revoked';

    protected $fillable = [
        'email',
        'user_id',
        'granted_by',
        'duration_type',
        'starts_at',
        'expires_at',
        'status',
        'token',
        'notes',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public static function durationOptions(): array
    {
        return [
            self::DURATION_LIFETIME => 'De por vida',
            self::DURATION_1_MONTH => '1 mes',
            self::DURATION_3_MONTHS => '3 meses',
            self::DURATION_6_MONTHS => '6 meses',
            self::DURATION_1_YEAR => '1 año',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_ACTIVE => 'Activo',
            self::STATUS_EXPIRED => 'Expirado',
            self::STATUS_REVOKED => 'Revocado',
        ];
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grantedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeForEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', strtolower($email));
    }

    public function scopeCurrentlyValid(Builder $query): Builder
    {
        return $query->active()->where(function (Builder $q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    // Helpers

    public static function generateToken(): string
    {
        do {
            $token = Str::random(64);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public static function computeExpiresAt(string $durationType, ?\DateTimeInterface $startsAt = null): ?\DateTimeInterface
    {
        if ($durationType === self::DURATION_LIFETIME) {
            return null;
        }

        $start = $startsAt ? now()->parse($startsAt) : now();

        return match ($durationType) {
            self::DURATION_1_MONTH => $start->copy()->addMonth(),
            self::DURATION_3_MONTHS => $start->copy()->addMonths(3),
            self::DURATION_6_MONTHS => $start->copy()->addMonths(6),
            self::DURATION_1_YEAR => $start->copy()->addYear(),
            default => null,
        };
    }

    public function isCurrentlyValid(): bool
    {
        if ($this->status === self::STATUS_REVOKED) {
            return false;
        }

        if ($this->status === self::STATUS_ACTIVE) {
            if ($this->expires_at === null) {
                return true; // lifetime
            }

            if ($this->expires_at->isPast()) {
                $this->update(['status' => self::STATUS_EXPIRED]);
                return false;
            }

            return true;
        }

        return false;
    }

    public function activate(?User $user = null): void
    {
        $now = now();
        $data = [
            'status' => self::STATUS_ACTIVE,
            'starts_at' => $now,
            'expires_at' => self::computeExpiresAt($this->duration_type, $now),
        ];

        if ($user) {
            $data['user_id'] = $user->id;
        }

        $this->update($data);
    }

    public function revoke(): void
    {
        $this->update([
            'status' => self::STATUS_REVOKED,
            'revoked_at' => now(),
        ]);
    }
}
