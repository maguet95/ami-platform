<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'icon', 'category',
        'xp_reward', 'requirement_type', 'requirement_value',
        'tier', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot('earned_at');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTierColor(): string
    {
        return match ($this->tier) {
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
            'diamond' => '#B9F2FF',
            default => '#808080',
        };
    }

    public function getTierLabel(): string
    {
        return match ($this->tier) {
            'bronze' => 'Bronce',
            'silver' => 'Plata',
            'gold' => 'Oro',
            'diamond' => 'Diamante',
            default => $this->tier,
        };
    }

    public function getCategoryLabel(): string
    {
        return match ($this->category) {
            'learning' => 'Aprendizaje',
            'engagement' => 'Compromiso',
            'milestone' => 'Hito',
            default => $this->category,
        };
    }
}
