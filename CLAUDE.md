# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Principios de Desarrollo

- **Cambios incrementales**: Trabajar paso a paso, un cambio a la vez. Verificar antes de seguir.
- **No sobre-ingenieria**: Solo implementar lo que se pide. No agregar features, refactors o "mejoras" extra.
- **Leer antes de modificar**: Siempre leer el archivo existente antes de editarlo.
- **Preferir edicion sobre creacion**: Editar archivos existentes en vez de crear nuevos cuando sea posible.
- **Reutilizar patrones**: Revisar como se resolvio algo similar en el codebase antes de inventar una solucion nueva.
- **Cuestionar supuestos**: No asumir — verificar en el codigo antes de actuar.
- **Idioma**: UI y contenido en espanol. Codigo (variables, funciones, clases) en ingles.

## Flujo de Trabajo con Comandos

Este proyecto usa **custom commands** (slash commands) para estandarizar el trabajo:

| Comando | Uso |
|---------|-----|
| `/plan [feature]` | Genera plan paso a paso en `docs/changes/` sin escribir codigo |
| `/develop [plan o descripcion]` | Implementa una feature siguiendo un plan o descripcion |
| `/test [archivo o feature]` | Escribe tests para el codigo indicado |
| `/commit [instrucciones]` | Crea commit limpio con convenciones del proyecto |
| `/check` | Ejecuta lint + analisis + tests + verificaciones |
| `/review [archivo o PR]` | Revisa codigo y da feedback por categorias |
| `/explain [tema]` | Explica conceptos en profundidad (modo aprendizaje) |
| `/deploy-check` | Verifica que todo esta listo para produccion |
| `/update-docs` | Actualiza documentacion segun cambios recientes |

### Flujo recomendado para features nuevas

```
/plan nombre-de-feature     → Genera plan en docs/changes/
/develop @docs/changes/...  → Implementa el plan
/test archivo-o-feature     → Escribe tests
/check                      → Verifica calidad
/commit                     → Commit con convenciones
/update-docs                → Actualiza docs si es necesario
```

## Sobre el Proyecto

AMI (Alpha Markets Institute) es una plataforma educativa de trading. Filosofia: "Criterio > Senales", "Proceso > Resultados rapidos". NO es un grupo de senales — ensena a pensar, no a copiar.

## Comandos de Desarrollo

```bash
# Desarrollo (levanta server, queue, logs y Vite en paralelo)
composer run dev

# Solo backend
php artisan serve

# Solo frontend (Vite)
npm run dev

# Build produccion
npm run build
```

### Testing

```bash
# Correr todos los tests
composer run test

# Un test especifico
php artisan test --filter=NombreDelTest

# Un archivo
php artisan test tests/Feature/MiTest.php
```

La BD de test es `ami_platform_test` (PostgreSQL). Configurada en `phpunit.xml`.

**Estructura de tests**:
- `tests/Feature/` — controllers, rutas, integraciones (usan `RefreshDatabase`)
- `tests/Unit/` — services, models, logica aislada
- Patron: **Arrange → Act → Assert**
- Nombres descriptivos: `test_student_cannot_access_admin_panel`
- Usar factories para datos de prueba

### Linting y Analisis

```bash
# Formatear codigo (Laravel Pint, preset: laravel)
composer run lint

# Verificar sin modificar
composer run lint:check

# Analisis estatico (PHPStan/Larastan)
composer run analyse

# Todo junto: lint + analyse + test
composer run check
```

## Stack Tecnico

- Laravel 12 + Livewire 4 + Filament 5 + Tailwind CSS 4 + Alpine.js
- PostgreSQL 16, Redis 7 (session, cache, queue)
- Vite 7, Node.js 20
- Python 3.12 (workers via GitHub Actions)
- Pagos: Stripe + Laravel Cashier v16
- Email: Resend
- Locale: `es` / `es_CO`

## Arquitectura Clave

### Separacion Laravel vs Workers

- **Laravel** = web/API unicamente. Nunca batch processing.
- **Workers** = Python en `workers/`. Corren via GitHub Actions (free tier). Se comunican con Laravel via API autenticada (`X-API-Key`), nunca acceso directo a BD.

### Roles y Acceso

Tres roles via Spatie: `admin`, `instructor`, `student`.

- Admin panel: `/admin` (Filament 5)
- Instructor panel: `/instructor` (Filament 5)
- Premium = suscripcion activa OR rol admin/instructor OR AccessGrant especial
- Metodo clave: `User::hasPremiumAccess()`

### Modulos Principales

