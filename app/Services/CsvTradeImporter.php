<?php

namespace App\Services;

use App\Models\TradeEntry;
use App\Models\TradePair;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CsvTradeImporter
{
    /**
     * Import trades from an uploaded file.
     */
    public function import(UploadedFile $file, int $userId, string $format): ImportResult
    {
        return match ($format) {
            'mt4' => $this->parseMt4Html($file, $userId),
            'mt5' => $this->parseMt5Csv($file, $userId),
            default => new ImportResult(errors: ["Formato no soportado: {$format}"]),
        };
    }

    /**
     * Parse MT4 HTML statement report.
     */
    private function parseMt4Html(UploadedFile $file, int $userId): ImportResult
    {
        $result = new ImportResult();
        $html = file_get_contents($file->getRealPath());

        $doc = new DOMDocument();
        @$doc->loadHTML($html, LIBXML_NOERROR);
        $xpath = new DOMXPath($doc);

        // Find the "Closed Transactions" table
        $tables = $xpath->query('//table');
        $tradesTable = null;

        foreach ($tables as $table) {
            $text = $table->textContent;
            if (str_contains($text, 'Closed Transactions') || str_contains($text, 'Closed P/L')) {
                $tradesTable = $table;
                break;
            }
        }

        if (! $tradesTable) {
            $result->errors[] = 'No se encontro la tabla de transacciones cerradas en el reporte MT4.';
            return $result;
        }

        $rows = $xpath->query('.//tr', $tradesTable);

        foreach ($rows as $row) {
            $cells = $xpath->query('.//td', $row);
            if ($cells->length < 13) {
                continue;
            }

            $values = [];
            foreach ($cells as $cell) {
                $values[] = trim($cell->textContent);
            }

            // MT4 columns: Ticket, Open Time, Type, Size, Item, Price, S/L, T/P, Close Time, Price, Commission, Taxes, Swap, Profit
            $ticket = $values[0] ?? '';
            if (! is_numeric($ticket)) {
                continue;
            }

            $type = strtolower($values[2] ?? '');
            if (! in_array($type, ['buy', 'sell'])) {
                continue;
            }

            $symbol = $values[4] ?? '';
            $entryPrice = (float) ($values[5] ?? 0);
            $exitPrice = (float) ($values[9] ?? 0);
            $quantity = (float) ($values[3] ?? 0);
            $profit = (float) ($values[13] ?? $values[12] ?? 0);
            $commission = (float) ($values[10] ?? 0);
            $swap = (float) ($values[12] ?? $values[11] ?? 0);

            try {
                $openedAt = Carbon::parse($values[1]);
                $closedAt = Carbon::parse($values[8]);
            } catch (\Exception $e) {
                $result->errors[] = "Ticket {$ticket}: fecha invalida";
                continue;
            }

            $this->upsertTrade($result, $userId, [
                'external_id' => "mt4_{$ticket}",
                'symbol' => $symbol,
                'market' => 'forex',
                'direction' => $type === 'buy' ? 'long' : 'short',
                'entry_price' => $entryPrice,
                'exit_price' => $exitPrice,
                'quantity' => $quantity,
                'pnl' => $profit + $commission + $swap,
                'fee' => abs($commission) + abs($swap),
                'opened_at' => $openedAt,
                'closed_at' => $closedAt,
                'duration_seconds' => $closedAt->diffInSeconds($openedAt),
                'status' => 'closed',
                'source' => 'csv_mt4',
            ]);
        }

        Log::info('CSV import: MT4', ['user_id' => $userId, 'created' => $result->created, 'duplicates' => $result->duplicates]);

        return $result;
    }

    /**
     * Parse MT5 CSV export (tab or comma separated).
     */
    private function parseMt5Csv(UploadedFile $file, int $userId): ImportResult
    {
        $result = new ImportResult();
        $content = file_get_contents($file->getRealPath());
        $lines = explode("\n", $content);

        if (count($lines) < 2) {
            $result->errors[] = 'Archivo vacio o sin datos.';
            return $result;
        }

        // Detect separator
        $separator = str_contains($lines[0], "\t") ? "\t" : ',';

        // Parse header
        $headers = str_getcsv($lines[0], $separator);
        $headers = array_map(fn ($h) => strtolower(trim($h)), $headers);

        // Map column names (MT5 exports vary by language)
        $colMap = $this->mapMt5Columns($headers);

        if (! $colMap) {
            $result->errors[] = 'No se reconocen las columnas del CSV. Asegurate de exportar desde MetaTrader 5.';
            return $result;
        }

        for ($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (empty($line)) {
                continue;
            }

            $values = str_getcsv($line, $separator);
            $ticket = $values[$colMap['ticket']] ?? '';

            if (! is_numeric($ticket)) {
                continue;
            }

            $type = strtolower($values[$colMap['type']] ?? '');
            if (! in_array($type, ['buy', 'sell'])) {
                continue;
            }

            $symbol = $values[$colMap['symbol']] ?? '';
            $entryPrice = (float) ($values[$colMap['price']] ?? 0);
            $exitPrice = isset($colMap['price_close']) ? (float) ($values[$colMap['price_close']] ?? 0) : $entryPrice;
            $quantity = (float) ($values[$colMap['volume']] ?? 0);
            $profit = (float) ($values[$colMap['profit']] ?? 0);
            $commission = isset($colMap['commission']) ? (float) ($values[$colMap['commission']] ?? 0) : 0;
            $swap = isset($colMap['swap']) ? (float) ($values[$colMap['swap']] ?? 0) : 0;

            try {
                $openedAt = Carbon::parse($values[$colMap['time']] ?? '');
                $closedAt = isset($colMap['time_close']) ? Carbon::parse($values[$colMap['time_close']] ?? '') : $openedAt;
            } catch (\Exception $e) {
                $result->errors[] = "Linea {$i}: fecha invalida";
                continue;
            }

            $this->upsertTrade($result, $userId, [
                'external_id' => "mt5_{$ticket}",
                'symbol' => $symbol,
                'market' => 'forex',
                'direction' => $type === 'buy' ? 'long' : 'short',
                'entry_price' => $entryPrice,
                'exit_price' => $exitPrice,
                'quantity' => $quantity,
                'pnl' => $profit + $commission + $swap,
                'fee' => abs($commission) + abs($swap),
                'opened_at' => $openedAt,
                'closed_at' => $closedAt,
                'duration_seconds' => $closedAt->diffInSeconds($openedAt),
                'status' => 'closed',
                'source' => 'csv_mt5',
            ]);
        }

        Log::info('CSV import: MT5', ['user_id' => $userId, 'created' => $result->created, 'duplicates' => $result->duplicates]);

        return $result;
    }

    /**
     * Try to map MT5 column headers (supports English/Spanish variants).
     */
    private function mapMt5Columns(array $headers): ?array
    {
        $map = [];
        $mappings = [
            'ticket' => ['ticket', 'order', 'orden', 'deal', 'position'],
            'time' => ['time', 'open time', 'fecha', 'tiempo', 'open date'],
            'type' => ['type', 'tipo', 'direction'],
            'volume' => ['volume', 'volumen', 'lots', 'size', 'lotes'],
            'symbol' => ['symbol', 'simbolo', 'item', 'instrument'],
            'price' => ['price', 'precio', 'open price', 'entry price'],
            'profit' => ['profit', 'beneficio', 'ganancia', 'p/l', 'pnl'],
        ];

        $optional = [
            'price_close' => ['close price', 'precio cierre', 's / l'],
            'time_close' => ['close time', 'fecha cierre', 'close date'],
            'commission' => ['commission', 'comision'],
            'swap' => ['swap'],
        ];

        foreach ($mappings as $key => $candidates) {
            $found = false;
            foreach ($candidates as $candidate) {
                $idx = array_search($candidate, $headers);
                if ($idx !== false) {
                    $map[$key] = $idx;
                    $found = true;
                    break;
                }
            }
            if (! $found) {
                return null;
            }
        }

        foreach ($optional as $key => $candidates) {
            foreach ($candidates as $candidate) {
                $idx = array_search($candidate, $headers);
                if ($idx !== false) {
                    $map[$key] = $idx;
                    break;
                }
            }
        }

        return $map;
    }

    /**
     * Create a trade entry if it doesn't already exist (dedup by external_id + source).
     */
    private function upsertTrade(ImportResult $result, int $userId, array $data): void
    {
        try {
            $exists = TradeEntry::where('user_id', $userId)
                ->where('external_id', $data['external_id'])
                ->where('source', $data['source'])
                ->exists();

            if ($exists) {
                $result->duplicates++;
                return;
            }

            $tradePair = TradePair::firstOrCreate(
                ['symbol' => $data['symbol'], 'market' => $data['market']],
                ['display_name' => $data['symbol']]
            );

            TradeEntry::create([
                'user_id' => $userId,
                'trade_pair_id' => $tradePair->id,
                'external_id' => $data['external_id'],
                'direction' => $data['direction'],
                'entry_price' => $data['entry_price'],
                'exit_price' => $data['exit_price'],
                'quantity' => $data['quantity'],
                'pnl' => $data['pnl'],
                'pnl_percentage' => null,
                'fee' => $data['fee'],
                'opened_at' => $data['opened_at'],
                'closed_at' => $data['closed_at'],
                'duration_seconds' => $data['duration_seconds'],
                'status' => $data['status'],
                'source' => $data['source'],
            ]);

            $result->created++;
        } catch (\Exception $e) {
            $result->errors[] = "{$data['external_id']}: {$e->getMessage()}";
        }
    }
}
