<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'stripe_product_id',
        'stripe_price_id',
        'price',
        'currency',
        'interval',
        'features',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Plan $plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name);
            }
        });
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers

    public function getFormattedPrice(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getIntervalLabel(): string
    {
        return match ($this->interval) {
            'monthly' => 'Mensual',
            'yearly' => 'Anual',
            default => $this->interval,
        };
    }
}
