# PLAN MAESTRO — Plataforma AMI

## Contexto

AMI es un instituto educativo de trading que necesita una plataforma web profesional, modular y escalable a largo plazo. El sistema se compone de cuatro dominios claramente separados: sitio público institucional, plataforma educativa con membresías, un módulo premium opcional de Trading Journal (solo lectura), y workers externos en Python para procesamiento batch. Este plan define la hoja de ruta completa desde la infraestructura base hasta la operación en producción.

---

## Principios Arquitectónicos

| Principio | Descripción |
|---|---|
| **Separación de responsabilidades** | Laravel = web/API. Workers = procesamiento batch. Nunca se mezclan. |
| **Modularidad** | Cada dominio funcional es un módulo independiente con contratos claros. |
| **Journal desacoplado** | El Trading Journal es un módulo opcional premium. Se conecta vía API, nunca acoplado al core. |
| **Workers no son servidores web** | Los workers Python corren en VPS Linux, consumen colas/APIs, nunca sirven HTTP público. |
| **Laravel no hace batch** | Laravel despacha trabajos, los workers los ejecutan. Laravel solo orquesta. |
| **Escalabilidad progresiva** | Cada fase entrega valor funcional completo. No se construye lo que no se necesita aún. |

---

## Decisiones Técnicas Confirmadas

| Decisión | Elección | Justificación |
|----------|----------|---------------|
| **Frontend** | Blade + Livewire 3 | Stack TALL completo. 100% ecosistema Laravel. Filament ya usa Livewire, consistencia total. Componentes reactivos sin JS custom. |
| **Base de datos** | PostgreSQL 16 | Superior para datos financieros/trading. Mejor soporte de JSON, full-text search, tipos avanzados. |
| **Admin panel** | Filament PHP 3 | Gratuito, moderno, TALL stack nativo. Productividad máxima para CRUDs y dashboards. |
| **Pasarela de pagos** | Stripe + Laravel Cashier | Integración nativa con Laravel. API excelente. Soporte global. |
| **Stack completo** | TALL Stack | **T**ailwind CSS + **A**lpine.js + **L**ivewire + **L**aravel. Ecosistema unificado. |

### Decisiones Pendientes (resolver en Phase 0)

1. **Hosting:** VPS con Laravel Forge vs PaaS (Laravel Cloud, Railway)
2. **Video hosting:** Vimeo OTT vs YouTube unlisted vs almacenamiento propio (S3)

---

## Mapa de Dependencias entre Fases

```
PHASE 0 (Fundación)
    └──> PHASE 1 (Sitio Público) ──── MVP PÚBLICO
    └──> PHASE 2 (Plataforma Educativa) ──── MVP COMPLETO
              └──> PHASE 3 (Pagos y Membresías)
                        └──> PHASE 4 (Trading Journal) ──── PREMIUM OPCIONAL
                        └──> PHASE 5 (Workers Externos)
                                  └──> PHASE 6 (Producción y Operaciones)
```

---

## PHASE 0 — Fundación e Infraestructura

**Alcance:** Establecer la base técnica sobre la que se construye todo. Sin esto, nada avanza.
**Clasificación:** PRE-MVP (obligatorio)

### Módulo 0.1 — Proyecto Laravel Base

**Entregable 0.1.1 — Scaffold del proyecto**
- [ ] Instalar Laravel (última versión LTS)
- [ ] Configurar estructura de directorios modular (`app/Modules/`)
- [ ] Configurar `.env.example` con todas las variables necesarias
- [ ] Configurar `docker-compose.yml` para desarrollo local (PHP 8.3+, PostgreSQL 16, Redis 7, Mailpit)
- [ ] Instalar y configurar Livewire 3
- [ ] Configurar Vite + Tailwind CSS 4 + Alpine.js (TALL Stack)

**Entregable 0.1.2 — Configuración de calidad de código**
- [ ] Instalar y configurar Pint (formato de código PHP)
- [ ] Instalar y configurar Larastan (análisis estático nivel 5+)
- [ ] Configurar ESLint + Prettier para assets frontend
- [ ] Crear `Makefile` o scripts `composer` para lint, test, build

