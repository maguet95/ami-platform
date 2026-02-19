<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokerConnection extends Model
{
    const TYPE_MT4 = 'metatrader4';
    const TYPE_MT5 = 'metatrader5';
    const TYPE_BINANCE = 'binance';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ERROR = 'error';

    protected $fillable = [
        'user_id',
        'type',
        'credentials',
        'status',
        'last_synced_at',
        'last_error',
        'sync_enabled',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'encrypted',
            'sync_enabled' => 'boolean',
            'metadata' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSyncable($query)
    {
        return $query->active()->where('sync_enabled', true);
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_MT4 => 'MetaTrader 4',
            self::TYPE_MT5 => 'MetaTrader 5',
            self::TYPE_BINANCE => 'Binance',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabels()[$this->type] ?? $this->type;
    }

    public function markError(string $error): void
    {
        $this->update([
            'status' => self::STATUS_ERROR,
            'last_error' => $error,
        ]);
    }

    public function markSynced(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'last_synced_at' => now(),
            'last_error' => null,
        ]);
    }
}
