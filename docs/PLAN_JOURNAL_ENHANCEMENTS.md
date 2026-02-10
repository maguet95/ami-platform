# Plan: Journal Enhancements — Stats, Exports, Demo Data, Public Profile

## Context
Both journals (manual Bitacora + automatic Journal) are functionally complete but lack: test data to visualize, export capabilities, detailed analytics with charts, and public profile integration. The user wants a ForTraders-style experience with equity curves, calendar views, and transparency about which journal is shared.

**User choices**: Chart.js, Excel+PDF exports, per-section privacy toggles, ~50 demo trades.

---

## Phase A: Dependencies & Setup

### A1. Install packages
```bash
composer require maatwebsite/excel barryvdh/laravel-dompdf
npm install chart.js
```

### A2. Add Chart.js to Vite
- **Edit**: `resources/js/app.js` — import Chart.js and register it globally on `window.Chart`

---

## Phase B: Demo Data Seeders

### B1. ManualTradeSeeder (~50 trades)
- **Create**: `database/seeders/ManualTradeSeeder.php`
- Generate realistic trades for the admin user over the past 3 months
- Mix: ~55% winners, ~30% losers, ~15% breakeven
- Varied pairs (BTCUSDT, EURUSD, SPX500, etc.), directions, timeframes, sessions
- Include emotions, confidence, ratings, plan adherence, mistakes, lessons
- Realistic P&L range: -$500 to +$1,200

### B2. TradeEntrySeeder (~50 entries for automatic journal)
- **Create**: `database/seeders/TradeEntrySeeder.php`
- Generate entries for the admin user simulating worker data
- Same 3-month period, varied pairs, realistic execution prices
- Include some with `source = 'metatrader5'` and `source = 'binance'`

### B3. JournalSummarySeeder
- **Create**: `database/seeders/JournalSummarySeeder.php`
- Generate daily/weekly/monthly/all_time summaries matching the trade entries
- Pre-calculate: total_trades, win_rate, total_pnl, max_drawdown, profit_factor

### B4. Update DatabaseSeeder
- **Edit**: `database/seeders/DatabaseSeeder.php` — call new seeders

---

## Phase C: Export Functionality

### C1. Manual Journal (Bitacora) Export
- **Create**: `app/Exports/ManualTradesExport.php` (Maatwebsite Excel)
  - Columns: Fecha, Par, Direccion, Entrada, Salida, SL, TP, P&L, P&L%, RR, Rating, Emociones, Notas
  - Styled headers, number formatting, auto-width columns
  - Filters carried over (export what you see)

- **Create**: `resources/views/exports/manual-trades-pdf.blade.php`
  - Clean PDF layout with header (AMI logo, user name, date range)
  - Summary metrics block at top (total trades, win rate, P&L, best streak)
  - Table of trades below
  - Uses DomPDF

- **Create**: `app/Http/Controllers/ManualJournalExportController.php`
  - `exportExcel(Request $request)` — downloads .xlsx
  - `exportPdf(Request $request)` — downloads .pdf
  - Both respect current filters from query params

- **Edit**: `routes/web.php` — add export routes under bitacora group:
  - `GET /bitacora/exportar/excel`
  - `GET /bitacora/exportar/pdf`

- **Edit**: `resources/views/bitacora/index.blade.php` — add export buttons (dropdown with Excel/PDF options) next to filters

### C2. Automatic Journal Export
- **Create**: `app/Exports/TradeEntriesExport.php`
- **Create**: `resources/views/exports/trade-entries-pdf.blade.php`
- **Edit**: `app/Http/Controllers/JournalController.php` — add `exportExcel()` and `exportPdf()` methods
- **Edit**: `routes/web.php` — add export routes under journal group
- **Edit**: `resources/views/journal/index.blade.php` — add export buttons

---

## Phase D: Detailed Trading Stats Page (Private, for the trader)

