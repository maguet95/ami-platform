# ARQUITECTURA DEL SISTEMA — Plataforma AMI

## 1. Visión General

La plataforma AMI sigue una arquitectura de **servicios separados por responsabilidad**, donde cada componente tiene un rol único y bien definido. No es microservicios (sería over-engineering para esta escala), pero tampoco es un monolito acoplado. Es un **monolito modular con servicios externos especializados**.

---

## 2. Diagrama de Arquitectura de Alto Nivel

```
┌─────────────────────────────────────────────────────────────────────┐
│                         INTERNET / USUARIOS                         │
└──────────────────────────────┬──────────────────────────────────────┘
                               │
                         ┌─────▼─────┐
                         │ Cloudflare │  CDN + WAF + SSL
                         │   / CDN    │
                         └─────┬─────┘
                               │
              ┌────────────────▼────────────────┐
              │          NGINX REVERSE PROXY      │
              │     (Rate Limiting, Static Files)  │
              └────────────────┬────────────────┘
                               │
         ┌─────────────────────▼─────────────────────┐
         │              LARAVEL APPLICATION            │
         │          (PHP 8.3+ / PHP-FPM)               │
         │                                             │
         │  ┌───────────┐ ┌───────────┐ ┌───────────┐ │
         │  │  Módulo    │ │  Módulo    │ │  Módulo    │ │
         │  │  Público   │ │ Educativo  │ │  Pagos    │ │
         │  └───────────┘ └───────────┘ └───────────┘ │
         │  ┌───────────┐ ┌───────────┐               │
         │  │  Módulo    │ │  Filament  │               │
         │  │  Journal   │ │  Admin     │               │
         │  │ (opcional) │ │  Panel     │               │
         │  └───────────┘ └───────────┘               │
         │                                             │
         │  ┌─────────────────────────────────────┐   │
         │  │         API INTERNA (workers)         │   │
         │  │    POST /api/internal/journal/*       │   │
         │  │    Auth: API Key + IP Whitelist       │   │
         │  └─────────────────────────────────────┘   │
         └──────┬──────────────┬──────────────┬──────┘
                │              │              │
         ┌──────▼──────┐ ┌────▼────┐ ┌───────▼───────┐
         │ PostgreSQL   │ │  Redis   │ │  File Storage  │
         │    16        │ │   7      │ │  (S3/Local)    │
         │              │ │          │ │                │
         │ - users      │ │ - cache  │ │ - avatars      │
         │ - courses    │ │ - sessions│ │ - course media │
         │ - enrollments│ │ - queues │ │ - documents    │
         │ - payments   │ │ - rate   │ │                │
         │ - journal *  │ │   limits │ │                │
         └──────────────┘ └─────────┘ └───────────────┘
                │
                │ (lectura compartida)
                │
┌ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ┐
│              VPS EXTERNO (Workers Python)                     │
│                                                              │
│  ┌──────────────────┐     ┌──────────────────┐              │
│  │  Worker:          │     │  Worker:          │              │
│  │  Trade Importer   │     │  Stats Analyzer   │              │
│  │                   │     │                   │              │
│  │  - Broker APIs    │     │  - Agrega datos   │              │
│  │  - CSV parsing    │     │  - Calcula PnL    │              │
│  │  - Normalización  │     │  - Genera resumen │              │
│  └────────┬─────────┘     └────────┬─────────┘              │
│           │                        │                         │
│           └────────┬───────────────┘                         │
│                    │                                         │
│              ┌─────▼─────┐                                   │
│              │ HTTP Client│ ──── API Key Auth ────> Laravel   │
│              │ (requests) │      POST /api/internal/*         │
│              └───────────┘                                   │
│                                                              │
│  ┌──────────────────┐                                        │
│  │  Worker:          │  (futuro — ejemplo de extensibilidad) │
│  │  [Nuevo Worker]   │                                       │
│  └──────────────────┘                                        │
└ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ┘
```

---

## 3. Responsabilidades por Componente

### 3.1 Laravel Application (Servidor Web Principal)

| Responsabilidad | Descripción |
|----------------|-------------|
| **Servir HTTP** | Todas las páginas públicas, dashboard educativo, admin panel |
| **Autenticación** | Registro, login, sesiones, recuperación de contraseña |
| **Autorización** | Roles, permisos, policies, gates, middleware |
| **API pública** | Endpoints para el frontend (Livewire/AJAX) |
| **API interna** | Endpoints autenticados para que workers escriban datos |
| **Orquestación** | Despachar eventos, notificaciones, emails |
| **Lectura de journal** | Presentar datos del Trading Journal (solo lectura) |
| **Admin** | Filament PHP para gestión completa |

