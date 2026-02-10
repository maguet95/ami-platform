<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualTradeImage extends Model
{
    protected $fillable = [
        'manual_trade_id',
        'image_path',
        'caption',
        'sort_order',
    ];

    public function manualTrade(): BelongsTo
    {
        return $this->belongsTo(ManualTrade::class);
    }
}
