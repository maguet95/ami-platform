<?php

namespace App\Events;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCancelled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public ?Carbon $endsAt = null,
    ) {}
}
