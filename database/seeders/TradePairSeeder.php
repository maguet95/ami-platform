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

            // Forex — Majors
            ['symbol' => 'EURUSD', 'market' => 'forex', 'display_name' => 'EUR/USD'],
            ['symbol' => 'GBPUSD', 'market' => 'forex', 'display_name' => 'GBP/USD'],
            ['symbol' => 'USDJPY', 'market' => 'forex', 'display_name' => 'USD/JPY'],
            ['symbol' => 'USDCHF', 'market' => 'forex', 'display_name' => 'USD/CHF'],
            ['symbol' => 'USDCAD', 'market' => 'forex', 'display_name' => 'USD/CAD'],
            ['symbol' => 'AUDUSD', 'market' => 'forex', 'display_name' => 'AUD/USD'],
            ['symbol' => 'NZDUSD', 'market' => 'forex', 'display_name' => 'NZD/USD'],

            // Forex — Minors (Crosses)
            ['symbol' => 'EURGBP', 'market' => 'forex', 'display_name' => 'EUR/GBP'],
            ['symbol' => 'EURJPY', 'market' => 'forex', 'display_name' => 'EUR/JPY'],
            ['symbol' => 'EURCHF', 'market' => 'forex', 'display_name' => 'EUR/CHF'],
            ['symbol' => 'EURAUD', 'market' => 'forex', 'display_name' => 'EUR/AUD'],
            ['symbol' => 'EURNZD', 'market' => 'forex', 'display_name' => 'EUR/NZD'],
            ['symbol' => 'EURCAD', 'market' => 'forex', 'display_name' => 'EUR/CAD'],
            ['symbol' => 'GBPJPY', 'market' => 'forex', 'display_name' => 'GBP/JPY'],
            ['symbol' => 'GBPCHF', 'market' => 'forex', 'display_name' => 'GBP/CHF'],
            ['symbol' => 'GBPAUD', 'market' => 'forex', 'display_name' => 'GBP/AUD'],
            ['symbol' => 'GBPNZD', 'market' => 'forex', 'display_name' => 'GBP/NZD'],
            ['symbol' => 'GBPCAD', 'market' => 'forex', 'display_name' => 'GBP/CAD'],
            ['symbol' => 'AUDJPY', 'market' => 'forex', 'display_name' => 'AUD/JPY'],
            ['symbol' => 'AUDNZD', 'market' => 'forex', 'display_name' => 'AUD/NZD'],
            ['symbol' => 'AUDCAD', 'market' => 'forex', 'display_name' => 'AUD/CAD'],
            ['symbol' => 'AUDCHF', 'market' => 'forex', 'display_name' => 'AUD/CHF'],
            ['symbol' => 'NZDJPY', 'market' => 'forex', 'display_name' => 'NZD/JPY'],
            ['symbol' => 'NZDCAD', 'market' => 'forex', 'display_name' => 'NZD/CAD'],
            ['symbol' => 'NZDCHF', 'market' => 'forex', 'display_name' => 'NZD/CHF'],
            ['symbol' => 'CHFJPY', 'market' => 'forex', 'display_name' => 'CHF/JPY'],
            ['symbol' => 'CADJPY', 'market' => 'forex', 'display_name' => 'CAD/JPY'],
            ['symbol' => 'CADCHF', 'market' => 'forex', 'display_name' => 'CAD/CHF'],

            // Forex — Exoticos
            ['symbol' => 'USDMXN', 'market' => 'forex', 'display_name' => 'USD/MXN'],
            ['symbol' => 'USDZAR', 'market' => 'forex', 'display_name' => 'USD/ZAR'],
            ['symbol' => 'USDTRY', 'market' => 'forex', 'display_name' => 'USD/TRY'],
            ['symbol' => 'USDSGD', 'market' => 'forex', 'display_name' => 'USD/SGD'],
            ['symbol' => 'USDHKD', 'market' => 'forex', 'display_name' => 'USD/HKD'],
            ['symbol' => 'USDNOK', 'market' => 'forex', 'display_name' => 'USD/NOK'],
            ['symbol' => 'USDSEK', 'market' => 'forex', 'display_name' => 'USD/SEK'],
            ['symbol' => 'USDDKK', 'market' => 'forex', 'display_name' => 'USD/DKK'],
            ['symbol' => 'USDPLN', 'market' => 'forex', 'display_name' => 'USD/PLN'],
            ['symbol' => 'USDCNH', 'market' => 'forex', 'display_name' => 'USD/CNH'],
            ['symbol' => 'EURTRY', 'market' => 'forex', 'display_name' => 'EUR/TRY'],
            ['symbol' => 'EURNOK', 'market' => 'forex', 'display_name' => 'EUR/NOK'],
            ['symbol' => 'EURSEK', 'market' => 'forex', 'display_name' => 'EUR/SEK'],
            ['symbol' => 'EURPLN', 'market' => 'forex', 'display_name' => 'EUR/PLN'],
            ['symbol' => 'GBPZAR', 'market' => 'forex', 'display_name' => 'GBP/ZAR'],
            ['symbol' => 'GBPTRY', 'market' => 'forex', 'display_name' => 'GBP/TRY'],
            ['symbol' => 'GBPNOK', 'market' => 'forex', 'display_name' => 'GBP/NOK'],
            ['symbol' => 'GBPSEK', 'market' => 'forex', 'display_name' => 'GBP/SEK'],

            // Metales
            ['symbol' => 'XAUUSD', 'market' => 'forex', 'display_name' => 'XAU/USD (Oro)'],

            // Indices — US
            ['symbol' => 'SPX500', 'market' => 'stocks', 'display_name' => 'S&P 500'],
            ['symbol' => 'NAS100', 'market' => 'stocks', 'display_name' => 'Nasdaq 100'],
            ['symbol' => 'US30', 'market' => 'stocks', 'display_name' => 'Dow Jones 30'],
            ['symbol' => 'US2000', 'market' => 'stocks', 'display_name' => 'Russell 2000'],
            ['symbol' => 'VIX', 'market' => 'stocks', 'display_name' => 'VIX (Volatilidad)'],

            // Indices — Europa
            ['symbol' => 'DAX40', 'market' => 'stocks', 'display_name' => 'DAX 40 (Alemania)'],
            ['symbol' => 'FTSE100', 'market' => 'stocks', 'display_name' => 'FTSE 100 (UK)'],
            ['symbol' => 'CAC40', 'market' => 'stocks', 'display_name' => 'CAC 40 (Francia)'],
            ['symbol' => 'IBEX35', 'market' => 'stocks', 'display_name' => 'IBEX 35 (Espana)'],
            ['symbol' => 'STOXX50', 'market' => 'stocks', 'display_name' => 'Euro Stoxx 50'],
            ['symbol' => 'SMI20', 'market' => 'stocks', 'display_name' => 'SMI (Suiza)'],

            // Indices — Asia / Oceania
            ['symbol' => 'NIKKEI225', 'market' => 'stocks', 'display_name' => 'Nikkei 225 (Japon)'],
            ['symbol' => 'HSI', 'market' => 'stocks', 'display_name' => 'Hang Seng (Hong Kong)'],
            ['symbol' => 'ASX200', 'market' => 'stocks', 'display_name' => 'ASX 200 (Australia)'],
            ['symbol' => 'CSI300', 'market' => 'stocks', 'display_name' => 'CSI 300 (China)'],
            ['symbol' => 'KOSPI', 'market' => 'stocks', 'display_name' => 'KOSPI (Corea)'],
            ['symbol' => 'SENSEX', 'market' => 'stocks', 'display_name' => 'SENSEX (India)'],

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
