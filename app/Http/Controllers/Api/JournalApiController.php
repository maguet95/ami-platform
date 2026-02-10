<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JournalSummary;
use App\Models\TradeEntry;
use App\Models\TradePair;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JournalApiController extends Controller
{
    /**
     * POST /api/internal/journal/entries
     * Receive a batch of trade entries from workers.
     */
    public function storeEntries(Request $request): JsonResponse
    {
        $maxEntries = config('journal.max_entries_per_request', 100);

        $validator = Validator::make($request->all(), [
            'entries' => "required|array|min:1|max:{$maxEntries}",
            'entries.*.user_id' => 'required|integer',
            'entries.*.external_id' => 'required|string|max:100',
            'entries.*.symbol' => 'required|string|max:20',
            'entries.*.market' => 'required|string|max:20',
            'entries.*.direction' => 'required|in:long,short',
            'entries.*.entry_price' => 'required|numeric',
            'entries.*.exit_price' => 'nullable|numeric',
            'entries.*.quantity' => 'required|numeric|gt:0',
            'entries.*.pnl' => 'nullable|numeric',
            'entries.*.pnl_percentage' => 'nullable|numeric',
            'entries.*.fee' => 'nullable|numeric|gte:0',
            'entries.*.opened_at' => 'required|date',
            'entries.*.closed_at' => 'nullable|date',
            'entries.*.duration_seconds' => 'nullable|integer|gte:0',
            'entries.*.status' => 'required|in:open,closed,cancelled',
            'entries.*.tags' => 'nullable|array',
            'entries.*.notes' => 'nullable|string|max:2000',
            'entries.*.source' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $entries = $request->input('entries');
        $created = 0;
        $duplicatesSkipped = 0;
        $errors = [];

        foreach ($entries as $index => $entry) {
            try {
                // Verify user exists
                if (! User::where('id', $entry['user_id'])->exists()) {
                    $errors[] = [
                        'index' => $index,
                        'external_id' => $entry['external_id'],
                        'error' => "user_id {$entry['user_id']} not found",
                    ];
                    continue;
                }

                // Find or create trade pair
                $tradePair = TradePair::firstOrCreate(
                    ['symbol' => $entry['symbol'], 'market' => $entry['market']],
                    ['display_name' => $entry['symbol']]
                );

                // Check for duplicate (deduplication)
                $exists = TradeEntry::where('user_id', $entry['user_id'])
                    ->where('external_id', $entry['external_id'])
                    ->where('source', $entry['source'])
                    ->exists();

                if ($exists) {
                    $duplicatesSkipped++;
                    continue;
                }

                TradeEntry::create([
                    'user_id' => $entry['user_id'],
                    'trade_pair_id' => $tradePair->id,
                    'external_id' => $entry['external_id'],
                    'direction' => $entry['direction'],
                    'entry_price' => $entry['entry_price'],
                    'exit_price' => $entry['exit_price'] ?? null,
                    'quantity' => $entry['quantity'],
                    'pnl' => $entry['pnl'] ?? null,
                    'pnl_percentage' => $entry['pnl_percentage'] ?? null,
                    'fee' => $entry['fee'] ?? 0,
                    'opened_at' => $entry['opened_at'],
                    'closed_at' => $entry['closed_at'] ?? null,
                    'duration_seconds' => $entry['duration_seconds'] ?? null,
                    'status' => $entry['status'],
                    'tags' => $entry['tags'] ?? null,
                    'notes' => $entry['notes'] ?? null,
                    'source' => $entry['source'],
                ]);

                $created++;
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'external_id' => $entry['external_id'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        $apiKey = $request->attributes->get('journal_api_key');
        Log::info('Journal API: entries written', [
            'key_id' => $apiKey->id,
            'received' => count($entries),
            'created' => $created,
            'duplicates' => $duplicatesSkipped,
            'errors' => count($errors),
        ]);

        $status = count($errors) > 0 ? 'partial' : 'ok';
        $httpCode = count($errors) > 0 ? 207 : 201;

        return response()->json([
            'status' => $status,
            'received' => count($entries),
            'created' => $created,
            'duplicates_skipped' => $duplicatesSkipped,
            'errors' => $errors,
        ], $httpCode);
    }

    /**
     * POST /api/internal/journal/summaries
     * Receive pre-calculated summaries from workers (upsert).
     */
    public function storeSummaries(Request $request): JsonResponse
    {
        $maxSummaries = config('journal.max_summaries_per_request', 50);

        $validator = Validator::make($request->all(), [
            'summaries' => "required|array|min:1|max:{$maxSummaries}",
            'summaries.*.user_id' => 'required|integer|exists:users,id',
            'summaries.*.period_type' => 'required|in:daily,weekly,monthly,all_time',
            'summaries.*.period_start' => 'required|date',
            'summaries.*.period_end' => 'required|date',
            'summaries.*.total_trades' => 'required|integer|gte:0',
            'summaries.*.winning_trades' => 'required|integer|gte:0',
            'summaries.*.losing_trades' => 'required|integer|gte:0',
            'summaries.*.win_rate' => 'required|numeric|between:0,100',
            'summaries.*.total_pnl' => 'required|numeric',
            'summaries.*.max_drawdown' => 'nullable|numeric',
            'summaries.*.best_trade_pnl' => 'nullable|numeric',
            'summaries.*.worst_trade_pnl' => 'nullable|numeric',
            'summaries.*.avg_trade_duration' => 'nullable|integer|gte:0',
            'summaries.*.profit_factor' => 'nullable|numeric|gte:0',
            'summaries.*.metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $summaries = $request->input('summaries');
        $upserted = 0;
        $errors = [];

        foreach ($summaries as $index => $summary) {
            try {
                JournalSummary::updateOrCreate(
                    [
                        'user_id' => $summary['user_id'],
                        'period_type' => $summary['period_type'],
                        'period_start' => $summary['period_start'],
                    ],
                    [
                        'period_end' => $summary['period_end'],
                        'total_trades' => $summary['total_trades'],
                        'winning_trades' => $summary['winning_trades'],
                        'losing_trades' => $summary['losing_trades'],
                        'win_rate' => $summary['win_rate'],
                        'total_pnl' => $summary['total_pnl'],
                        'max_drawdown' => $summary['max_drawdown'] ?? 0,
                        'best_trade_pnl' => $summary['best_trade_pnl'] ?? 0,
                        'worst_trade_pnl' => $summary['worst_trade_pnl'] ?? 0,
                        'avg_trade_duration' => $summary['avg_trade_duration'] ?? 0,
                        'profit_factor' => $summary['profit_factor'] ?? 0,
                        'metadata' => $summary['metadata'] ?? null,
                        'calculated_at' => now(),
                    ]
                );

                $upserted++;
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'error' => $e->getMessage(),
                ];
            }
        }

        $apiKey = $request->attributes->get('journal_api_key');
        Log::info('Journal API: summaries written', [
            'key_id' => $apiKey->id,
            'received' => count($summaries),
            'upserted' => $upserted,
            'errors' => count($errors),
        ]);

        return response()->json([
            'status' => count($errors) > 0 ? 'partial' : 'ok',
            'received' => count($summaries),
            'upserted' => $upserted,
            'errors' => $errors,
        ], count($errors) > 0 ? 207 : 201);
    }

    /**
     * GET /api/internal/journal/health
     * Health check for workers.
     */
    public function health(): JsonResponse
    {
        $dbWritable = true;
        try {
            DB::select('SELECT 1');
        } catch (\Exception $e) {
            $dbWritable = false;
        }

        return response()->json([
            'status' => 'ok',
            'module' => 'journal',
            'active' => true,
            'db_writable' => $dbWritable,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
