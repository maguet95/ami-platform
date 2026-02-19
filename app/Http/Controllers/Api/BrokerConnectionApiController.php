<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BrokerConnection;
use App\Models\TradeEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BrokerConnectionApiController extends Controller
{
    /**
     * GET /api/internal/journal/connections?type=binance
     * Return active, syncable connections with decrypted credentials.
     */
    public function index(Request $request): JsonResponse
    {
        $query = BrokerConnection::syncable()->with('user:id,name,email');

        if ($request->has('type')) {
            $query->ofType($request->input('type'));
        }

        $connections = $query->get()->map(fn (BrokerConnection $conn) => [
            'id' => $conn->id,
            'user_id' => $conn->user_id,
            'type' => $conn->type,
            'credentials' => json_decode($conn->credentials, true),
            'last_synced_at' => $conn->last_synced_at?->toIso8601String(),
            'metadata' => $conn->metadata,
        ]);

        return response()->json([
            'status' => 'ok',
            'count' => $connections->count(),
            'connections' => $connections->values(),
        ]);
    }

    /**
     * PATCH /api/internal/journal/connections/{id}/sync-status
     * Worker reports sync result.
     */
    public function updateSyncStatus(Request $request, int $id): JsonResponse
    {
        $connection = BrokerConnection::find($id);

        if (! $connection) {
            return response()->json([
                'status' => 'error',
                'message' => 'Connection not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'success' => 'required|boolean',
            'error' => 'nullable|string|max:1000',
            'trades_imported' => 'nullable|integer|gte:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        if ($request->boolean('success')) {
            $connection->markSynced();
        } else {
            $connection->markError($request->input('error', 'Unknown error'));
        }

        Log::info('Journal API: sync status updated', [
            'connection_id' => $id,
            'success' => $request->boolean('success'),
            'trades_imported' => $request->input('trades_imported', 0),
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * GET /api/internal/journal/users-with-trades
     * Return user IDs that have trade entries.
     */
    public function usersWithTrades(): JsonResponse
    {
        $userIds = TradeEntry::select('user_id')
            ->distinct()
            ->pluck('user_id');

        return response()->json([
            'status' => 'ok',
            'user_ids' => $userIds,
        ]);
    }
}
