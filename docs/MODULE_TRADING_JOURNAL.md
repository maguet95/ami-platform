# PLAN TÉCNICO DE MÓDULO — Trading Journal Automático (Read-Only)

## 1. Alcance del Módulo

El Trading Journal es un **módulo premium opcional** que permite a estudiantes con membresía premium visualizar su historial de operaciones de trading. Los datos son importados y procesados por **workers externos (Python)** y presentados por **Laravel en modo solo lectura**.

### Qué ES este módulo
- Dashboard de lectura de operaciones de trading por estudiante
- Visualización de estadísticas calculadas externamente
- Endpoint API interno para recibir datos de workers
- Feature flag para activar/desactivar sin afectar la plataforma

### Qué NO ES este módulo (Non-Goals)
- **NO es un sistema de trading** — no ejecuta operaciones
- **NO permite entrada manual** — los usuarios no escriben trades
- **NO calcula estadísticas** — eso lo hacen los workers
- **NO se conecta a brokers** — eso lo hacen los workers
- **NO es un servicio independiente** — es un módulo dentro de Laravel
- **NO tiene su propia autenticación** — usa la autenticación existente de la plataforma
- **NO modifica datos** — es estrictamente read-only para el usuario

---

## 2. Separación de Responsabilidades

### 2.1 Laravel (este módulo) es responsable de:

| Responsabilidad | Detalle |
|----------------|---------|
| **Recibir datos** | API interna autenticada para que workers escriban entradas |
| **Validar datos** | Validación estricta de todo payload antes de persistir |
| **Almacenar datos** | Escribir en PostgreSQL las entradas validadas |
| **Presentar datos** | Dashboard read-only con tablas, filtros, estadísticas |
| **Control de acceso** | Verificar membresía premium antes de mostrar journal |
| **Auditoría** | Log de toda escritura vía API |

### 2.2 Workers (Python) son responsables de:

| Responsabilidad | Detalle |
|----------------|---------|
| **Conectar a brokers** | APIs de brokers (Binance, MetaTrader, etc.) o parseo de CSV |
| **Parsear trades** | Normalizar formatos diferentes a estructura común |
| **Calcular métricas** | PnL, duración, slippage, win rate, drawdown |
| **Agregar estadísticas** | Resúmenes diarios, semanales, mensuales |
| **Enviar a Laravel** | POST HTTP a API interna con datos procesados |
| **Scheduling** | Ejecutarse periódicamente (cron propio) |
| **Reintentos** | Manejar fallos de red y reintentar envíos |

---

## 3. Modelos de Datos

### 3.1 `trade_pairs`

Catálogo de pares/instrumentos de trading.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint PK | Auto-increment |
| `symbol` | varchar(20) | Símbolo del par (BTCUSDT, EURUSD) |
| `market` | varchar(20) | Mercado (crypto, forex, stocks) |
| `display_name` | varchar(50) | Nombre legible (Bitcoin/USDT) |
| `is_active` | boolean | Si está activo en el sistema |
| `created_at` | timestamp | — |
| `updated_at` | timestamp | — |

**Índice:** `UNIQUE(symbol, market)`

### 3.2 `trade_entries`

Operaciones individuales de trading. Escritas SOLO por workers.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint PK | Auto-increment |
| `user_id` | bigint FK → users | Estudiante dueño del trade |
| `trade_pair_id` | bigint FK → trade_pairs | Par operado |
| `external_id` | varchar(100) | ID del trade en el broker (para deduplicación) |
| `direction` | enum('long','short') | Dirección de la operación |
| `entry_price` | decimal(18,8) | Precio de entrada |
| `exit_price` | decimal(18,8) | Precio de salida (null si abierto) |
| `quantity` | decimal(18,8) | Cantidad operada |
| `pnl` | decimal(18,8) | Profit/Loss calculado por worker |
| `pnl_percentage` | decimal(8,4) | PnL como porcentaje |
| `fee` | decimal(18,8) | Comisión del broker |
| `opened_at` | timestamp | Momento de apertura |
| `closed_at` | timestamp | Momento de cierre (null si abierto) |
| `duration_seconds` | integer | Duración en segundos |
| `status` | enum('open','closed','cancelled') | Estado del trade |
| `tags` | jsonb | Tags libres del worker (estrategia, setup, etc.) |
| `notes` | text | Notas generadas por worker |
| `source` | varchar(50) | Origen: broker name o 'csv_import' |
| `created_at` | timestamp | Momento de escritura en la plataforma |
| `updated_at` | timestamp | — |

