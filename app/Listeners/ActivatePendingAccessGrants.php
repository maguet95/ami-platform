<?php

namespace App\Listeners;

use App\Models\AccessGrant;
use App\Notifications\AccessGrantedNotification;
use Illuminate\Auth\Events\Registered;

class ActivatePendingAccessGrants
{
    public function handle(Registered $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        $grants = AccessGrant::pending()
            ->forEmail($user->email)
            ->get();

        foreach ($grants as $grant) {
            $grant->activate($user);
            $user->notify(new AccessGrantedNotification($grant));
        }
    }
}
