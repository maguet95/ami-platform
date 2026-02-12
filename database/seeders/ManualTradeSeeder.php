<?php

namespace Database\Seeders;

use App\Models\ManualTrade;
use App\Models\TradePair;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ManualTradeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'enmajose95+admin@gmail.com')->first();
        if (! $admin) {
            $this->command->warn('Admin user not found. Skipping ManualTradeSeeder.');
            return;
        }

        // Ensure trade pairs exist
        if (TradePair::count() === 0) {
            $this->call(TradePairSeeder::class);
        }

        $pairs = TradePair::all();
        $now = Carbon::now();

        $emotions = ['calm', 'confident', 'anxious', 'fearful', 'greedy', 'frustrated', 'euphoric', 'neutral'];
        $timeframes = ['5m', '15m', '30m', '1h', '4h', '1d'];
        $sessions = ['asian', 'london', 'new_york', 'overlap'];
        $marketConditions = ['trending_up', 'trending_down', 'ranging', 'volatile', 'low_volume'];
        $mistakes = ['no_plan', 'moved_stop', 'oversize', 'revenge_trade', 'fomo', 'early_exit', 'late_entry', 'ignored_levels', 'no_confluence', 'emotional'];

        $entryReasons = [
            'Ruptura de estructura en HTF con confirmacion en LTF. OB + FVG confluencia.',
            'Rechazo en zona de demanda diaria. Vela envolvente alcista en 4H.',
            'Divergencia RSI en soporte clave. Volumen decreciente en la caida.',
            'BOS alcista en 15m despues de sweep de liquidez en el bajo del dia anterior.',
            'Patron armonico Gartley completado en zona de OB semanal.',
            'Media movil de 200 como soporte dinamico + patron de doble piso.',
            'Canal descendente roto al alza con aumento de volumen significativo.',
            'Entrada en retroceso 61.8% Fibonacci con confluencia de S/R.',
        ];

        $lessons = [
            'Respetar el plan es mas importante que el resultado individual.',
            'No perseguir el precio despues de perder la entrada original.',
            'La paciencia en la espera del setup paga mas que la accion impulsiva.',
            'Reducir tamano de posicion en sesiones de baja volatilidad.',
            'Confirmar en multiples temporalidades antes de ejecutar.',
            'Mover SL a breakeven despues de 1R a favor es clave para proteger capital.',
            'Las mejores entradas vienen despues de periodos de consolidacion.',
            'Journaling consistente mejora la toma de decisiones enormemente.',
        ];

        // Distribution: ~27 winners, ~15 losers, ~8 breakeven = 50 trades
        $results = array_merge(
            array_fill(0, 27, 'win'),
            array_fill(0, 15, 'loss'),
            array_fill(0, 8, 'breakeven')
        );
        shuffle($results);

        for ($i = 0; $i < 50; $i++) {
            $pair = $pairs->random();
            $daysAgo = rand(1, 90);
            $tradeDate = $now->copy()->subDays($daysAgo);
            $direction = rand(0, 1) ? 'long' : 'short';
            $result = $results[$i];

            // Realistic prices based on market
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

            $entryPrice = round($basePrice, $pair->market === 'forex' && !str_contains($pair->symbol, 'XAU') ? 5 : 2);

            // Calculate exit based on result
            $priceDelta = $entryPrice * (rand(5, 30) / 1000); // 0.5% - 3%
            if ($result === 'win') {
                $exitPrice = $direction === 'long'
                    ? round($entryPrice + $priceDelta, 2)
                    : round($entryPrice - $priceDelta, 2);
                $pnl = round(rand(50, 1200) + rand(0, 99) / 100, 2);
                $pnlPct = round(rand(5, 80) / 10, 2);
            } elseif ($result === 'loss') {
                $exitPrice = $direction === 'long'
                    ? round($entryPrice - $priceDelta, 2)
                    : round($entryPrice + $priceDelta, 2);
                $pnl = round(-(rand(30, 500) + rand(0, 99) / 100), 2);
                $pnlPct = round(-(rand(3, 50) / 10), 2);
            } else {
                $exitPrice = $entryPrice;
                $pnl = 0;
                $pnlPct = 0;
            }

            // SL and TP
            $slDistance = $entryPrice * (rand(5, 20) / 1000);
            $tpDistance = $entryPrice * (rand(10, 50) / 1000);
            $stopLoss = $direction === 'long'
                ? round($entryPrice - $slDistance, 2)
                : round($entryPrice + $slDistance, 2);
            $takeProfit = $direction === 'long'
                ? round($entryPrice + $tpDistance, 2)
                : round($entryPrice - $tpDistance, 2);

            $rrPlanned = round($tpDistance / max($slDistance, 0.01), 2);
            $rrActual = $pnl != 0 ? round(abs($pnl) / max(rand(30, 200), 1), 2) : 0;
            if ($pnl < 0) $rrActual = -$rrActual;

            $positionSize = round(rand(1, 50) / 10, 2);
            $commission = round(rand(1, 15) / 10, 2);

            $hadPlan = rand(0, 100) > 15;
            $planFollowed = $hadPlan ? rand(2, 5) : rand(1, 3);
            $confidenceLevel = rand(1, 5);
            $stressLevel = rand(1, 5);
            $overallRating = $result === 'win' ? rand(3, 5) : ($result === 'loss' ? rand(1, 4) : rand(2, 4));

            $tradeMistakes = null;
            if ($result === 'loss' || rand(0, 100) > 70) {
                $keys = (array) array_rand($mistakes, rand(1, 3));
                $tradeMistakes = array_values(array_intersect_key($mistakes, array_flip($keys)));
            }

            ManualTrade::create([
                'user_id' => $admin->id,
                'trade_pair_id' => $pair->id,
                'direction' => $direction,
                'trade_date' => $tradeDate,
                'timeframe' => $timeframes[array_rand($timeframes)],
                'session' => $sessions[array_rand($sessions)],
                'entry_price' => $entryPrice,
                'exit_price' => $exitPrice,
                'stop_loss' => $stopLoss,
                'take_profit' => $takeProfit,
                'position_size' => $positionSize,
                'risk_reward_planned' => $rrPlanned,
                'risk_reward_actual' => $rrActual,
                'pnl' => $pnl,
                'pnl_percentage' => $pnlPct,
                'commission' => $commission,
                'status' => 'closed',
                'had_plan' => $hadPlan,
                'plan_followed' => $planFollowed,
                'entry_reason' => $entryReasons[array_rand($entryReasons)],
                'invalidation_criteria' => 'Cierre por debajo/encima del nivel clave marcado.',
                'mistakes' => $tradeMistakes,
                'lessons_learned' => rand(0, 100) > 40 ? $lessons[array_rand($lessons)] : null,
                'emotion_before' => $emotions[array_rand($emotions)],
                'emotion_during' => $emotions[array_rand($emotions)],
                'emotion_after' => $result === 'win'
                    ? ['calm', 'confident', 'euphoric'][rand(0, 2)]
                    : ['frustrated', 'anxious', 'neutral'][rand(0, 2)],
                'confidence_level' => $confidenceLevel,
                'stress_level' => $stressLevel,
                'psychology_notes' => rand(0, 100) > 60 ? 'Me senti en control durante la operacion.' : null,
                'market_condition' => $marketConditions[array_rand($marketConditions)],
                'key_levels' => rand(0, 100) > 50 ? 'Soporte en ' . round($entryPrice * 0.98, 2) . ', Resistencia en ' . round($entryPrice * 1.02, 2) : null,
                'what_i_did_well' => rand(0, 100) > 50 ? 'Segui el plan y respete el stop loss.' : null,
                'what_to_improve' => rand(0, 100) > 50 ? 'Mejorar el timing de entrada esperando confirmacion.' : null,
                'would_take_again' => $result !== 'loss' || rand(0, 1),
                'overall_rating' => $overallRating,
                'notes' => rand(0, 100) > 70 ? 'Trade ejecutado segun el plan de la sesion.' : null,
            ]);
        }

        $this->command->info('Created 50 manual trades for admin user.');
    }
}