**Índices:**
- `INDEX(user_id, closed_at DESC)` — consulta principal del dashboard
- `INDEX(user_id, trade_pair_id)` — filtro por par
- `UNIQUE(user_id, external_id, source)` — deduplicación

### 3.3 `journal_summaries`

Estadísticas agregadas pre-calculadas por workers. Evitan cálculos en tiempo real.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint PK | Auto-increment |
| `user_id` | bigint FK → users | Estudiante |
| `period_type` | enum('daily','weekly','monthly','all_time') | Tipo de período |
| `period_start` | date | Inicio del período |
| `period_end` | date | Fin del período |
| `total_trades` | integer | Total de operaciones |
| `winning_trades` | integer | Operaciones ganadoras |
| `losing_trades` | integer | Operaciones perdedoras |
| `win_rate` | decimal(5,2) | Porcentaje de acierto |
| `total_pnl` | decimal(18,8) | PnL total del período |
| `max_drawdown` | decimal(8,4) | Máximo drawdown (%) |
| `best_trade_pnl` | decimal(18,8) | Mejor operación |
| `worst_trade_pnl` | decimal(18,8) | Peor operación |
| `avg_trade_duration` | integer | Duración promedio (segundos) |
| `profit_factor` | decimal(8,4) | Ratio ganancia/pérdida |
| `metadata` | jsonb | Datos extra flexibles |
| `calculated_at` | timestamp | Momento del cálculo |
| `created_at` | timestamp | — |
| `updated_at` | timestamp | — |

**Índices:**
- `UNIQUE(user_id, period_type, period_start)` — un resumen por período
- `INDEX(user_id, period_type)` — consulta de dashboard

### 3.4 `journal_api_keys`

API keys para autenticación de workers.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint PK | Auto-increment |
| `name` | varchar(100) | Nombre descriptivo (ej: "worker-trade-importer") |
| `key_hash` | varchar(255) | Hash SHA-256 de la API key |
| `key_prefix` | varchar(8) | Primeros 8 chars para identificación |
| `permissions` | jsonb | Permisos específicos (write:entries, write:summaries) |
| `allowed_ips` | jsonb | Lista de IPs permitidas (null = cualquiera) |
| `last_used_at` | timestamp | Último uso |
| `expires_at` | timestamp | Fecha de expiración (null = sin expiración) |
| `is_active` | boolean | Si está activa |
| `created_at` | timestamp | — |
| `updated_at` | timestamp | — |

---

## 4. Contratos de API (Request/Response)

### 4.1 Escritura de Entradas de Trading

**Endpoint:** `POST /api/internal/journal/entries`
**Autenticación:** Header `X-API-Key: <key>`
**Propósito:** Worker envía lote de trades procesados

**Request:**
```
Headers:
  X-API-Key: ami_worker_k8f7g...
  Content-Type: application/json

Body:
{
  "entries": [
    {
      "user_id": 42,
      "external_id": "binance_123456",
      "symbol": "BTCUSDT",
      "market": "crypto",
      "direction": "long",
      "entry_price": "43250.50",
      "exit_price": "44100.00",
      "quantity": "0.5",
      "pnl": "424.75",
      "pnl_percentage": "1.96",
      "fee": "8.63",
      "opened_at": "2026-02-08T14:30:00Z",
      "closed_at": "2026-02-08T18:45:00Z",
      "duration_seconds": 15300,
      "status": "closed",
      "tags": ["breakout", "btc"],
      "notes": "Breakout de resistencia en 4H",
      "source": "binance"
    }
  ]
}
```

