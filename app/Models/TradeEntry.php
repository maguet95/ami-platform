<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
