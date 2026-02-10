<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TradeEntriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'Fecha Apertura',
            'Fecha Cierre',
            'Par',
            'Direccion',
            'Entrada',
            'Salida',
            'Cantidad',
            'P&L ($)',
            'P&L (%)',
            'Fee',
            'Duracion',
            'Fuente',
            'Estado',
        ];
    }

    public function map($trade): array
    {
        $duration = $trade->duration_seconds;
        if ($duration >= 86400) {
            $durationStr = round($duration / 86400, 1) . 'd';
        } elseif ($duration >= 3600) {
            $durationStr = round($duration / 3600, 1) . 'h';
        } else {
            $durationStr = round($duration / 60) . 'm';
        }

        return [
            $trade->opened_at->format('d/m/Y H:i'),
            $trade->closed_at ? $trade->closed_at->format('d/m/Y H:i') : '-',
            $trade->tradePair->symbol ?? '-',
            strtoupper($trade->direction),
            $trade->entry_price,
            $trade->exit_price ?? '-',
            $trade->quantity,
            $trade->pnl !== null ? number_format($trade->pnl, 2) : '-',
            $trade->pnl_percentage !== null ? number_format($trade->pnl_percentage, 2) . '%' : '-',
            $trade->fee ? number_format($trade->fee, 2) : '-',
            $durationStr,
            $trade->source ?? '-',
            $trade->status,
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
