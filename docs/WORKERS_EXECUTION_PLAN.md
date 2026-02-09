# PLAN DE EJECUCIÓN — Workers Externos (Linux VPS / Python)

## 1. Supuestos del Sistema Operativo

### 1.1 Entorno de Producción

| Aspecto | Especificación |
|---------|---------------|
| **OS** | Ubuntu 24.04 LTS (soporte hasta 2034) |
| **Arquitectura** | x86_64 (AMD64) |
| **VPS mínimo** | 2 vCPU, 4 GB RAM, 40 GB SSD |
| **Proveedor recomendado** | DigitalOcean, Hetzner, o Vultr |
| **Acceso** | SSH con key-based auth (sin password) |
| **Firewall** | UFW: solo SSH (22), sin puertos HTTP públicos |
| **DNS** | No necesita dominio. No sirve tráfico web. |

### 1.2 Hardening Básico

```
- SSH: solo key-based authentication
- SSH: puerto custom (no 22) — opcional pero recomendado
- UFW: deny incoming, allow outgoing, allow SSH
- fail2ban: activo para SSH
- Actualizaciones de seguridad automáticas (unattended-upgrades)
- Usuario dedicado 'ami-worker' (sin sudo)
- No root login por SSH
```

**Principio clave:** Este VPS NO sirve HTTP público. No tiene Nginx, no tiene PHP, no tiene puertos abiertos al mundo excepto SSH.

---

## 2. Entorno Python

### 2.1 Stack

| Componente | Versión | Propósito |
|-----------|---------|-----------|
| **Python** | 3.12+ | Runtime principal |
| **pip + venv** | Incluido | Gestión de dependencias y entorno virtual |
| **poetry** | Última | Gestión de dependencias (opcional, alternativa a pip) |
| **httpx** | Última | Cliente HTTP async para comunicación con Laravel API |
| **pydantic** | v2 | Validación de datos y serialización |
| **structlog** | Última | Logging estructurado (JSON) |
| **python-dotenv** | Última | Variables de entorno |
| **ccxt** | Última | Conexión universal a brokers crypto (si aplica) |
| **pandas** | Última | Procesamiento de datos tabulares (solo si necesario) |

### 2.2 Estructura del Proyecto

```
ami-workers/                    ← Repositorio separado
├── pyproject.toml              ← Configuración del proyecto (dependencias, metadata)
├── .env.example                ← Variables de entorno requeridas
├── .gitignore
├── README.md
│
├── workers/                    ← Paquete principal
│   ├── __init__.py
│   ├── config.py               ← Carga de configuración desde .env
│   ├── api_client.py           ← Cliente HTTP para Laravel API interna
│   ├── logger.py               ← Configuración de structlog
│   ├── exceptions.py           ← Excepciones custom del proyecto
│   │
│   ├── trade_importer/         ← Worker: importación de trades
│   │   ├── __init__.py
│   │   ├── main.py             ← Entry point del worker
│   │   ├── brokers/            ← Conectores por broker
│   │   │   ├── __init__.py
│   │   │   ├── base.py         ← Interfaz base de broker
│   │   │   ├── binance.py
│   │   │   ├── metatrader.py
│   │   │   └── csv_import.py
│   │   ├── normalizer.py       ← Normalización de trades a formato estándar
│   │   └── calculator.py       ← Cálculos de PnL, duración, etc.
│   │
│   ├── stats_analyzer/         ← Worker: cálculo de estadísticas
│   │   ├── __init__.py
│   │   ├── main.py             ← Entry point del worker
│   │   ├── aggregator.py       ← Agregación por período
│   │   └── metrics.py          ← Cálculos de win rate, drawdown, etc.
│   │
│   └── [future_worker]/        ← Plantilla para nuevos workers
│       ├── __init__.py
│       └── main.py
│
├── scripts/
│   ├── setup.sh                ← Script de instalación en VPS
│   ├── deploy.sh               ← Script de deploy (pull + restart)
│   └── health_check.sh         ← Script para verificar que workers están vivos
│
├── logs/                       ← Directorio de logs (gitignored)
│   └── .gitkeep
│
├── data/                       ← Datos temporales / buffer local (gitignored)
│   └── .gitkeep
│
└── tests/
    ├── test_api_client.py
    ├── test_normalizer.py
    └── test_calculator.py
```

### 2.3 Configuración (.env)