**Response (éxito — 201):**
```json
{
  "status": "ok",
  "received": 1,
  "created": 1,
  "duplicates_skipped": 0,
  "errors": []
}
```

**Response (error parcial — 207):**
```json
{
  "status": "partial",
  "received": 3,
  "created": 2,
  "duplicates_skipped": 0,
  "errors": [
    {
      "index": 2,
      "external_id": "binance_999",
      "error": "user_id 999 not found"
    }
  ]
}
```

**Response (error de autenticación — 401):**
```json
{
  "status": "error",
  "message": "Invalid or expired API key"
}
```

**Response (rate limited — 429):**
```json
{
  "status": "error",
  "message": "Rate limit exceeded",
  "retry_after": 60
}
```

### 4.2 Escritura de Resúmenes

**Endpoint:** `POST /api/internal/journal/summaries`
**Autenticación:** Header `X-API-Key: <key>`
**Propósito:** Worker envía estadísticas pre-calculadas

**Request:**
```
Body:
{
  "summaries": [
    {
      "user_id": 42,
      "period_type": "weekly",
      "period_start": "2026-02-03",
      "period_end": "2026-02-09",
      "total_trades": 15,
      "winning_trades": 9,
      "losing_trades": 6,
      "win_rate": 60.00,
      "total_pnl": "1250.30",
      "max_drawdown": 3.20,
      "best_trade_pnl": "424.75",
      "worst_trade_pnl": "-180.50",
      "avg_trade_duration": 12600,
      "profit_factor": 2.15,
      "metadata": {
        "most_traded_pair": "BTCUSDT",
        "avg_risk_reward": 1.8
      }
    }
  ]
}
```

**Response (éxito — 201):**
```json
{
  "status": "ok",
  "received": 1,
  "upserted": 1,
  "errors": []
}
```

**Nota:** Los resúmenes usan UPSERT (insertar o actualizar si ya existe para el mismo user_id + period_type + period_start).

### 4.3 Health Check del Módulo

**Endpoint:** `GET /api/internal/journal/health`
**Autenticación:** Header `X-API-Key: <key>`
**Propósito:** Workers verifican que el módulo está activo

**Response (activo — 200):**
```json
{
  "status": "ok",
  "module": "journal",
  "active": true,
  "db_writable": true,
  "timestamp": "2026-02-09T10:30:00Z"
}
```

**Response (módulo desactivado — 503):**
```json
{
  "status": "unavailable",
  "module": "journal",
  "active": false,
  "message": "Journal module is disabled"
}
```

---

## 5. Ciclo de Vida de Sincronización

### 5.1 Flujo Normal

```
┌─── Worker (cada N minutos) ───────────────────────────────────┐
│                                                                │
│  1. GET /api/internal/journal/health                           │
│     └── Si 503 → log warning, skip cycle, retry next interval │
│     └── Si 200 → continuar                                    │
│                                                                │
│  2. Conectar a broker API / leer CSV                           │
│     └── Obtener trades nuevos desde último sync                │
│                                                                │
│  3. Procesar trades:                                           │
│     └── Parsear, normalizar, calcular PnL                      │
│                                                                │
│  4. POST /api/internal/journal/entries                          │
│     └── Enviar lote (max 100 entries por request)              │
│     └── Si 201 → ok                                            │
│     └── Si 207 → log errores parciales, continuar              │
│     └── Si 429 → esperar retry_after, reintentar               │
│     └── Si 5xx → reintentar con backoff exponencial (max 3)    │
│                                                                │
│  5. Calcular estadísticas del período                          │
│                                                                │
│  6. POST /api/internal/journal/summaries                       │
│     └── Mismo manejo de errores que paso 4                     │
│                                                                │
│  7. Log resultado del ciclo                                    │
│     └── Registrar: trades importados, errores, duración        │
└────────────────────────────────────────────────────────────────┘
```