**Laravel NO hace:**
- Procesamiento batch de datos de trading
- Conexión directa a broker APIs
- Cálculos pesados de estadísticas
- Importación masiva de datos
- Scraping o polling de servicios externos

### 3.2 PostgreSQL (Base de Datos)

| Responsabilidad | Descripción |
|----------------|-------------|
| **Almacenamiento primario** | Todos los datos de la plataforma |
| **Integridad referencial** | Foreign keys, constraints, transacciones ACID |
| **Índices optimizados** | Para lectura rápida del journal y catálogo |
| **JSON nativo** | Para metadatos flexibles (tags, configuración de cursos) |
| **Full-text search** | Para búsqueda de cursos y contenido (futuro) |

**PostgreSQL NO tiene:**
- Esquemas separados por módulo (se usa prefijo en tablas si es necesario, pero por ahora esquema único)
- Acceso directo desde workers (los workers pasan por la API de Laravel)

### 3.3 Redis

| Responsabilidad | Descripción |
|----------------|-------------|
| **Caché** | Query cache, page cache, config cache |
| **Sesiones** | Sesiones de usuario (más rápido que DB) |
| **Colas** | Jobs de Laravel: emails, notificaciones, tareas ligeras |
| **Rate Limiting** | Throttling de API y formularios |

**Redis NO es:**
- Cola de mensajes para workers externos (los workers consumen la API HTTP, no colas Redis directamente)
- Base de datos persistente

### 3.4 Workers Externos (Python / VPS)

| Responsabilidad | Descripción |
|----------------|-------------|
| **Procesamiento batch** | Importar, parsear, normalizar datos de trading |
| **Cálculos pesados** | Estadísticas, agregaciones, análisis |
| **Conexión a brokers** | APIs externas de brokers, parseo de CSV |
| **Escritura vía API** | Enviar resultados procesados a Laravel por HTTP |
| **Scheduling propio** | Cron o scheduler interno, no depende de Laravel Scheduler |

**Workers NO hacen:**
- Servir HTTP público
- Manejar autenticación de usuarios
- Acceder directamente a la base de datos
- Renderizar vistas o frontend
- Enviar emails a usuarios (eso lo hace Laravel)

### 3.5 Nginx

| Responsabilidad | Descripción |
|----------------|-------------|
| **Reverse proxy** | Proxy a PHP-FPM |
| **Static files** | Servir CSS, JS, imágenes compiladas |
| **SSL termination** | Manejo de certificados HTTPS |
| **Rate limiting** | Primera capa de protección |
| **Gzip/Brotli** | Compresión de respuestas |

---

## 4. Flujos de Datos

### 4.1 Flujo: Usuario navega el sitio público

```
Usuario ──> Cloudflare ──> Nginx ──> Laravel (Blade + Livewire)
                                         │
                                         ├──> PostgreSQL (cursos, contenido)
                                         └──> Redis (caché de páginas)
```

### 4.2 Flujo: Estudiante consume un curso

```
Estudiante (autenticado) ──> Nginx ──> Laravel
                                          │
                                          ├──> Verificar enrollment (PostgreSQL)
                                          ├──> Cargar lección (PostgreSQL + File Storage)
                                          ├──> Registrar progreso (PostgreSQL)
                                          └──> Servir video embebido (Vimeo/YouTube URL)
```

### 4.3 Flujo: Pago de suscripción

```
Estudiante ──> Laravel (página de checkout)
                  │
                  ├──> Stripe API (crear sesión de pago)
                  │       │
                  │       └──> Stripe redirige a usuario a página de pago
                  │                │
                  │                └──> Stripe envía webhook a Laravel
                  │                         │
                  └──> Laravel procesa webhook:
                          ├──> Crear/actualizar Subscription (PostgreSQL)
                          ├──> Otorgar acceso a cursos
                          ├──> Generar factura PDF
                          └──> Enviar email de confirmación (Redis queue → email)
```

### 4.4 Flujo: Trading Journal (datos procesados por worker)

