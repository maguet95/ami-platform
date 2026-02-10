<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bitacora de Trading — {{ $user->name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #333; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #6366f1; padding-bottom: 10px; }
        .header h1 { font-size: 18px; color: #1e1e2e; margin: 0; }
        .header .meta { text-align: right; color: #666; font-size: 9px; }
        .stats-grid { display: table; width: 100%; margin-bottom: 20px; }
        .stat-row { display: table-row; }
        .stat-cell { display: table-cell; text-align: center; padding: 8px; background: #f8f9fa; border: 1px solid #e2e8f0; width: 16.66%; }
        .stat-cell .value { font-size: 14px; font-weight: bold; color: #1e1e2e; }
        .stat-cell .label { font-size: 8px; color: #64748b; text-transform: uppercase; margin-top: 2px; }
        .green { color: #22c55e; }
        .red { color: #ef4444; }
        table.trades { width: 100%; border-collapse: collapse; font-size: 9px; }
        table.trades th { background: #1e1e2e; color: #fff; padding: 6px 4px; text-align: left; font-weight: 600; text-transform: uppercase; font-size: 8px; }
        table.trades td { padding: 5px 4px; border-bottom: 1px solid #e2e8f0; }
        table.trades tr:nth-child(even) { background: #f8f9fa; }
        .footer { margin-top: 20px; text-align: center; color: #999; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>AMI — Bitacora de Trading</h1>
            <p style="color: #666; margin: 4px 0 0;">{{ $user->name }} ({{ $user->email }})</p>
        </div>
        <div class="meta">
            <p>Generado: {{ $generatedAt }}</p>
            <p>Total: {{ $trades->count() }} trades</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-row">
            <div class="stat-cell">
                <div class="value">{{ $stats['total_trades'] }}</div>
                <div class="label">Trades</div>
            </div>
            <div class="stat-cell">
                <div class="value {{ $stats['win_rate'] >= 50 ? 'green' : 'red' }}">{{ $stats['win_rate'] }}%</div>
                <div class="label">Win Rate</div>
            </div>
            <div class="stat-cell">
                <div class="value {{ $stats['total_pnl'] >= 0 ? 'green' : 'red' }}">${{ number_format($stats['total_pnl'], 2) }}</div>
                <div class="label">P&L Total</div>
            </div>
            <div class="stat-cell">
                <div class="value">{{ number_format($stats['profit_factor'], 2) }}</div>
                <div class="label">Profit Factor</div>
            </div>
            <div class="stat-cell">
                <div class="value green">${{ number_format($stats['best_trade'], 2) }}</div>
                <div class="label">Mejor Trade</div>
            </div>
            <div class="stat-cell">
                <div class="value">{{ $stats['best_streak'] }}</div>
                <div class="label">Mejor Racha</div>
            </div>
        </div>
    </div>

    <table class="trades">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Par</th>
                <th>Dir.</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>SL</th>
                <th>TP</th>
                <th style="text-align:right">P&L</th>
                <th style="text-align:right">P&L%</th>
                <th>RR</th>
                <th>Rating</th>
                <th>Emocion</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trades as $trade)
            <tr>
                <td>{{ $trade->trade_date->format('d/m/Y') }}</td>
                <td style="font-weight:600">{{ $trade->tradePair->symbol ?? '-' }}</td>
                <td>{{ strtoupper($trade->direction) }}</td>
                <td>{{ $trade->entry_price }}</td>
                <td>{{ $trade->exit_price ?? '-' }}</td>
                <td>{{ $trade->stop_loss ?? '-' }}</td>
                <td>{{ $trade->take_profit ?? '-' }}</td>
                <td style="text-align:right; font-weight:600;" class="{{ $trade->pnl >= 0 ? 'green' : 'red' }}">
                    {{ $trade->pnl !== null ? ($trade->pnl >= 0 ? '+' : '') . '$' . number_format($trade->pnl, 2) : '-' }}
                </td>
                <td style="text-align:right" class="{{ ($trade->pnl_percentage ?? 0) >= 0 ? 'green' : 'red' }}">
                    {{ $trade->pnl_percentage !== null ? number_format($trade->pnl_percentage, 2) . '%' : '-' }}
                </td>
                <td>{{ $trade->risk_reward_actual !== null ? number_format($trade->risk_reward_actual, 2) : '-' }}</td>
                <td>{{ $trade->overall_rating ? $trade->overall_rating . '/5' : '-' }}</td>
                <td>{{ \App\Models\ManualTrade::emotionOptions()[$trade->emotion_after] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Alpha Markets Institute (AMI) — Reporte generado automaticamente</p>
    </div>
</body>
</html>