**Entregable 0.1.3 — Testing base**
- [ ] Configurar PHPUnit con base de datos de testing
- [ ] Crear test base de salud (`/health` endpoint)
- [ ] Configurar factories y seeders base

### Módulo 0.2 — CI/CD y Repositorio

**Entregable 0.2.1 — Pipeline de integración continua**
- [ ] Configurar GitHub Actions: lint + tests en cada PR
- [ ] Configurar GitHub Actions: build de assets frontend
- [ ] Definir estrategia de branching (trunk-based o gitflow simplificado)
- [ ] Proteger rama `main` (require PR + checks passing)

**Entregable 0.2.2 — Estrategia de despliegue**
- [ ] Definir entorno de staging vs producción
- [ ] Configurar despliegue automatizado (Laravel Forge, Deployer, o similar)
- [ ] Documentar proceso de deploy y rollback

### Módulo 0.3 — Base de Datos y Migraciones Core

**Entregable 0.3.1 — Esquema base**
- [ ] Configurar PostgreSQL 16 como motor de base de datos
- [ ] Crear migraciones para tablas core: `users`, `roles`, `permissions`
- [ ] Crear seeders para roles iniciales (admin, student, guest)
- [ ] Configurar Redis para caché y colas

---

## PHASE 1 — Sitio Web Público Institucional

**Alcance:** Web pública de AMI. Presentación institucional, información de cursos, landing pages. Sin autenticación.
**Dependencias:** Phase 0 completada.
**Clasificación:** MVP PÚBLICO

### Módulo 1.1 — Layout y Sistema de Diseño

**Entregable 1.1.1 — Design system base**
- [ ] Definir paleta de colores institucional, tipografía y espaciado
- [ ] Crear componentes Blade reutilizables: header, footer, nav, hero, cards, buttons, CTA
- [ ] Implementar layout base responsive (mobile-first)
- [ ] Crear sistema de íconos (Heroicons o similar)

**Entregable 1.1.2 — Navegación y estructura**
- [ ] Implementar navbar institucional con mega-menu o dropdown
- [ ] Implementar footer con links, redes sociales, legal
- [ ] Implementar breadcrumbs y navegación secundaria
- [ ] Soporte SEO: meta tags dinámicos, Open Graph, sitemap.xml, robots.txt

### Módulo 1.2 — Páginas Públicas

**Entregable 1.2.1 — Páginas estáticas institucionales**
- [ ] Home page (hero, propuesta de valor, testimonios, CTA)
- [ ] Sobre nosotros / Quiénes somos
- [ ] Metodología de enseñanza
- [ ] Contacto (formulario con validación + envío de email)
- [ ] Términos y condiciones / Política de privacidad

**Entregable 1.2.2 — Catálogo público de cursos**
- [ ] Página de listado de cursos (grid con filtros)
- [ ] Página de detalle de curso (descripción, temario, precio, CTA de inscripción)
- [ ] Modelo `Course` con campos: título, slug, descripción, imagen, precio, nivel, estado
- [ ] Panel admin básico para gestionar cursos (CRUD)

**Entregable 1.2.3 — Blog / Contenido educativo (opcional en MVP)**
- [ ] Modelo `Post` con campos: título, slug, contenido, imagen, categoría, autor
- [ ] Listado de artículos con paginación
- [ ] Vista de artículo individual
- [ ] CRUD de posts desde admin

### Módulo 1.3 — Admin Panel Base

**Entregable 1.3.1 — Panel de administración**
- [ ] Instalar y configurar Filament PHP 3
- [ ] Dashboard administrativo con métricas básicas
- [ ] CRUD de cursos desde admin
- [ ] CRUD de páginas/contenido estático (opcional)
- [ ] Gestión básica de formularios de contacto recibidos

---

## PHASE 2 — Plataforma Educativa (Usuarios y Cursos)