| Modulo | Descripcion |
|--------|------------|
| Education | Cursos, lecciones, enrollments, progreso |
| Payments | Stripe subscriptions (planes, no cursos individuales) |
| Journal | Trading journal automatico (API) + manual (bitacora) |
| Broker Connections | Conexiones a brokers (Binance, MT4/MT5), CSV import |
| Live Classes | Clases en vivo con calendario y links tokenizados |
| Gamification | XP, achievements, streaks, ranking |

### API Interna (Journal)

Prefijo: `/api/internal/journal/`
Auth: Header `X-API-Key` → middleware `JournalApiAuth`
Keys almacenadas en tabla `journal_api_keys` con permisos granulares.

Endpoints: `entries`, `summaries`, `connections`, `calculate-stats`, `health`, `users-with-trades`

### Paneles Filament 5

Estructura de resources en `app/Filament/Resources/`:
```
NombreResource/
├── NombreResource.php      # Definicion del resource
├── Pages/                  # List, Create, Edit
├── Schemas/                # Form schema separado
└── Tables/                 # Table schema separado
```

## Estandares de Codigo

### PHP / Laravel

- Formateo: Laravel Pint (preset `laravel`)
- Analisis: PHPStan/Larastan
- Controllers flacos: logica de negocio en Services, no en controllers
- Eloquent: usar scopes para queries reutilizables, `with()` para eager loading (evitar N+1)
- Validacion: en FormRequest o inline con `$request->validate()`
- Nunca usar raw SQL sin bindings

### Blade / Frontend

- Tailwind CSS 4 con design system dark-first (TradingView-inspired)
- Alpine.js para interactividad en el cliente
- Componentes anonimos en `resources/views/components/`
- UI en espanol, clases CSS semanticas (`text-bullish`, `text-bearish`, `bg-surface-*`)

### Seguridad

- CSRF: todos los formularios con `@csrf`
- Mass assignment: usar `$fillable` explicitamente
- Output escaping: `{{ }}` (no `{!! !!}` sin necesidad)
- Credenciales sensibles: cast `encrypted` en modelos, nunca en logs
- Headers: SecurityHeaders middleware (CSP, HSTS, X-Frame-Options)
- Rate limiting: configurado por tipo de ruta (web, auth, api, webhooks)

### Git

- Branch principal: `main` (produccion), `development` (desarrollo)
- Commits: `tipo: descripcion` (feat, fix, docs, refactor, test, chore)
- Siempre incluir: `Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>`
- No hacer push/merge sin que el usuario lo pida

## Gotchas Importantes

### Filament 5 (NO es Filament 3)

- `Section`, `Grid`, `Fieldset` → `Filament\Schemas\Components\*` (NO `Filament\Forms\Components\*`)
- Form fields (`TextInput`, `Select`, `Toggle`) SI van en `Filament\Forms\Components\*`
- `$view` en Pages: `protected string $view` (NO static)
- `form()` usa `Schema $schema` como parametro
- `infolist()` usa `Schema $schema` (NO `Infolist $infolist`)

### Blade

- Componentes anonimos DEBEN vivir en `resources/views/components/` (no en `resources/views/layouts/`)

### Base de Datos

- PostgreSQL, no MySQL. Usar `jsonb` en lugar de `json` para columnas JSON.
- `avg_trade_duration` en `journal_summaries` es integer (castear a int antes de guardar)

### Config

- Feature flags en `config/journal.php`: `enabled`, `connections_enabled`, `csv_upload_enabled`
- Credenciales de broker encriptadas via cast `encrypted` en el modelo

## Estructura de Archivos Clave

```
docs/                       # Documentacion del proyecto
├── MASTER_PLAN.md          # Roadmap de 7 fases
├── SYSTEM_ARCHITECTURE.md  # Diagramas y decisiones
├── MODULE_TRADING_JOURNAL.md
├── ABOUT_AMI.md
└── changes/                # Planes de features (generados con /plan)

config/journal.php          # Feature flags del journal
routes/api.php              # API interna para workers
routes/web.php              # Rutas estudiante/publicas
deploy/                     # Scripts de deploy y nginx config
.github/workflows/          # GitHub Actions (reminders, import, stats)
workers/                    # Python workers (trade_importer, stats_analyzer)
.claude/commands/           # Custom slash commands para flujo de trabajo
```

## Deploy

Script: `deploy/deploy.sh` — 9 pasos con maintenance mode.
Produccion: Nginx + PHP-FPM + supervisord en `/var/www/ami`.

GitHub Actions secrets requeridos: `JOURNAL_API_KEY`, `APP_URL`, `METAAPI_TOKEN` (opcional).