```
# Laravel API
LARAVEL_API_URL=https://ami-platform.com/api/internal
LARAVEL_API_KEY=ami_worker_k8f7g...

# Worker: Trade Importer
TRADE_IMPORTER_ENABLED=true
TRADE_IMPORTER_INTERVAL_MINUTES=15
TRADE_IMPORTER_BATCH_SIZE=100

# Worker: Stats Analyzer
STATS_ANALYZER_ENABLED=true
STATS_ANALYZER_INTERVAL_MINUTES=60

# Broker: Binance (ejemplo)
BINANCE_API_KEY=...
BINANCE_API_SECRET=...

# Logging
LOG_LEVEL=INFO
LOG_DIR=/home/ami-worker/ami-workers/logs

# Retry
MAX_RETRIES=3
RETRY_BACKOFF_BASE=2
```

---

## 3. Estrategia de Cron / Scheduling

### 3.1 Enfoque: systemd timers + scripts wrapper

No se usa cron directamente. Se usan **systemd timers** por estas razones:
- Mejor logging (journalctl integrado)
- Control de dependencias entre servicios
- Reinicio automático en caso de fallo
- No sufre el problema de cron de "entorno mínimo"

### 3.2 Definición de Servicios

**Trade Importer Service:**

```ini
# /etc/systemd/system/ami-trade-importer.service
[Unit]
Description=AMI Trade Importer Worker
After=network-online.target
Wants=network-online.target

[Service]
Type=oneshot
User=ami-worker
Group=ami-worker
WorkingDirectory=/home/ami-worker/ami-workers
Environment=PYTHONPATH=/home/ami-worker/ami-workers
ExecStart=/home/ami-worker/ami-workers/.venv/bin/python -m workers.trade_importer.main
StandardOutput=journal
StandardError=journal

# Seguridad
NoNewPrivileges=true
ProtectSystem=strict
ReadWritePaths=/home/ami-worker/ami-workers/logs /home/ami-worker/ami-workers/data
PrivateTmp=true
```

**Trade Importer Timer:**

```ini
# /etc/systemd/system/ami-trade-importer.timer
[Unit]
Description=Run AMI Trade Importer every 15 minutes

[Timer]
OnCalendar=*:0/15
Persistent=true
RandomizedDelaySec=30

[Install]
WantedBy=timers.target
```

**Stats Analyzer Service + Timer:**
Mismo patrón, con `OnCalendar=*:0/60` (cada hora).

### 3.3 Intervalos por Worker

| Worker | Intervalo | Justificación |
|--------|-----------|---------------|
| Trade Importer | Cada 15 minutos | Balance entre frescura y carga en API del broker |
| Stats Analyzer | Cada 60 minutos | Las estadísticas no necesitan ser real-time |
| [Futuro worker] | Configurable | Según necesidad del worker |

### 3.4 Protección contra Overlap

- `Type=oneshot` en systemd asegura que no se ejecuten dos instancias simultáneas
- Si la ejecución anterior no terminó, el timer espera
- Timeout configurable: si un worker tarda más de 10 minutos → kill + log

```ini
# Agregar a cada .service:
TimeoutStartSec=600  # 10 minutos máximo por ejecución
```

---

## 4. Logging

### 4.1 Formato

Todos los workers usan **JSON estructurado** via `structlog`:

```json
{
  "timestamp": "2026-02-09T10:30:15.123Z",
  "level": "info",
  "worker": "trade_importer",
  "event": "cycle_completed",
  "trades_fetched": 12,
  "trades_sent": 12,
  "trades_created": 10,
  "duplicates_skipped": 2,
  "errors": 0,
  "duration_seconds": 8.4,
  "broker": "binance",
  "user_ids": [42, 78, 103]
}
```

### 4.2 Niveles

| Nivel | Uso |
|-------|-----|
| `DEBUG` | Detalle de cada trade procesado (solo en desarrollo) |
| `INFO` | Inicio/fin de ciclo, métricas de resultado |
| `WARNING` | Rate limited, broker temporalmente no disponible, datos incompletos |
| `ERROR` | Fallo de API (después de reintentos), datos corruptos, excepción no manejada |
| `CRITICAL` | API key revocada, worker no puede continuar |

### 4.3 Destinos

```
DESARROLLO:
  → Console (stdout con colores)
  → Archivo local: logs/trade_importer.log

PRODUCCIÓN:
  → Archivo local: logs/trade_importer.log (rotado diariamente)
  → journalctl (vía systemd)
  → (Futuro) Servicio externo: Loki, Datadog, o similar
```

### 4.4 Rotación de Logs

```ini
# /etc/logrotate.d/ami-workers
/home/ami-worker/ami-workers/logs/*.log {
    daily
    rotate 14
    compress
    delaycompress
    missingok
    notifempty
    create 640 ami-worker ami-worker
}
```

14 días de retención. Comprimidos después del primer día.

---

## 5. Aislamiento de Fallos por Cuenta

### 5.1 Principio

**Un fallo en la importación de un usuario NO debe afectar a otros usuarios.** Cada usuario se procesa de forma independiente.