**Alcance:** Sistema de autenticación, perfiles de usuario, inscripción a cursos, consumo de contenido educativo.
**Dependencias:** Phase 1 completada (catálogo de cursos existe).
**Clasificación:** MVP COMPLETO

### Módulo 2.1 — Autenticación y Usuarios

**Entregable 2.1.1 — Sistema de autenticación**
- [ ] Registro de usuarios (nombre, email, password)
- [ ] Login / Logout
- [ ] Recuperación de contraseña por email
- [ ] Verificación de email
- [ ] Protección con rate limiting y CSRF

**Entregable 2.1.2 — Perfil de usuario**
- [ ] Página de perfil editable (nombre, avatar, bio, datos de contacto)
- [ ] Cambio de contraseña
- [ ] Historial de actividad básico

**Entregable 2.1.3 — Roles y permisos**
- [ ] Implementar sistema de roles: `admin`, `instructor`, `student`
- [ ] Middleware de autorización por rol
- [ ] Gates y Policies de Laravel para control granular
- [ ] Gestión de roles desde admin panel

### Módulo 2.2 — Sistema de Cursos (Contenido)

**Entregable 2.2.1 — Estructura de contenido educativo**
- [ ] Modelo `Course` extendido: módulos, lecciones, orden
- [ ] Modelo `Module` (secciones dentro de un curso)
- [ ] Modelo `Lesson` (contenido individual: video, texto, recursos)
- [ ] Relaciones jerárquicas: Course → Module → Lesson
- [ ] Soporte para contenido multimedia (videos embebidos, PDFs, imágenes)

**Entregable 2.2.2 — Experiencia del estudiante**
- [ ] Vista de curso inscrito (sidebar con módulos/lecciones, progreso)
- [ ] Reproductor de video embebido (Vimeo/YouTube private o almacenamiento propio)
- [ ] Tracking de progreso: marcar lección como completada
- [ ] Barra de progreso por curso
- [ ] Página "Mis cursos" con cursos activos y progreso

**Entregable 2.2.3 — Gestión de cursos (Admin/Instructor)**
- [ ] CRUD completo de módulos y lecciones desde admin
- [ ] Reordenamiento drag-and-drop de módulos/lecciones
- [ ] Preview de curso antes de publicar
- [ ] Estados de curso: borrador, publicado, archivado

### Módulo 2.3 — Inscripciones

**Entregable 2.3.1 — Sistema de inscripción**
- [ ] Modelo `Enrollment` (user_id, course_id, status, enrolled_at, expires_at)
- [ ] Flujo de inscripción: usuario solicita → se procesa → acceso otorgado
- [ ] Inscripción manual por admin (para Phase 2 sin pagos)
- [ ] Control de acceso: solo usuarios inscritos ven contenido del curso
- [ ] Notificaciones por email: bienvenida, recordatorios

---

## PHASE 3 — Pagos y Membresías

**Alcance:** Monetización de la plataforma. Pasarela de pagos, planes de membresía, acceso basado en suscripción.
**Dependencias:** Phase 2 completada (usuarios e inscripciones existen).
**Clasificación:** POST-MVP (necesario para operación comercial)

### Módulo 3.1 — Pasarela de Pagos

**Entregable 3.1.1 — Integración de pagos**
- [ ] Integrar Stripe vía Laravel Cashier
- [ ] Flujo de pago: seleccionar curso/plan → checkout → confirmación
- [ ] Manejo de webhooks de pago (confirmación, fallo, reembolso)
- [ ] Registro de transacciones en base de datos (modelo `Payment`)

**Entregable 3.1.2 — Facturación básica**
- [ ] Generación de recibos/facturas en PDF
- [ ] Historial de pagos en perfil del usuario
- [ ] Notificaciones de pago exitoso/fallido por email

### Módulo 3.2 — Membresías y Planes

