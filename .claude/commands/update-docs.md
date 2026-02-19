# Objetivo

Revisar y actualizar la documentacion del proyecto segun los cambios recientes.

# Proceso

1. **Revisar cambios recientes**:
   ```bash
   git diff HEAD~1 --stat
   ```
   O si hay cambios no commiteados: `git diff --stat`

2. **Identificar documentacion afectada**:
   - Cambios en modelos/migraciones → Actualizar `docs/SYSTEM_ARCHITECTURE.md`
   - Cambios en API → Actualizar contratos en `docs/MODULE_TRADING_JOURNAL.md`
   - Nuevas features/fases → Actualizar `docs/MASTER_PLAN.md`
   - Cambios en deploy/config → Actualizar `deploy/` docs
   - Cambios en workers → Actualizar `docs/WORKERS_EXECUTION_PLAN.md`

3. **Actualizar cada archivo afectado**:
   - Mantener consistencia con la estructura existente
   - Documentar en espanol (la documentacion del proyecto es en espanol)

4. **Actualizar CLAUDE.md** si hay cambios en:
   - Stack tecnico
   - Nuevos gotchas descubiertos
   - Nuevos comandos o rutas importantes
   - Cambios arquitectonicos

5. **Actualizar MEMORY.md** si hay cambios en:
   - Estado de fases
   - Decisiones arquitectonicas importantes
   - Nuevos patrones o convenciones

6. **Reportar** que archivos se actualizaron y que cambios se hicieron
