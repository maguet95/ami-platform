<?php

namespace Database\Seeders;

use App\Models\TradePair;
use Illuminate\Database\Seeder;

class TradePairSeeder extends Seeder
{
    public function run(): void
    {
        $pairs = [
            // Crypto
            ['symbol' => 'BTCUSDT', 'market' => 'crypto', 'display_name' => 'Bitcoin/USDT'],
            ['symbol' => 'ETHUSDT', 'market' => 'crypto', 'display_name' => 'Ethereum/USDT'],
            ['symbol' => 'BNBUSDT', 'market' => 'crypto', 'display_name' => 'BNB/USDT'],
            ['symbol' => 'SOLUSDT', 'market' => 'crypto', 'display_name' => 'Solana/USDT'],
            ['symbol' => 'XRPUSDT', 'market' => 'crypto', 'display_name' => 'XRP/USDT'],
            ['symbol' => 'ADAUSDT', 'market' => 'crypto', 'display_name' => 'Cardano/USDT'],
            ['symbol' => 'DOGEUSDT', 'market' => 'crypto', 'display_name' => 'Dogecoin/USDT'],
            ['symbol' => 'DOTUSDT', 'market' => 'crypto', 'display_name' => 'Polkadot/USDT'],
            ['symbol' => 'AVAXUSDT', 'market' => 'crypto', 'display_name' => 'Avalanche/USDT'],
            ['symbol' => 'LINKUSDT', 'market' => 'crypto', 'display_name' => 'Chainlink/USDT'],

            // Forex
            ['symbol' => 'EURUSD', 'market' => 'forex', 'display_name' => 'Euro/Dolar'],
            ['symbol' => 'GBPUSD', 'market' => 'forex', 'display_name' => 'Libra/Dolar'],
            ['symbol' => 'USDJPY', 'market' => 'forex', 'display_name' => 'Dolar/Yen'],
            ['symbol' => 'USDCAD', 'market' => 'forex', 'display_name' => 'Dolar/CAD'],
            ['symbol' => 'AUDUSD', 'market' => 'forex', 'display_name' => 'AUD/Dolar'],
            ['symbol' => 'XAUUSD', 'market' => 'forex', 'display_name' => 'Oro/Dolar'],

            // Stocks / Indices
            ['symbol' => 'SPX500', 'market' => 'stocks', 'display_name' => 'S&P 500'],
            ['symbol' => 'NAS100', 'market' => 'stocks', 'display_name' => 'Nasdaq 100'],
            ['symbol' => 'US30', 'market' => 'stocks', 'display_name' => 'Dow Jones 30'],
            ['symbol' => 'DAX40', 'market' => 'stocks', 'display_name' => 'DAX 40'],
            ['symbol' => 'FTSE100', 'market' => 'stocks', 'display_name' => 'FTSE 100'],
            ['symbol' => 'NIKKEI225', 'market' => 'stocks', 'display_name' => 'Nikkei 225'],

            // Commodities
            ['symbol' => 'XAGUSD', 'market' => 'commodities', 'display_name' => 'Plata/Dolar'],
            ['symbol' => 'WTIUSD', 'market' => 'commodities', 'display_name' => 'Petroleo WTI'],
            ['symbol' => 'BRENTUSD', 'market' => 'commodities', 'display_name' => 'Petroleo Brent'],
            ['symbol' => 'NATGASUSD', 'market' => 'commodities', 'display_name' => 'Gas Natural'],
        ];

        foreach ($pairs as $pair) {
            TradePair::firstOrCreate(
                ['symbol' => $pair['symbol'], 'market' => $pair['market']],
                ['display_name' => $pair['display_name']]
            );
        }
    }
}