**Entregable 3.2.1 — Sistema de membresías**
- [ ] Modelo `Plan` (nombre, precio, duración, cursos incluidos, features)
- [ ] Modelo `Subscription` (user_id, plan_id, status, starts_at, ends_at)
- [ ] Tipos de acceso: compra individual de curso vs suscripción a plan
- [ ] Lógica de acceso: verificar suscripción activa O compra individual
- [ ] Página de planes/pricing pública

**Entregable 3.2.2 — Gestión de suscripciones**
- [ ] Renovación automática (si pasarela lo soporta)
- [ ] Cancelación de suscripción por usuario
- [ ] Período de gracia configurable
- [ ] Admin: vista de suscripciones activas, métricas de revenue
- [ ] Notificaciones: próximo a vencer, renovación exitosa, cancelación

---

## PHASE 4 — Trading Journal (Módulo Premium Opcional)

**Alcance:** Módulo completamente desacoplado que permite a estudiantes premium visualizar su journal de trading. Los datos son procesados externamente por workers. Laravel solo los presenta en modo lectura.
**Dependencias:** Phase 3 completada (membresías premium existen). Phase 5 parcial (workers que alimentan datos).
**Clasificación:** PREMIUM / FUTURO

### Módulo 4.1 — API de Trading Journal

**Entregable 4.1.1 — Esquema de datos del journal**
- [ ] Modelo `TradePair` (símbolo, mercado)
- [ ] Modelo `TradeEntry` (user_id, pair, direction, entry_price, exit_price, PnL, timestamp, notas, tags)
- [ ] Modelo `JournalSummary` (user_id, período, estadísticas agregadas)
- [ ] Migraciones con índices optimizados para consultas de lectura
- [ ] Nota: estos datos son ESCRITOS por workers externos, NO por Laravel

**Entregable 4.1.2 — API interna para workers**
- [ ] Endpoint autenticado (API key) para que workers escriban entradas de journal
- [ ] Validación estricta de datos entrantes
- [ ] Rate limiting por API key
- [ ] Logs de escritura para auditoría

### Módulo 4.2 — Interfaz de Lectura del Journal

**Entregable 4.2.1 — Dashboard del journal**
- [ ] Vista de journal personal del estudiante (solo lectura)
- [ ] Tabla de trades con filtros (fecha, par, dirección, resultado)
- [ ] Estadísticas resumidas: win rate, PnL total, racha, drawdown
- [ ] Gráficos básicos (equity curve, distribución de PnL)
- [ ] Exportación a CSV

**Entregable 4.2.2 — Control de acceso premium**
- [ ] Verificar membresía premium antes de mostrar journal
- [ ] Página de upsell para usuarios sin acceso premium
- [ ] Feature flag para activar/desactivar módulo globalmente
- [ ] El módulo se puede desinstalar sin afectar el resto de la plataforma

---

## PHASE 5 — Workers Externos (Python / VPS)

**Alcance:** Servicios de procesamiento que corren en VPS Linux independientes. Ejecutan tareas batch, procesamiento de datos de trading, y cualquier tarea pesada que NO debe correr en Laravel.
**Dependencias:** Phase 0 (API base disponible). Phase 4 parcial (esquema de journal definido).
**Clasificación:** FUTURO (paralelo a Phase 4)

### Módulo 5.1 — Infraestructura de Workers

**Entregable 5.1.1 — Proyecto base de workers**
- [ ] Repositorio separado (`ami-workers`) — NO vive dentro del repo Laravel
- [ ] Estructura de proyecto Python (poetry/pip, estructura de paquetes)
- [ ] Sistema de configuración (variables de entorno, archivos de config)
- [ ] Logger estructurado (JSON logs)
- [ ] Dockerfile para cada worker
- [ ] Health checks y monitoreo básico

**Entregable 5.1.2 — Comunicación con Laravel**
- [ ] Cliente HTTP para consumir API interna de Laravel
- [ ] Autenticación vía API keys seguras
- [ ] Cola de mensajes compartida (Redis o RabbitMQ)
- [ ] Protocolo de reintentos y manejo de errores
- [ ] Dead letter queue para mensajes fallidos

### Módulo 5.2 — Workers de Trading Journal

