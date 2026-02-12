<?php

namespace App\Providers;

use App\Events\AchievementUnlocked;
use App\Events\CourseEnrolled;
use App\Events\PaymentFailed;
use App\Events\SubscriptionCancelled;
use App\Events\SubscriptionConfirmed;
use App\Events\SubscriptionRenewed;
use App\Listeners\SendAchievementEmail;
use App\Listeners\SendCourseEnrollmentEmail;
use App\Listeners\SendPasswordChangedEmail;
use App\Listeners\SendPaymentFailedEmail;
use App\Listeners\SendSubscriptionCancelledEmail;
use App\Listeners\SendSubscriptionConfirmedEmail;
use App\Listeners\SendSubscriptionRenewedEmail;
use App\Listeners\ActivatePendingAccessGrants;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Prevent lazy loading in development (catches N+1 queries)
        Model::preventLazyLoading(! app()->isProduction());

        // Email notification events
        Event::listen(Registered::class, ActivatePendingAccessGrants::class);
        Event::listen(Verified::class, SendWelcomeEmail::class);
        Event::listen(PasswordReset::class, SendPasswordChangedEmail::class);
        Event::listen(AchievementUnlocked::class, SendAchievementEmail::class);
        Event::listen(CourseEnrolled::class, SendCourseEnrollmentEmail::class);
        Event::listen(SubscriptionConfirmed::class, SendSubscriptionConfirmedEmail::class);
        Event::listen(SubscriptionRenewed::class, SendSubscriptionRenewedEmail::class);
        Event::listen(PaymentFailed::class, SendPaymentFailedEmail::class);
        Event::listen(SubscriptionCancelled::class, SendSubscriptionCancelledEmail::class);

        // Global web rate limit: 120 requests/minute per IP
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        // Auth attempts: 5 per minute per IP (login, register, password reset)
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Contact form: 3 per minute per IP
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        // Stripe webhooks: 60 per minute
        RateLimiter::for('webhooks', function (Request $request) {
            return Limit::perMinute(60);
        });

        // Subscription checkout: 10 per minute per user
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}
