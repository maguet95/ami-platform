# Revisión Completa del Proyecto AMI — Hallazgos y Correcciones

## 1. Auditoría de Seguridad (OWASP)

### Hallazgos con Corrección

#### SEC-01: Campos de gamificación en User `$fillable` (MEDIA)
**Archivo:** `app/Models/User.php:61-63`
**Riesgo:** `total_xp`, `current_streak`, `longest_streak` en `$fillable` podrían manipularse si algún controller usara `$request->all()`. Actualmente todos usan `validated()`, pero es defense-in-depth.
**Fix:** Remover de `$fillable` — se actualizan via `increment()` y `update()` internos.
**Estado:** ✅ Corregido

#### SEC-02: API interna sin rate limiting (BAJA)
**Archivo:** `routes/api.php`
**Riesgo:** Rutas `/api/internal/journal/*` no tienen throttle. API key comprometido = requests ilimitados.
**Fix:** Agregar `throttle:api` al grupo.
**Estado:** ✅ Corregido

#### SEC-03: `PublicProfileUpdateRequest` no valida todos los campos que acepta el controller (MEDIA)
**Archivo:** `app/Http/Requests/PublicProfileUpdateRequest.php`
**Riesgo:** El controller `ProfileController::updatePublic()` acepta `headline`, `instagram_handle`, `youtube_handle`, `linkedin_url` pero el FormRequest no los valida.
**Fix:** Agregar reglas de validación.
**Estado:** ✅ Corregido

#### SEC-04: Exportaciones sin límite cargan todo en memoria (MEDIA)
**Archivo:** `app/Http/Controllers/JournalController.php:164`
**Riesgo:** `getFilteredTrades()` usa `->get()` sin límite. Miles de trades = OOM.
**Fix:** Agregar `->limit(5000)`.
**Estado:** ✅ Corregido

### Documentados (No requieren fix inmediato)

#### SEC-05: `trustProxies(at: '*')` (BAJA en Render/Nginx)
**Archivo:** `bootstrap/app.php:15`
**Nota:** Aceptable para Render.com que controla los proxies. Si se despliega en otro hosting, configurar IPs específicas.

#### SEC-06: CSP con `unsafe-inline` y `unsafe-eval` (BAJA)
**Archivo:** `app/Http/Middleware/SecurityHeaders.php:35`
**Nota:** Necesario para Livewire/Alpine.js/Filament. Aceptable dado el stack.

### Verificaciones Positivas ✅
- ✅ 21/21 modelos tienen `$fillable` (no hay `$guarded = []`)
- ✅ `BrokerConnection.credentials` → cast `encrypted`
- ✅ `JournalApiKey.findByKey()` → hash SHA-256 en DB (timing-safe)
- ✅ CSRF excluido solo para `stripe/*`
- ✅ Stripe webhook verifica firma (extiende CashierWebhookController)
- ✅ Todas las rutas auth bajo `middleware('auth')`
- ✅ Rate limiting: web(120), auth(5), contact(3), webhooks(60), checkout(10)
- ✅ Security headers completos: X-Frame-Options, HSTS, CSP, etc.
- ✅ Controllers usan `$request->validated()` o `$request->validate()`
- ✅ Ownership checks en ManualJournal y BrokerConnection
- ✅ Premium access checks en Journal, Connections, cursos
- ✅ No hay SQL raw sin bindings
- ✅ CSV importer solo parsea datos, no ejecuta código
- ✅ XSS: `{!! !!}` solo con contenido admin/trusted o hardcoded

---

## 2. Auditoría de Rendimiento

### Correcciones

#### PERF-01: Query duplicada en StudentCourseController (MEDIA)
**Archivo:** `app/Http/Controllers/StudentCourseController.php:41-49, 69-77`
**Problema:** `$course->lessons()->pluck('lessons.id')` hace query extra innecesaria.
**Fix:** Usar la colección ya cargada con `$course->load()`.
**Estado:** ✅ Corregido

#### PERF-02: JournalController userPairs query ineficiente (BAJA)
**Archivo:** `app/Http/Controllers/JournalController.php:78-87`
**Fix:** Usar query directa a TradePair con subquery.
**Estado:** ✅ Corregido

#### PERF-03: LiveClassController dos queries en vez de join (BAJA)
**Archivo:** `app/Http/Controllers/LiveClassController.php:16-24`
**Fix:** Usar whereHas en vez de whereIn con subquery.
**Estado:** ✅ Corregido

### Verificaciones Positivas ✅
- ✅ Paginación en listas (12 bitácora, 20 journal)
- ✅ `with()` consistente para eager loading
- ✅ `preventLazyLoading` activo en dev

---

## 3. Arquitectura (Solo Documentación — NO implementar)

- **ARCH-01:** Duplicación Admin/Instructor (~42 archivos) → Extraer traits compartidos (futuro)
- **ARCH-02:** `TradingStatsService` (357 líneas) → Separar Automatic vs Manual (futuro)
- **ARCH-03:** `JournalApiController` (265 líneas) → Extraer a Services (futuro)
- **ARCH-04:** Filtros duplicados Journal vs ManualJournal → Trait compartido (futuro)
- **ARCH-05:** `User` model (275 líneas) → Aceptable para modelo central

---

## 4. Código Muerto

- Eliminado: `resources/views/welcome.blade.php` (no referenciada)
- Actualizado `.gitignore`: `*.odt`, `.~lock.*`

---

## 5. Consistencia

- ✅ URLs en español, código en inglés — consistente
- ✅ API responses formato `{status, data/message}` — consistente
- ✅ Corregido `PublicProfileUpdateRequest` con campos faltantes

---

## 6. Tests — Ver archivos de test creados

---

## 7. Config y Deploy — Ver correcciones aplicadas
