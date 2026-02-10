<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class XpTransaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'amount', 'type', 'description',
        'reference_type', 'reference_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'lesson' => 'Leccion',
            'course' => 'Curso',
            'login' => 'Login diario',
            'streak' => 'Racha',
            'achievement' => 'Logro',
            default => $this->type,
        };
    }
}