**Entregable 5.2.1 — Worker de importación de trades**
- [ ] Conectar con broker APIs o archivos CSV de trades
- [ ] Parsear y normalizar datos de trading
- [ ] Calcular métricas: PnL, duración, slippage
- [ ] Escribir resultados en Laravel vía API interna
- [ ] Scheduling: ejecución periódica (cron o scheduler interno)

**Entregable 5.2.2 — Worker de análisis y estadísticas**
- [ ] Calcular estadísticas agregadas por período
- [ ] Generar resúmenes semanales/mensuales
- [ ] Escribir resúmenes en Laravel vía API
- [ ] Alertas ante anomalías (drawdown excesivo, etc.)

---

## PHASE 6 — Producción, Operaciones y Observabilidad

**Alcance:** Todo lo necesario para operar la plataforma en producción con confianza.
**Dependencias:** Phase 3 completada como mínimo.
**Clasificación:** OBLIGATORIO antes de lanzamiento comercial

### Módulo 6.1 — Infraestructura de Producción

**Entregable 6.1.1 — Servidor y hosting**
- [ ] Provisionar servidor de producción (VPS o PaaS)
- [ ] Configurar Nginx + PHP-FPM optimizado
- [ ] Configurar SSL/TLS (Let's Encrypt o Cloudflare)
- [ ] Configurar CDN para assets estáticos
- [ ] Configurar backups automáticos de base de datos (diario + retención)
- [ ] Configurar Redis en producción (caché + sesiones + colas)

**Entregable 6.1.2 — Seguridad**
- [ ] Hardening del servidor (firewall, SSH keys, fail2ban)
- [ ] Headers de seguridad HTTP (CSP, HSTS, X-Frame-Options)
- [ ] Auditoría de dependencias (composer audit, npm audit)
- [ ] Rate limiting global y por endpoint
- [ ] Política de rotación de secretos y API keys

### Módulo 6.2 — Observabilidad

**Entregable 6.2.1 — Logging y monitoreo**
- [ ] Logging centralizado (Laravel logs → archivo o servicio externo)
- [ ] Monitoreo de errores (Sentry o similar)
- [ ] Monitoreo de uptime (UptimeRobot, Oh Dear, o similar)
- [ ] Alertas: email/Slack ante errores críticos o caídas
- [ ] Dashboard de métricas básicas (usuarios, inscripciones, revenue)

**Entregable 6.2.2 — Performance**
- [ ] Configurar caché de Laravel (config, routes, views)
- [ ] Optimización de queries (eager loading, índices, query profiling)
- [ ] Queue worker de Laravel para emails y tareas ligeras (NO batch)
- [ ] Configurar Horizon si se usa Redis queues

---

## Resumen de Clasificación

| Fase | Nombre | Clasificación | Dependencia |
|------|--------|---------------|-------------|
| **Phase 0** | Fundación e Infraestructura | PRE-MVP | Ninguna |
| **Phase 1** | Sitio Web Público | **MVP PÚBLICO** | Phase 0 |
| **Phase 2** | Plataforma Educativa | **MVP COMPLETO** | Phase 1 |
| **Phase 3** | Pagos y Membresías | POST-MVP | Phase 2 |
| **Phase 4** | Trading Journal | PREMIUM / FUTURO | Phase 3 + Phase 5 |
| **Phase 5** | Workers Externos | FUTURO | Phase 0 |
| **Phase 6** | Producción y Operaciones | OBLIGATORIO PRE-LAUNCH | Phase 3 |

---

## Notas Finales

- Cada fase será descompuesta en planes ejecutables individuales antes de comenzar.
- El Trading Journal (Phase 4) puede NO construirse nunca sin afectar el resto de la plataforma.
- Los workers (Phase 5) viven en repositorio separado con su propio ciclo de vida.
- Phase 6 no es "al final" — muchos entregables de operaciones se implementan incrementalmente desde Phase 0.
- Este plan NO incluye estimaciones de tiempo. Las estimaciones se harán por fase al momento de planificar cada una.