```
┌─────── VPS EXTERNO ───────────────────────────────┐
│                                                    │
│  Broker API / CSV ──> Worker Python                │
│                          │                         │
│                          ├──> Parsear trades       │
│                          ├──> Calcular métricas    │
│                          └──> POST /api/internal/  │
│                                journal/entries     │
└──────────────────────────┬─────────────────────────┘
                           │
                     (HTTPS + API Key)
                           │
                    ┌──────▼──────┐
                    │   Laravel    │
                    │              │
                    ├──> Validar request
                    ├──> Verificar API key
                    ├──> Guardar en PostgreSQL
                    └──> Log de auditoría
                           │
                    ┌──────▼──────┐
                    │  Estudiante  │
                    │  (lectura)   │
                    │              │
                    └──> Dashboard de journal (solo lectura)
                            ├──> Tabla de trades
                            ├──> Estadísticas
                            └──> Gráficos
```

### 4.5 Flujo: Agregar un nuevo worker futuro

```
1. Crear nuevo worker en repositorio ami-workers
2. Solicitar API key al admin de Laravel
3. Worker consume API interna existente o se crea nuevo endpoint
4. Worker escribe datos vía POST HTTP autenticado
5. Laravel presenta los datos al usuario

NO SE NECESITA:
- Modificar la arquitectura
- Cambiar la base de datos
- Tocar el frontend existente (salvo crear nueva vista si aplica)
```

---

## 5. Fronteras de Seguridad

### 5.1 Capas de Seguridad

```
CAPA 1: Cloudflare / CDN
├── WAF (Web Application Firewall)
├── DDoS protection
├── Bot management
└── SSL/TLS termination

CAPA 2: Nginx
├── Rate limiting por IP
├── Headers de seguridad (CSP, HSTS, X-Frame-Options)
├── Bloqueo de rutas internas (/api/internal/* solo IPs whitelisted)
└── Request size limits

CAPA 3: Laravel Application
├── CSRF protection (todas las forms)
├── Rate limiting por endpoint (throttle middleware)
├── Autenticación (bcrypt passwords, sesiones seguras)
├── Autorización (policies, gates, middleware de roles)
├── Validación de input (Form Requests)
├── Prepared statements (Eloquent, sin SQL injection)
└── XSS prevention (Blade escaping por defecto)

CAPA 4: API Interna (workers)
├── API key authentication (header X-API-Key)
├── IP whitelist (solo IPs de VPS conocidos)
├── Rate limiting estricto por API key
├── Validación de payload
└── Audit logging de toda escritura

CAPA 5: Base de Datos
├── Credenciales rotadas periódicamente
├── Conexión solo desde app server (no pública)
├── Backups encriptados
└── Principio de mínimo privilegio (user de app ≠ user de admin)
```

### 5.2 Matriz de Acceso

| Componente | Accede a PostgreSQL | Accede a Redis | Accede a Internet | Recibe HTTP público |
|------------|:---:|:---:|:---:|:---:|
| **Laravel** | Directo (Eloquent) | Directo | Sí (Stripe, email) | Sí (vía Nginx) |
| **Workers** | NO (vía API) | NO | Sí (brokers, API Laravel) | NO |
| **Filament Admin** | Vía Laravel | Vía Laravel | No directo | Sí (sub-ruta de Laravel) |
| **Nginx** | NO | NO | NO | Sí |
| **Redis** | NO | — | NO | NO |
| **PostgreSQL** | — | NO | NO | NO |

### 5.3 Principio Crítico: Workers NO tocan la DB

```
CORRECTO:
  Worker ──(HTTPS + API Key)──> Laravel API ──> PostgreSQL

INCORRECTO (NUNCA):
  Worker ──(conexión directa)──> PostgreSQL
```

**Razones:**
- Laravel valida y sanitiza todos los datos antes de escribir
- Un worker comprometido no puede corromper la DB directamente
- Se puede revocar una API key sin tocar la DB
- Audit trail completo en Laravel
- Los workers pueden correr en infraestructura menos confiable

---

## 6. Estrategia de Escalabilidad

### 6.1 Escalabilidad Vertical (Fase inicial)

Para el lanzamiento y primeros meses:
- **Un VPS** para Laravel + PostgreSQL + Redis + Nginx
- **Un VPS separado** para workers (cuando se activen)
- Suficiente para cientos de usuarios concurrentes

### 6.2 Escalabilidad Horizontal (Crecimiento)

Cuando el tráfico lo requiera:

```
ANTES (un servidor):
┌─────────────────────┐
│ Nginx + Laravel +   │
│ PostgreSQL + Redis   │
└─────────────────────┘

DESPUÉS (separado):
┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐
│ Nginx    │  │ Laravel  │  │ PostgreSQL│  │ Redis    │
│ (LB)     │──│ App x2   │──│ (managed) │  │ (managed)│
└──────────┘  └──────────┘  └──────────┘  └──────────┘
```

