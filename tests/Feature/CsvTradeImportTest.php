<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CsvTradeImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CsvTradeImportTest extends TestCase
{
    use RefreshDatabase;

    private CsvTradeImporter $importer;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importer = new CsvTradeImporter;
        $this->user = User::factory()->create();
    }

    public function test_mt5_csv_import_creates_entries(): void
    {
        $csv = "Ticket\tOpen Time\tType\tVolume\tSymbol\tPrice\tClose Price\tClose Time\tCommission\tSwap\tProfit\n";
        $csv .= "12345\t2026-01-15 10:00:00\tBuy\t1.0\tEURUSD\t1.0950\t1.1000\t2026-01-15 14:00:00\t-2.50\t-0.30\t50.00\n";

        $file = UploadedFile::fake()->createWithContent('trades.csv', $csv);

        $result = $this->importer->import($file, $this->user->id, 'mt5');

        $this->assertEquals(1, $result->created);
        $this->assertEquals(0, $result->duplicates);
        $this->assertEmpty($result->errors);

        $this->assertDatabaseHas('trade_entries', [
            'user_id' => $this->user->id,
            'external_id' => 'mt5_12345',
            'direction' => 'long',
            'source' => 'csv_mt5',
        ]);
    }

    public function test_mt5_csv_deduplicates_trades(): void
    {
        $csv = "Ticket\tOpen Time\tType\tVolume\tSymbol\tPrice\tProfit\n";
        $csv .= "12345\t2026-01-15 10:00:00\tBuy\t1.0\tEURUSD\t1.0950\t50.00\n";

        $file = UploadedFile::fake()->createWithContent('trades.csv', $csv);

        $this->importer->import($file, $this->user->id, 'mt5');

        // Import again
        $file2 = UploadedFile::fake()->createWithContent('trades2.csv', $csv);
        $result = $this->importer->import($file2, $this->user->id, 'mt5');

        $this->assertEquals(0, $result->created);
        $this->assertEquals(1, $result->duplicates);
    }

    public function test_mt5_csv_handles_sell_direction(): void
    {
        $csv = "Ticket\tOpen Time\tType\tVolume\tSymbol\tPrice\tProfit\n";
        $csv .= "12346\t2026-01-15 10:00:00\tSell\t0.5\tGBPUSD\t1.2500\t-25.00\n";

        $file = UploadedFile::fake()->createWithContent('trades.csv', $csv);

        $result = $this->importer->import($file, $this->user->id, 'mt5');

        $this->assertEquals(1, $result->created);
        $this->assertDatabaseHas('trade_entries', [
            'direction' => 'short',
            'external_id' => 'mt5_12346',
        ]);
    }

    public function test_empty_csv_returns_error(): void
    {
        $file = UploadedFile::fake()->createWithContent('empty.csv', '');

        $result = $this->importer->import($file, $this->user->id, 'mt5');

        $this->assertEquals(0, $result->created);
        $this->assertNotEmpty($result->errors);
    }

    public function test_unrecognized_columns_returns_error(): void
    {
        $csv = "Col1\tCol2\tCol3\n";
        $csv .= "val1\tval2\tval3\n";

        $file = UploadedFile::fake()->createWithContent('bad.csv', $csv);

        $result = $this->importer->import($file, $this->user->id, 'mt5');

        $this->assertEquals(0, $result->created);
        $this->assertNotEmpty($result->errors);
    }

    public function test_unsupported_format_returns_error(): void
    {
        $file = UploadedFile::fake()->createWithContent('test.csv', 'data');

        $result = $this->importer->import($file, $this->user->id, 'unknown');

        $this->assertEquals(0, $result->created);
        $this->assertNotEmpty($result->errors);
    }

    public function test_mt4_html_import_creates_entries(): void
    {
        $html = '<html><body><table>
            <tr><td colspan="14">Closed Transactions:</td></tr>
            <tr><td>Ticket</td><td>Open Time</td><td>Type</td><td>Size</td><td>Item</td><td>Price</td><td>S/L</td><td>T/P</td><td>Close Time</td><td>Price</td><td>Commission</td><td>Taxes</td><td>Swap</td><td>Profit</td></tr>
            <tr><td>11111</td><td>2026-01-15 10:00:00</td><td>buy</td><td>1.00</td><td>EURUSD</td><td>1.0950</td><td>0</td><td>0</td><td>2026-01-15 14:00:00</td><td>1.1000</td><td>-2.50</td><td>0</td><td>-0.30</td><td>50.00</td></tr>
        </table></body></html>';

        $file = UploadedFile::fake()->createWithContent('statement.htm', $html);

        $result = $this->importer->import($file, $this->user->id, 'mt4');

        $this->assertEquals(1, $result->created);
        $this->assertDatabaseHas('trade_entries', [
            'user_id' => $this->user->id,
            'external_id' => 'mt4_11111',
            'source' => 'csv_mt4',
        ]);
    }

    public function test_mt4_html_without_closed_transactions_returns_error(): void
    {
        $html = '<html><body><table><tr><td>No trades</td></tr></table></body></html>';

        $file = UploadedFile::fake()->createWithContent('empty.htm', $html);

        $result = $this->importer->import($file, $this->user->id, 'mt4');

        $this->assertEquals(0, $result->created);
        $this->assertNotEmpty($result->errors);
    }
}
