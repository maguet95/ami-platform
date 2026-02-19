# Objetivo

Verificar que el proyecto esta listo para deploy a produccion.

# Proceso

Ejecutar las siguientes verificaciones en orden:

## 1. Calidad de codigo
```bash
composer run check
```
Esto ejecuta: lint:check + analyse + test

## 2. Build de frontend
```bash
npm run build
```
Verificar que compila sin errores.

## 3. Migraciones
```bash
php artisan migrate:status
```
Verificar que no hay migraciones pendientes sin correr.

## 4. Config y cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
Verificar que cachean sin errores (indica que no hay closures en rutas ni config invalida).

Limpiar despues:
```bash
php artisan config:clear && php artisan route:clear && php artisan view:clear
```

## 5. Variables de entorno criticas
Verificar que `.env.example` tiene todas las variables necesarias documentadas.
Listar variables usadas en config/ que no esten en .env.example.

## 6. Seguridad basica
- Verificar que `APP_DEBUG=false` esta documentado para produccion
- Verificar que no hay credenciales hardcodeadas en el codigo
- Verificar que `APP_KEY` esta configurado

## 7. GitHub Actions
```bash
ls .github/workflows/
```
Verificar que los workflows estan actualizados y los secrets documentados.

# Reporte

```
Pre-deploy Check
================
Lint:          OK/FALLO
Analisis:      OK/FALLO
Tests:         OK/FALLO (X passed, Y failed)
Build:         OK/FALLO
Migraciones:   OK/FALLO (N pendientes)
Config cache:  OK/FALLO
Route cache:   OK/FALLO
View cache:    OK/FALLO
Env vars:      OK/FALLO (lista de faltantes)
Seguridad:     OK/FALLO
Workflows:     OK/FALLO

Veredicto: LISTO PARA DEPLOY / NO LISTO
```
