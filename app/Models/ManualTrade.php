<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualTrade extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'trade_pair_id',
        'direction',
        'trade_date',
        'timeframe',
        'session',
        'entry_price',
        'exit_price',
        'stop_loss',
        'take_profit',
        'position_size',
        'risk_reward_planned',
        'risk_reward_actual',
        'pnl',
        'pnl_percentage',
        'commission',
        'status',
        'had_plan',
        'plan_followed',
        'entry_reason',
        'invalidation_criteria',
        'mistakes',
        'lessons_learned',
        'emotion_before',
        'emotion_during',
        'emotion_after',
        'confidence_level',
        'stress_level',
        'psychology_notes',
        'market_condition',
        'key_levels',
        'relevant_news',
        'additional_confluence',
        'what_i_did_well',
        'what_to_improve',
        'would_take_again',
        'overall_rating',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'trade_date' => 'date',
            'entry_price' => 'decimal:8',
            'exit_price' => 'decimal:8',
            'stop_loss' => 'decimal:8',
            'take_profit' => 'decimal:8',
            'position_size' => 'decimal:8',
            'risk_reward_planned' => 'decimal:2',
            'risk_reward_actual' => 'decimal:2',
            'pnl' => 'decimal:8',
            'pnl_percentage' => 'decimal:4',
            'commission' => 'decimal:8',
            'had_plan' => 'boolean',
            'would_take_again' => 'boolean',
            'mistakes' => 'array',
        ];
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tradePair(): BelongsTo
    {
        return $this->belongsTo(TradePair::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ManualTradeImage::class)->orderBy('sort_order');
    }

    // Scopes

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
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

    public function scopeBreakeven($query)
    {
        return $query->where('status', 'closed')->where(function ($q) {
            $q->where('pnl', 0)->orWhereNull('pnl');
        });
    }

    // Helpers

    public function isWinning(): bool
    {
        return $this->pnl > 0;
    }

    public function isLosing(): bool
    {
        return $this->pnl < 0;
    }

    public function getResultLabel(): string
    {
        if ($this->status === 'open') {
            return 'Abierto';
        }

        if ($this->pnl === null) {
            return 'Sin P&L';
        }

        if ($this->pnl > 0) {
            return 'Ganador';
        }

        if ($this->pnl < 0) {
            return 'Perdedor';
        }

        return 'Breakeven';
    }

    public function getResultColor(): string
    {
        if ($this->status === 'open') {
            return 'text-ami-400';
        }

        if ($this->pnl === null) {
            return 'text-surface-500';
        }

        if ($this->pnl > 0) {
            return 'text-bullish';
        }

        if ($this->pnl < 0) {
            return 'text-bearish';
        }

        return 'text-surface-400';
    }

    public static function emotionOptions(): array
    {
        return [
            'calm' => 'Calmado',
            'confident' => 'Confiado',
            'anxious' => 'Ansioso',
            'fearful' => 'Temeroso',
            'greedy' => 'Codicioso',
            'frustrated' => 'Frustrado',
            'euphoric' => 'Euforico',
            'neutral' => 'Neutral',
        ];
    }

    public static function emotionColor(string $emotion): string
    {
        return match ($emotion) {
            'calm' => 'bg-emerald-500/10 text-emerald-400',
            'confident' => 'bg-blue-500/10 text-blue-400',
            'anxious' => 'bg-yellow-500/10 text-yellow-400',
            'fearful' => 'bg-red-500/10 text-red-400',
            'greedy' => 'bg-orange-500/10 text-orange-400',
            'frustrated' => 'bg-rose-500/10 text-rose-400',
            'euphoric' => 'bg-purple-500/10 text-purple-400',
            'neutral' => 'bg-surface-500/10 text-surface-400',
            default => 'bg-surface-500/10 text-surface-400',
        };
    }

    public static function marketConditionOptions(): array
    {
        return [
            'trending_up' => 'Tendencia alcista',
            'trending_down' => 'Tendencia bajista',
            'ranging' => 'Rango / Lateral',
            'volatile' => 'Volatil',
            'low_volume' => 'Bajo volumen',
        ];
    }

    public static function mistakeOptions(): array
    {
        return [
            'no_plan' => 'Sin plan de trading',
            'moved_stop' => 'Movi el stop loss',
            'oversize' => 'Posicion muy grande',
            'revenge_trade' => 'Trade de venganza',
            'fomo' => 'FOMO',
            'early_exit' => 'Sali muy temprano',
            'late_entry' => 'Entre muy tarde',
            'ignored_levels' => 'Ignore niveles clave',
            'no_confluence' => 'Sin confluencia',
            'emotional' => 'Decision emocional',
        ];
    }

    public static function timeframeOptions(): array
    {
        return [
            '1m' => '1 min',
            '5m' => '5 min',
            '15m' => '15 min',
            '30m' => '30 min',
            '1h' => '1 hora',
            '4h' => '4 horas',
            '1d' => 'Diario',
            '1w' => 'Semanal',
        ];
    }

    public static function sessionOptions(): array
    {
        return [
            'asian' => 'Asia',
            'london' => 'Londres',
            'new_york' => 'Nueva York',
            'overlap' => 'Overlap (LON/NY)',
        ];
    }
}