### 5.2 Deduplicación

- Cada trade tiene `external_id` + `source` únicos por usuario
- Si un worker envía un trade que ya existe → se skippea (no error, no duplicado)
- Los resúmenes usan UPSERT → siempre se sobrescribe con el cálculo más reciente

### 5.3 Idempotencia

- Todos los endpoints son idempotentes
- Enviar el mismo lote 2 veces produce el mismo resultado
- No hay efectos secundarios (no se envían emails, no se disparan eventos)

---

## 6. Estrategia de Manejo de Errores

### 6.1 Errores en el Worker

| Escenario | Acción del Worker | Acción de Laravel |
|-----------|-------------------|-------------------|
| Broker API no disponible | Log error, skip cycle, retry en próximo intervalo | Nada (no se entera) |
| Error de parseo de trade | Log trade específico, continuar con los demás | Nada |
| Laravel API no disponible (5xx) | Retry con backoff exponencial (3 intentos), luego almacenar en disco local | Nada |
| Laravel API rechaza datos (422) | Log validación fallida, NO reintentar (datos incorrectos) | Log en audit |
| Rate limited (429) | Esperar `retry_after` segundos, reintentar | Log en audit |
| API key expirada (401) | Alertar al admin, detener worker | Log intento de acceso |
| Timeout de red | Retry con backoff, mismo lote (idempotente) | Nada |

### 6.2 Errores en Laravel (API interna)

| Escenario | Respuesta HTTP | Acción |
|-----------|----------------|--------|
| API key inválida | 401 | Log intento + bloquear si > 10 intentos/minuto |
| IP no autorizada | 403 | Log intento |
| Payload inválido | 422 | Devolver detalle de validación |
| Módulo desactivado | 503 | Devolver estado |
| Error interno | 500 | Log error, devolver mensaje genérico |
| DB no disponible | 503 | Log error, worker reintentará |

### 6.3 Monitoreo

- **Laravel:** Log de toda escritura en tabla `journal_audit_log` (quien, cuándo, cuántos registros, resultado)
- **Workers:** Log estructurado (JSON) con métricas por ciclo: trades importados, errores, duración
- **Alertas:** Si un worker no escribe en > 24h → alerta al admin

---

## 7. Supuestos de Seguridad

| Supuesto | Implementación |
|----------|----------------|
| Workers corren en infraestructura semi-confiable | Por eso NO acceden directo a la DB |
| API keys pueden ser robadas | Rotación periódica + IP whitelist + expiración |
| Los datos de trading son sensibles | Solo el dueño ve su journal. Admin ve métricas agregadas, no trades individuales |
| El módulo puede ser desactivado de emergencia | Feature flag global. Desactivar = API devuelve 503, vistas devuelven 404 |
| Un worker comprometido podría enviar datos falsos | Validación estricta en Laravel. Logs de auditoría. No hay acciones automáticas basadas en trades (no se ejecutan órdenes) |
| Volumen de datos puede crecer | Índices optimizados. Paginación obligatoria. Posibilidad de particionar por fecha en el futuro |

---

## 8. Límites del MVP del Journal

### MVP incluye:
- [ ] Esquema de datos (3 tablas + API keys)
- [ ] API interna con 3 endpoints (entries, summaries, health)
- [ ] Dashboard básico: tabla de trades con paginación y filtros
- [ ] Estadísticas: win rate, PnL total, número de trades (desde summaries)
- [ ] Control de acceso premium
- [ ] Feature flag on/off

### MVP NO incluye:
- Gráficos interactivos (equity curve, distribución) → Phase 4.2+
- Exportación a CSV → Phase 4.2+
- Comparación entre períodos → futuro
- Alertas por drawdown → futuro
- Múltiples brokers por usuario → futuro
- Trades abiertos en tiempo real → fuera de alcance (requeriría WebSockets)
