<?php

namespace App\Http\Middleware;

use App\Services\GamificationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordDailyLogin
{
    public function __construct(
        private GamificationService $gamification
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (! $user->last_active_date || $user->last_active_date->toDateString() !== now()->toDateString())) {
            $this->gamification->onDailyLogin($user);
        }

        return $next($request);
    }
}
