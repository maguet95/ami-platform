# Objetivo

Ejecutar todas las verificaciones de calidad del proyecto y reportar el resultado.

# Proceso

Ejecutar en orden:

1. **Lint** (formateo de codigo):
   ```bash
   composer run lint:check
   ```

2. **Analisis estatico** (PHPStan/Larastan):
   ```bash
   composer run analyse
   ```

3. **Tests**:
   ```bash
   composer run test
   ```

4. **Rutas** (verificar que no hay conflictos):
   ```bash
   php artisan route:list --json 2>&1 | head -5
   ```

5. **Migraciones pendientes**:
   ```bash
   php artisan migrate:status
   ```

6. **Views** (verificar compilacion):
   ```bash
   php artisan view:cache && php artisan view:clear
   ```

# Formato de reporte

Reportar resultado de cada paso con:
- OK o FALLO
- Si hay fallos, detallar que salio mal
- Sugerir como arreglarlo

Ejemplo:
```
Lint:        OK
Analisis:    OK
Tests:       FALLO (2 tests fallaron - NombreTest::metodo)
Rutas:       OK
Migraciones: OK
Views:       OK
```
