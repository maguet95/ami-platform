# Rol

Eres un experto en testing de aplicaciones Laravel con PHPUnit/Pest.

# Argumento

$ARGUMENTS

# Objetivo

Escribir tests para el codigo o feature indicada en $ARGUMENTS.

# Proceso

1. **Identificar que testear**:
   - Si $ARGUMENTS es un archivo: escribir tests para ese archivo
   - Si $ARGUMENTS es una feature: identificar todos los componentes testeables

2. **Explorar el codigo**:
   - Leer el archivo/clase a testear
   - Entender sus dependencias
   - Revisar tests existentes para seguir el patron

3. **Escribir tests**:
   - Feature tests para controllers/rutas en `tests/Feature/`
   - Unit tests para services/models en `tests/Unit/`
   - Seguir patron AAA: Arrange → Act → Assert

4. **Ejecutar tests**:
   ```bash
   php artisan test --filter=NombreDelTest
   ```

# Estructura de tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class NombreTest extends TestCase
{
    use RefreshDatabase;

    public function test_descripcion_clara_del_escenario(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/ruta');

        // Assert
        $response->assertStatus(200);
    }
}
```

# Que testear por tipo

## Controllers (Feature tests)
- Respuesta HTTP correcta (status codes)
- Redireccion despues de acciones
- Validacion de formularios (campos requeridos, formatos)
- Autorizacion (usuarios no autenticados, roles incorrectos)
- Datos correctos en la vista

## Services (Unit tests)
- Logica de negocio con diferentes inputs
- Casos limite (listas vacias, nulls, valores extremos)
- Manejo de errores

## Models (Unit tests)
- Scopes
- Relaciones
- Accessors/Mutators
- Metodos custom

## API (Feature tests)
- Autenticacion (X-API-Key)
- Validacion de payload
- Respuestas JSON correctas
- Deduplicacion

# Reglas

- Usar `RefreshDatabase` para tests que tocan la BD
- Usar factories para crear datos de prueba
- Un test por escenario — no combinar multiples assertions no relacionadas
- Nombres descriptivos: `test_student_cannot_access_admin_panel`
- La BD de test es PostgreSQL (`ami_platform_test`)
