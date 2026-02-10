<?php

namespace Database\Seeders;

use App\Models\TradeEntry;
use App\Models\TradePair;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TradeEntrySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@ami.com')->first();
        if (! $admin) {
            $this->command->warn('Admin user not found. Skipping TradeEntrySeeder.');
            return;
        }

        if (TradePair::count() === 0) {
            $this->call(TradePairSeeder::class);
        }

        $pairs = TradePair::all();
        $now = Carbon::now();
        $sources = ['metatrader5', 'metatrader5', 'metatrader5', 'binance', 'binance'];

        // ~55% win, ~30% loss, ~15% breakeven
        $results = array_merge(
            array_fill(0, 28, 'win'),
            array_fill(0, 15, 'loss'),
            array_fill(0, 7, 'breakeven')
        );
        shuffle($results);

        for ($i = 0; $i < 50; $i++) {
            $pair = $pairs->random();
            $daysAgo = rand(1, 90);
            $openedAt = $now->copy()->subDays($daysAgo)->setHour(rand(6, 22))->setMinute(rand(0, 59));
            $durationSeconds = rand(300, 86400 * 3); // 5 min to 3 days
            $closedAt = $openedAt->copy()->addSeconds($durationSeconds);
            $direction = rand(0, 1) ? 'long' : 'short';
            $result = $results[$i];
            $source = $sources[array_rand($sources)];

            $basePrice = match ($pair->market) {
                'crypto' => match (true) {
                    str_contains($pair->symbol, 'BTC') => rand(38000, 48000) + rand(0, 99) / 100,
                    str_contains($pair->symbol, 'ETH') => rand(2200, 3500) + rand(0, 99) / 100,
                    str_contains($pair->symbol, 'SOL') => rand(80, 180) + rand(0, 99) / 100,
                    default => rand(1, 600) + rand(0, 99) / 100,
                },
                'forex' => match (true) {
                    str_contains($pair->symbol, 'XAU') => rand(1950, 2150) + rand(0, 99) / 100,
                    str_contains($pair->symbol, 'JPY') => rand(140, 155) + rand(0, 999) / 1000,
                    default => rand(0, 1) + rand(5000, 9999) / 10000,
                },
                'stocks' => match (true) {
                    str_contains($pair->symbol, 'SPX') => rand(4800, 5300) + rand(0, 99) / 100,
                    str_contains($pair->symbol, 'NAS') => rand(16500, 18500) + rand(0, 99) / 100,
                    str_contains($pair->symbol, 'US30') => rand(37000, 40000) + rand(0, 99) / 100,
                    default => rand(5000, 20000) + rand(0, 99) / 100,
                },
                default => rand(20, 100) + rand(0, 99) / 100,
            };

            $entryPrice = round($basePrice, 2);
            $priceDelta = $entryPrice * (rand(3, 25) / 1000);

            if ($result === 'win') {
                $exitPrice = $direction === 'long'
                    ? round($entryPrice + $priceDelta, 2)
                    : round($entryPrice - $priceDelta, 2);
                $pnl = round(rand(30, 1000) + rand(0, 99) / 100, 2);
                $pnlPct = round(rand(3, 60) / 10, 2);
            } elseif ($result === 'loss') {
                $exitPrice = $direction === 'long'
                    ? round($entryPrice - $priceDelta, 2)
                    : round($entryPrice + $priceDelta, 2);
                $pnl = round(-(rand(20, 450) + rand(0, 99) / 100), 2);
                $pnlPct = round(-(rand(2, 40) / 10), 2);
            } else {
                $exitPrice = $entryPrice;
                $pnl = 0;
                $pnlPct = 0;
            }

            $quantity = round(rand(1, 100) / 10, 4);
            $fee = round(rand(1, 20) / 10, 2);

            TradeEntry::create([
                'user_id' => $admin->id,
                'trade_pair_id' => $pair->id,
                'external_id' => $source . '_' . strtolower($pair->symbol) . '_' . $openedAt->timestamp,
                'direction' => $direction,
                'entry_price' => $entryPrice,
                'exit_price' => $exitPrice,
                'quantity' => $quantity,
                'pnl' => $pnl,
                'pnl_percentage' => $pnlPct,
                'fee' => $fee,
                'opened_at' => $openedAt,
                'closed_at' => $closedAt,
                'duration_seconds' => $durationSeconds,
                'status' => 'closed',
                'source' => $source,
                'tags' => rand(0, 100) > 60 ? ['swing', 'trend'] : null,
                'notes' => rand(0, 100) > 70 ? 'Operacion importada automaticamente.' : null,
            ]);
        }

        $this->command->info('Created 50 automatic trade entries for admin user.');
    }
}
