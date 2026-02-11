<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SubscriptionExpiringSoonNotification;
use Illuminate\Console\Command;

class SendSubscriptionExpiringReminders extends Command
{
    protected $signature = 'email:subscription-expiring';

    protected $description = 'Send reminders to users whose subscriptions expire in 3 days';

    public function handle(): int
    {
        $targetDate = now()->addDays(3)->toDateString();

        $users = User::whereHas('subscriptions', function ($query) use ($targetDate) {
            $query->where('ends_at', '>=', now())
                ->where('ends_at', '<=', now()->addDays(3)->endOfDay());
        })
            ->where('email_notifications', true)
            ->whereNull('subscription_expiry_notified_at')
            ->get();

        $count = 0;

        foreach ($users as $user) {
            $subscription = $user->subscription('default');

            if (! $subscription || ! $subscription->ends_at) {
                continue;
            }

            $daysLeft = (int) now()->diffInDays($subscription->ends_at, false);

            if ($daysLeft < 0 || $daysLeft > 3) {
                continue;
            }

            $user->notify(new SubscriptionExpiringSoonNotification($daysLeft));
            $user->update(['subscription_expiry_notified_at' => now()]);
            $count++;
        }

        $this->info("Sent {$count} subscription expiring reminders.");

        return self::SUCCESS;
    }
}
