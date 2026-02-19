<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $trade_pair_id
 * @property string $external_id
 * @property string $direction
 * @property string $entry_price
 * @property string|null $exit_price
 * @property string $quantity
 * @property string|null $pnl
 * @property string|null $pnl_percentage
 * @property string|null $fee
 * @property \Carbon\Carbon|null $opened_at
 * @property \Carbon\Carbon|null $closed_at
 * @property int|null $duration_seconds
 * @property string $status
 * @property array<int, string>|null $tags
 * @property string|null $notes
 * @property string $source
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static> closed()
 * @method static \Illuminate\Database\Eloquent\Builder<static> winning()
 * @method static \Illuminate\Database\Eloquent\Builder<static> losing()
 */
class TradeEntry extends Model
{
    protected $fillable = [
        'user_id',
        'trade_pair_id',
        'external_id',
        'direction',
        'entry_price',
        'exit_price',
        'quantity',
        'pnl',
        'pnl_percentage',
        'fee',
        'opened_at',
        'closed_at',
        'duration_seconds',
        'status',
        'tags',
        'notes',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'entry_price' => 'decimal:8',
            'exit_price' => 'decimal:8',
            'quantity' => 'decimal:8',
            'pnl' => 'decimal:8',
            'pnl_percentage' => 'decimal:4',
            'fee' => 'decimal:8',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'tags' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tradePair(): BelongsTo
    {
        return $this->belongsTo(TradePair::class);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeWinning($query)
    {
        return $query->where('pnl', '>', 0);
    }

    public function scopeLosing($query)
    {
        return $query->where('pnl', '<', 0);
    }

    public function isWinning(): bool
    {
        return $this->pnl > 0;
    }
}
