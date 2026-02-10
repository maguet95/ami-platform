<?php

namespace App\Exports;

use App\Models\ManualTrade;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ManualTradesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private Collection $trades;

    public function __construct(Collection $trades)
    {
        $this->trades = $trades;
    }

    public function collection(): Collection
    {
        return $this->trades;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Par',
            'Direccion',
            'Entrada',
            'Salida',
            'SL',
            'TP',
            'P&L ($)',
            'P&L (%)',
            'RR Actual',
            'Rating',
            'Emocion Antes',
            'Emocion Despues',
            'Notas',
        ];
    }

    public function map($trade): array
    {
        $emotions = ManualTrade::emotionOptions();

        return [
            $trade->trade_date->format('d/m/Y'),
            $trade->tradePair->symbol ?? '-',
            strtoupper($trade->direction),
            $trade->entry_price,
            $trade->exit_price ?? '-',
            $trade->stop_loss ?? '-',
            $trade->take_profit ?? '-',
            $trade->pnl !== null ? number_format($trade->pnl, 2) : '-',
            $trade->pnl_percentage !== null ? number_format($trade->pnl_percentage, 2) . '%' : '-',
            $trade->risk_reward_actual !== null ? number_format($trade->risk_reward_actual, 2) : '-',
            $trade->overall_rating ? $trade->overall_rating . '/5' : '-',
            isset($emotions[$trade->emotion_before]) ? $emotions[$trade->emotion_before] : '-',
            isset($emotions[$trade->emotion_after]) ? $emotions[$trade->emotion_after] : '-',
            $trade->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1e1e2e'],
                ],
            ],
        ];
    }
}