### D1. Stats Service
- **Create**: `app/Services/TradingStatsService.php`
  - Works for BOTH journal types (manual + automatic) via adapter pattern
  - Methods:
    - `getOverviewMetrics()` — total trades, win rate, P&L, profit factor, max drawdown, best/worst trade, avg RR, expectancy
    - `getEquityCurve()` — chronological cumulative P&L array for Chart.js
    - `getDailyPnl()` — daily aggregated P&L for calendar view
    - `getWeeklyPnl()` — weekly aggregated P&L for bar chart
    - `getPairDistribution()` — trades per pair (donut chart)
    - `getDirectionStats()` — long vs short win rates
    - `getSessionStats()` — per-session performance
    - `getTimeframeStats()` — per-timeframe performance
    - `getStreaks()` — current/best win and loss streaks
    - `getMonthlyReturns()` — monthly P&L grid
  - Cache results (5 min) per user

### D2. Stats Controller
- **Create**: `app/Http/Controllers/TradingStatsController.php`
  - `manualStats()` — stats page for bitacora
  - `automaticStats()` — stats page for automatic journal (premium only)

### D3. Stats View (shared template, parameterized)
- **Create**: `resources/views/stats/trading-stats.blade.php`
  - **Section 1 — KPI Cards** (grid, 2x3x4 responsive):
    - Total Trades, Win Rate (%), Total P&L ($), Profit Factor, Max Drawdown ($), Expectancy, Best Trade, Worst Trade, Avg RR, Dias Rentables, Racha Actual, Mejor Racha
  - **Section 2 — Equity Curve** (Chart.js line/area chart):
    - Cumulative P&L over time, colored green above 0 / red below 0
    - Like ForTraders: show initial balance line, drawdown zones
  - **Section 3 — Calendar Heatmap** (monthly calendar, Alpine.js):
    - ForTraders-style: each day shows P&L + trade count
    - Green for profit days, red for loss days, gray for no trades
    - Month navigation arrows
  - **Section 4 — Distribution Charts** (Chart.js donuts/bars):
    - Trades by pair (donut)
    - Long vs Short performance (horizontal bar)
    - Performance by session (bar)
    - Performance by timeframe (bar)
  - **Section 5 — Monthly Returns Table**:
    - Grid with months as rows, showing P&L, # trades, win rate per month

### D4. Routes & Navigation
- **Edit**: `routes/web.php`:
  - `GET /bitacora/estadisticas` → `TradingStatsController@manualStats`
  - `GET /journal/estadisticas` → `TradingStatsController@automaticStats`
- **Edit**: `resources/views/layouts/app.blade.php` — add "Estadisticas" sub-items under Trading Journal dropdown in sidebar
- **Edit**: `resources/views/bitacora/index.blade.php` — add link to stats page
- **Edit**: `resources/views/journal/index.blade.php` — add link to stats page

---

## Phase E: Public Profile Trading Stats

### E1. User Model: Privacy Toggles
- **Create**: migration `add_journal_sharing_fields_to_users_table`
  - `share_manual_journal` (boolean, default false)
  - `share_automatic_journal` (boolean, default false)
  - `automatic_journal_account_type` (string, nullable) — e.g., "Demo MT5", "Real Binance"
- **Edit**: `app/Models/User.php` — add fillable fields, casts

### E2. Profile Settings
- **Edit**: profile settings view (find existing settings form) — add toggles:
  - "Compartir estadisticas de bitacora manual"
  - "Compartir estadisticas del journal automatico"
  - "Tipo de cuenta" text input (only shown when automatic is toggled on)

### E3. Public Profile Controller Update
- **Edit**: `app/Http/Controllers/PublicProfileController.php`
  - If `share_manual_journal` → calculate manual journal summary stats via TradingStatsService
  - If `share_automatic_journal` → calculate automatic journal summary stats
  - Pass to view: metrics, equity curve data, journal type shared, account type

