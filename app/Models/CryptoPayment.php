<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $plan_id
 * @property int|null $access_grant_id
 * @property string $now_payment_id
 * @property string $order_id
 * @property string $status
 * @property float $price_amount
 * @property string $price_currency
 * @property string $pay_currency
 * @property string $pay_address
 * @property float|null $pay_amount
 * @property float|null $actually_paid
 * @property string $duration_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class CryptoPayment extends Model
{
    const STATUS_WAITING = 'waiting';

    const STATUS_CONFIRMING = 'confirming';

    const STATUS_CONFIRMED = 'confirmed';

    const STATUS_SENDING = 'sending';

    const STATUS_FINISHED = 'finished';

    const STATUS_PARTIALLY_PAID = 'partially_paid';

    const STATUS_FAILED = 'failed';

    const STATUS_EXPIRED = 'expired';

    const FINAL_STATUSES = [self::STATUS_FINISHED, self::STATUS_FAILED, self::STATUS_EXPIRED];

    const SUCCESS_STATUSES = [self::STATUS_CONFIRMED, self::STATUS_SENDING, self::STATUS_FINISHED];

    protected $fillable = [
        'user_id',
        'plan_id',
        'access_grant_id',
        'now_payment_id',
        'order_id',
        'status',
        'price_amount',
        'price_currency',
        'pay_currency',
        'pay_address',
        'pay_amount',
        'actually_paid',
        'duration_type',
    ];

    protected function casts(): array
    {
        return [
            'price_amount' => 'decimal:2',
            'pay_amount' => 'decimal:8',
            'actually_paid' => 'decimal:8',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function accessGrant(): BelongsTo
    {
        return $this->belongsTo(AccessGrant::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_WAITING, self::STATUS_CONFIRMING]);
    }

    public function getCurrencyLabel(): string
    {
        return strtoupper(str_replace(['trc20', 'erc20', 'bep20'], '', $this->pay_currency));
    }
}