### 6.3 Qué escala independientemente

| Componente | Cómo escala | Cuándo |
|------------|-------------|--------|
| **Laravel** | Más instancias PHP-FPM detrás de load balancer | > 500 usuarios concurrentes |
| **PostgreSQL** | Read replicas, o migrar a managed DB (RDS, DO Managed) | > 100K registros de journal |
| **Redis** | Cluster o managed Redis | > 10K sesiones simultáneas |
| **Workers** | Más instancias del mismo worker, o workers especializados | > volumen de datos de brokers |
| **Storage** | Migrar a S3/R2 con CDN | > 50GB de contenido multimedia |

### 6.4 Decisiones que permiten escalar sin re-arquitectura

1. **Sesiones en Redis** (no en disco) → permite múltiples instancias Laravel
2. **File storage abstracto** (Laravel Filesystem) → cambiar de local a S3 sin tocar código
3. **Workers vía API HTTP** → escalar workers sin tocar Laravel
4. **Módulo Journal desacoplado** → puede moverse a servicio independiente si crece
5. **Colas en Redis** → migrar a SQS o RabbitMQ si necesario

---

## 7. Por Qué el Trading Journal DEBE Estar Aislado

### 7.1 Razones Técnicas

| Razón | Explicación |
|-------|-------------|
| **Volumen de datos** | Un journal activo genera miles de registros. Esto NO debe afectar las queries del LMS. |
| **Origen de datos externo** | Los datos vienen de workers, no de usuarios. Flujo de escritura completamente diferente. |
| **Patrón de acceso** | El LMS es lectura-escritura interactiva. El journal es lectura masiva de datos pre-procesados. |
| **Ciclo de vida** | El journal puede NO existir. La plataforma educativa debe funcionar 100% sin él. |
| **Riesgo** | Un bug en el journal no debe tumbar el sistema de cursos o pagos. |

### 7.2 Cómo se aísla

```
app/
├── Modules/
│   ├── Public/          ← Sitio público (siempre activo)
│   ├── Education/       ← LMS core (siempre activo)
│   ├── Payments/        ← Stripe/membresías (siempre activo)
│   └── Journal/         ← Trading Journal (OPCIONAL)
│       ├── Models/
│       ├── Controllers/
│       ├── Routes/
│       ├── Views/
│       └── JournalServiceProvider.php  ← Se registra solo si feature flag activo
```

- El `JournalServiceProvider` se registra condicionalmente
- Si se elimina el directorio `Journal/`, la plataforma sigue funcionando
- Las migraciones del journal tienen prefijo propio
- No hay foreign keys entre tablas del journal y tablas del LMS (solo `user_id`)

---

## 8. Por Qué los Workers Son Externos

### 8.1 Razones Técnicas

| Razón | Explicación |
|-------|-------------|
| **Lenguaje diferente** | Python tiene ecosistema superior para datos financieros (pandas, numpy, ccxt). |
| **Recursos diferentes** | Workers pueden necesitar mucha RAM/CPU. No deben competir con el web server. |
| **Ciclo de deploy independiente** | Actualizar un worker no requiere re-deployar Laravel. |
| **Aislamiento de fallos** | Un worker caído no afecta la web. La web caída no afecta a los workers. |
| **Seguridad** | Workers corren en VPS separado. Si se comprometen, no tienen acceso a la DB ni al código Laravel. |
| **Scheduling propio** | Workers controlan su propio cron. No dependen de Laravel Scheduler para tareas pesadas. |

### 8.2 Cómo agregar un nuevo worker en el futuro

```
Paso 1: Definir qué datos produce el worker
Paso 2: Crear endpoint en Laravel API interna (si no existe)
Paso 3: Generar API key para el nuevo worker
Paso 4: Implementar worker en repositorio ami-workers
Paso 5: Deploy en VPS
Paso 6: (Opcional) Crear vista en Laravel para presentar los datos

Impacto en sistema existente: MÍNIMO
- Solo se agrega un endpoint (si es necesario)
- Solo se agregan migraciones (si hay nuevas tablas)
- El resto del sistema NO se toca
```

---

## 9. Resumen de Repositorios

| Repositorio | Tecnología | Contenido |
|-------------|------------|-----------|
| `ami-platform` | Laravel (PHP) | Toda la plataforma web: público, LMS, pagos, journal (lectura), admin |
| `ami-workers` | Python | Todos los workers externos: importación, análisis, procesamiento batch |

Dos repositorios. Dos ciclos de vida. Una API los conecta.