### 5.2 Flujo de Procesamiento Aislado

```
Worker inicia ciclo
│
├── Obtener lista de usuarios activos con journal
│
├── Para cada usuario (user_id):
│   │
│   ├── TRY:
│   │   ├── Conectar a broker del usuario
│   │   ├── Obtener trades nuevos
│   │   ├── Procesar y normalizar
│   │   ├── Enviar a Laravel API
│   │   └── Log éxito para este usuario
│   │
│   └── CATCH:
│       ├── Log error para este usuario específico
│       ├── Incrementar contador de errores
│       ├── Continuar con el siguiente usuario ← CLAVE
│       └── Si > 50% de usuarios fallan → alertar
│
├── Log resumen del ciclo
└── Fin del ciclo
```

### 5.3 Estrategia de Aislamiento

| Escenario | Impacto | Manejo |
|-----------|---------|--------|
| Broker de un usuario falla | Solo ese usuario | Log error, skip, continuar |
| Datos de un usuario corruptos | Solo ese usuario | Log error, skip, continuar |
| Laravel rechaza trades de un usuario (422) | Solo ese usuario | Log validación, skip |
| API key del broker de un usuario expirada | Solo ese usuario | Log, marcar para revisión |
| Worker completo cae | Todos los usuarios de ese ciclo | systemd reinicia en siguiente timer |
| Laravel API no disponible | Todos los usuarios | Buffer local + retry en siguiente ciclo |

### 5.4 Buffer Local (Fault Tolerance)

Cuando Laravel no está disponible, el worker guarda datos procesados en disco local:

```
data/
├── pending/                    ← Lotes pendientes de envío
│   ├── 2026-02-09_10-30_user42.json
│   └── 2026-02-09_10-30_user78.json
└── failed/                     ← Lotes que fallaron 3 veces
    └── 2026-02-08_22-15_user103.json
```

- En cada ciclo, antes de procesar nuevos datos, el worker intenta enviar los pendientes
- Después de 3 reintentos, un lote pasa a `failed/` y se alerta
- Los archivos en `failed/` requieren intervención manual o investigación

### 5.5 Métricas por Cuenta

Cada ciclo registra métricas por usuario:

```json
{
  "timestamp": "2026-02-09T10:30:15Z",
  "worker": "trade_importer",
  "cycle_id": "abc123",
  "per_user_results": [
    {"user_id": 42, "status": "ok", "trades": 5, "duration_ms": 1200},
    {"user_id": 78, "status": "ok", "trades": 3, "duration_ms": 800},
    {"user_id": 103, "status": "error", "error": "broker_auth_failed", "duration_ms": 200}
  ],
  "summary": {
    "total_users": 3,
    "successful": 2,
    "failed": 1,
    "total_trades": 8,
    "total_duration_ms": 2200
  }
}
```

---

## 6. Deploy y Operaciones

### 6.1 Deploy Inicial (setup.sh)

```
1. Crear usuario ami-worker
2. Clonar repositorio ami-workers
3. Crear venv + instalar dependencias
4. Copiar .env desde template
5. Crear directorios: logs/, data/pending/, data/failed/
6. Instalar systemd services y timers
7. Habilitar timers
8. Verificar con health_check.sh
```

### 6.2 Deploy de Actualización (deploy.sh)

```
1. git pull origin main
2. Activar venv
3. pip install -r requirements.txt (o poetry install)
4. Reiniciar timers afectados
5. Verificar health
```

### 6.3 Monitoreo

| Qué monitorear | Cómo |
|-----------------|------|
| Workers están ejecutándose | `systemctl list-timers --all` + health_check.sh |
| Errores en workers | Revisar logs + alertas por email si error rate > umbral |
| Datos llegando a Laravel | Laravel dashboard: "última escritura del journal" |
| Disk space | Alerta si logs o data/ superan umbral |
| VPS está vivo | UptimeRobot monitoring SSH port |

---

## 7. Cómo Agregar un Nuevo Worker

```
1. Crear directorio: workers/nuevo_worker/
2. Implementar main.py con la lógica del worker
3. Usar api_client.py existente para comunicación con Laravel
4. Crear systemd service + timer
5. Agregar variables a .env
6. Deploy: deploy.sh + systemctl enable

Archivos a tocar:
  - workers/nuevo_worker/main.py (nuevo)
  - .env (agregar variables)
  - /etc/systemd/system/ami-nuevo-worker.service (nuevo)
  - /etc/systemd/system/ami-nuevo-worker.timer (nuevo)

Archivos que NO se tocan:
  - Ningún archivo de workers existentes
  - Ningún archivo de configuración global
  - Nada en el repositorio de Laravel (salvo nuevo endpoint si necesario)
```