### E4. Public Profile View Update
- **Edit**: `resources/views/profile/public-show.blade.php`
  - Replace the "Proximamente" placeholder with actual stats section
  - **Journal Badge**: Shows which journal(s) are shared with icon + label:
    - "Bitacora Manual" with notebook icon
    - "Journal Automatico — Demo MT5" with robot icon + account type (transparency)
  - **Summary Cards** (compact, 4-6 KPIs): Total Trades, Win Rate, P&L, Profit Factor, Mejor Racha, Dias Activos
  - **Mini Equity Curve** (Chart.js, smaller version)
  - **Pair Distribution** (small donut chart)
  - If neither journal shared → show "Este trader no comparte su journal"
  - If user is owner → show "Configura tu journal publico" link to settings

---

## Phase F: Wiring & Polish

### F1. Config updates
- **Edit**: `config/journal.php` — add `exports_enabled`, `stats_enabled` flags

### F2. Chart.js component
- **Create**: `resources/views/components/chart.blade.php` — reusable Alpine+Chart.js wrapper component that accepts type, data, options as props

---

## Implementation Order
1. **Phase A** — Install deps (required by everything else)
2. **Phase B** — Seeders (needed to test all UI)
3. **Phase D** — Stats service + page (core logic reused by profile)
4. **Phase C** — Exports (independent, can run after stats)
5. **Phase E** — Public profile (depends on stats service from Phase D)
6. **Phase F** — Polish and config

## Files to Create (~15 new files)
| File | Purpose |
|------|---------|
| `database/seeders/ManualTradeSeeder.php` | ~50 demo manual trades |
| `database/seeders/TradeEntrySeeder.php` | ~50 demo automatic entries |
| `database/seeders/JournalSummarySeeder.php` | Pre-calculated summaries |
| `app/Services/TradingStatsService.php` | Unified stats for both journals |
| `app/Http/Controllers/TradingStatsController.php` | Stats page controller |
| `resources/views/stats/trading-stats.blade.php` | Full analytics dashboard |
| `resources/views/components/chart.blade.php` | Reusable Chart.js component |
| `app/Exports/ManualTradesExport.php` | Excel export for bitacora |
| `app/Exports/TradeEntriesExport.php` | Excel export for auto journal |
| `app/Http/Controllers/ManualJournalExportController.php` | Export controller |
| `resources/views/exports/manual-trades-pdf.blade.php` | PDF template bitacora |
| `resources/views/exports/trade-entries-pdf.blade.php` | PDF template auto journal |
| `database/migrations/..._add_journal_sharing_fields.php` | Privacy toggle columns |

## Files to Modify (~10 edits)
| File | Change |
|------|--------|
| `resources/js/app.js` | Import Chart.js |
| `database/seeders/DatabaseSeeder.php` | Call new seeders |
| `routes/web.php` | Export + stats routes |
| `resources/views/layouts/app.blade.php` | Nav: Estadisticas links |
| `resources/views/bitacora/index.blade.php` | Export buttons + stats link |
| `resources/views/journal/index.blade.php` | Export buttons + stats link |
| `app/Http/Controllers/JournalController.php` | Export methods |
| `app/Http/Controllers/PublicProfileController.php` | Journal stats data |
| `resources/views/profile/public-show.blade.php` | Trading stats section |
| `app/Models/User.php` | Sharing fields + fillable |
| `config/journal.php` | New feature flags |

## Verification
1. Run `php artisan db:seed --class=ManualTradeSeeder` → verify 50 trades in DB
2. Visit `/bitacora` → see trades with metrics, filters, pagination working
3. Visit `/bitacora/estadisticas` → see full stats dashboard with charts
4. Click export Excel/PDF → download files, verify content is clean and readable
5. Visit `/journal` (as premium user) → see automatic journal with data
6. Visit `/journal/estadisticas` → see auto journal stats dashboard
7. Toggle sharing in profile settings → visit public profile → see/hide journal stats
8. Public profile shows journal type badge with account type for transparency
