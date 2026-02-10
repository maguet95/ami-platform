<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoginLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'logged_in_at'];

    protected function casts(): array
    {
        return [
            'logged_in_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
