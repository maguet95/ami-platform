<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalSummary extends Model
{
    protected $fillable = [
        'user_id',
        'period_type',
        'period_start',
        'period_end',
        'total_trades',
        'winning_trades',
        'losing_trades',
        'win_rate',
        'total_pnl',
        'max_drawdown',
        'best_trade_pnl',
        'worst_trade_pnl',
        'avg_trade_duration',
        'profit_factor',
        'metadata',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'win_rate' => 'decimal:2',
            'total_pnl' => 'decimal:8',
            'max_drawdown' => 'decimal:4',
            'best_trade_pnl' => 'decimal:8',
            'worst_trade_pnl' => 'decimal:8',
            'profit_factor' => 'decimal:4',
            'metadata' => 'array',
            'calculated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForPeriod($query, string $periodType)
    {
        return $query->where('period_type', $periodType);
    }

    public function scopeAllTime($query)
    {
        return $query->where('period_type', 'all_time');
    }
}
