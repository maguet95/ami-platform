<?php

namespace App\Http\Middleware;

use App\Models\JournalApiKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class JournalApiAuth
{
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        // Check feature flag
        if (! config('journal.enabled')) {
            return response()->json([
                'status' => 'unavailable',
                'module' => 'journal',
                'active' => false,
                'message' => 'Journal module is disabled',
            ], 503);
        }

        $plainKey = $request->header('X-API-Key');

        if (! $plainKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing API key',
            ], 401);
        }

        $apiKey = JournalApiKey::findByKey($plainKey);

        if (! $apiKey) {
            Log::warning('Journal API: invalid key attempt', [
                'ip' => $request->ip(),
                'prefix' => substr($plainKey, 0, 8),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired API key',
            ], 401);
        }

        if ($apiKey->isExpired()) {
            Log::warning('Journal API: expired key', [
                'key_id' => $apiKey->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'API key has expired',
            ], 401);
        }

        if (! $apiKey->isIpAllowed($request->ip())) {
            Log::warning('Journal API: IP not allowed', [
                'key_id' => $apiKey->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'IP not authorized',
            ], 403);
        }

        if ($permission && ! $apiKey->hasPermission($permission)) {
            return response()->json([
                'status' => 'error',
                'message' => "Missing permission: {$permission}",
            ], 403);
        }

        $apiKey->recordUsage();

        $request->attributes->set('journal_api_key', $apiKey);

        return $next($request);
    }
}
