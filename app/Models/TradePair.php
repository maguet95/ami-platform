<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradePair extends Model
{
    protected $fillable = [
        'symbol',
        'market',
        'display_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function tradeEntries(): HasMany
    {
        return $this->hasMany(TradeEntry::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
