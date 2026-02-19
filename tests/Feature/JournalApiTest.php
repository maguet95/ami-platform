<?php

namespace Tests\Feature;

use App\Models\JournalApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalApiTest extends TestCase
{
    use RefreshDatabase;

    private string $plainKey;

    private JournalApiKey $apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->apiKey, $this->plainKey] = JournalApiKey::createKey(
            'test-key',
            ['write:entries', 'write:summaries']
        );
    }

    public function test_health_endpoint_returns_ok(): void
    {
        $response = $this->getJson('/api/internal/journal/health', [
            'X-API-Key' => $this->plainKey,
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'ok',
                'module' => 'journal',
                'active' => true,
            ]);
    }

    public function test_health_endpoint_rejects_missing_key(): void
    {
        $response = $this->getJson('/api/internal/journal/health');

        $response->assertUnauthorized()
            ->assertJson(['message' => 'Missing API key']);
    }

    public function test_health_endpoint_rejects_invalid_key(): void
    {
        $response = $this->getJson('/api/internal/journal/health', [
            'X-API-Key' => 'invalid-key',
        ]);

        $response->assertUnauthorized()
            ->assertJson(['message' => 'Invalid or expired API key']);
    }

    public function test_store_entries_creates_trades(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/internal/journal/entries', [
            'entries' => [
                [
                    'user_id' => $user->id,
                    'external_id' => 'test_001',
                    'symbol' => 'EURUSD',
                    'market' => 'forex',
                    'direction' => 'long',
                    'entry_price' => 1.0950,
                    'exit_price' => 1.1000,
                    'quantity' => 1.0,
                    'pnl' => 50.0,
                    'opened_at' => '2026-01-15 10:00:00',
                    'closed_at' => '2026-01-15 14:00:00',
                    'duration_seconds' => 14400,
                    'status' => 'closed',
                    'source' => 'test',
                ],
            ],
        ], [
            'X-API-Key' => $this->plainKey,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'ok',
                'created' => 1,
                'duplicates_skipped' => 0,
            ]);

        $this->assertDatabaseHas('trade_entries', [
            'user_id' => $user->id,
            'external_id' => 'test_001',
            'source' => 'test',
        ]);
    }

    public function test_store_entries_deduplicates(): void
    {
        $user = User::factory()->create();

        $entry = [
            'user_id' => $user->id,
            'external_id' => 'dup_001',
            'symbol' => 'BTCUSD',
            'market' => 'crypto',
            'direction' => 'short',
            'entry_price' => 50000,
            'quantity' => 0.1,
            'opened_at' => '2026-01-15 10:00:00',
            'status' => 'open',
            'source' => 'test',
        ];

        // First request
        $this->postJson('/api/internal/journal/entries', [
            'entries' => [$entry],
        ], ['X-API-Key' => $this->plainKey]);

        // Second request (duplicate)
        $response = $this->postJson('/api/internal/journal/entries', [
            'entries' => [$entry],
        ], ['X-API-Key' => $this->plainKey]);

        $response->assertStatus(201)
            ->assertJson([
                'created' => 0,
                'duplicates_skipped' => 1,
            ]);
    }

    public function test_store_entries_validates_input(): void
    {
        $response = $this->postJson('/api/internal/journal/entries', [
            'entries' => [
                ['invalid' => 'data'],
            ],
        ], ['X-API-Key' => $this->plainKey]);

        $response->assertStatus(422)
            ->assertJson(['status' => 'error']);
    }

    public function test_store_entries_rejects_nonexistent_user(): void
    {
        $response = $this->postJson('/api/internal/journal/entries', [
            'entries' => [
                [
                    'user_id' => 99999,
                    'external_id' => 'test_002',
                    'symbol' => 'EURUSD',
                    'market' => 'forex',
                    'direction' => 'long',
                    'entry_price' => 1.0950,
                    'quantity' => 1.0,
                    'opened_at' => '2026-01-15 10:00:00',
                    'status' => 'open',
                    'source' => 'test',
                ],
            ],
        ], ['X-API-Key' => $this->plainKey]);

        $response->assertStatus(207)
            ->assertJson(['created' => 0]);
    }

    public function test_store_summaries_upserts(): void
    {
        $user = User::factory()->create();

        $summary = [
            'user_id' => $user->id,
            'period_type' => 'weekly',
            'period_start' => '2026-01-13',
            'period_end' => '2026-01-19',
            'total_trades' => 10,
            'winning_trades' => 6,
            'losing_trades' => 4,
            'win_rate' => 60.0,
            'total_pnl' => 150.50,
        ];

        $response = $this->postJson('/api/internal/journal/summaries', [
            'summaries' => [$summary],
        ], ['X-API-Key' => $this->plainKey]);

        $response->assertStatus(201)
            ->assertJson(['upserted' => 1]);

        // Upsert with updated data
        $summary['total_pnl'] = 200.00;
        $response = $this->postJson('/api/internal/journal/summaries', [
            'summaries' => [$summary],
        ], ['X-API-Key' => $this->plainKey]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('journal_summaries', [
            'user_id' => $user->id,
            'period_type' => 'weekly',
            'total_pnl' => 200.00,
        ]);
    }

    public function test_expired_key_is_rejected(): void
    {
        $this->apiKey->update(['expires_at' => now()->subDay()]);

        $response = $this->getJson('/api/internal/journal/health', [
            'X-API-Key' => $this->plainKey,
        ]);

        $response->assertUnauthorized()
            ->assertJson(['message' => 'API key has expired']);
    }

    public function test_inactive_key_is_rejected(): void
    {
        $this->apiKey->update(['is_active' => false]);

        $response = $this->getJson('/api/internal/journal/health', [
            'X-API-Key' => $this->plainKey,
        ]);

        $response->assertUnauthorized();
    }

    public function test_ip_restriction_blocks_unauthorized_ip(): void
    {
        $this->apiKey->update(['allowed_ips' => ['192.168.1.1']]);

        $response = $this->getJson('/api/internal/journal/health', [
            'X-API-Key' => $this->plainKey,
        ]);

        $response->assertForbidden()
            ->assertJson(['message' => 'IP not authorized']);
    }

    public function test_permission_check_blocks_unauthorized_action(): void
    {
        [$_, $readOnlyKey] = JournalApiKey::createKey('readonly', ['read:entries']);

        $user = User::factory()->create();

        $response = $this->postJson('/api/internal/journal/entries', [
            'entries' => [
                [
                    'user_id' => $user->id,
                    'external_id' => 'perm_test',
                    'symbol' => 'EURUSD',
                    'market' => 'forex',
                    'direction' => 'long',
                    'entry_price' => 1.0950,
                    'quantity' => 1.0,
                    'opened_at' => '2026-01-15 10:00:00',
                    'status' => 'open',
                    'source' => 'test',
                ],
            ],
        ], ['X-API-Key' => $readOnlyKey]);

        $response->assertForbidden()
            ->assertJson(['message' => 'Missing permission: write:entries']);
    }

    public function test_journal_disabled_returns_503(): void
    {
        config(['journal.enabled' => false]);

        $response = $this->getJson('/api/internal/journal/health', [
            'X-API-Key' => $this->plainKey,
        ]);

        $response->assertStatus(503)
            ->assertJson(['status' => 'unavailable']);
    }
}
