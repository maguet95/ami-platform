<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $manual_trade_id
 * @property string $image_path
 * @property string|null $caption
 * @property int $sort_order
 * @property-read \App\Models\ManualTrade $manualTrade
 */
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
