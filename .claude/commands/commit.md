# Rol

Experto en control de versiones y flujos de trabajo Git.

# Argumento

$ARGUMENTS

# Objetivo

Crear un commit limpio y descriptivo con los cambios relevantes, y opcionalmente push + merge.

# Proceso

## 1. Inspeccionar estado actual

- `git status` para ver archivos modificados y sin trackear
- `git diff` para ver cambios
- Identificar branch actual

## 2. Determinar alcance

- **Si $ARGUMENTS esta vacio**: incluir todos los cambios relevantes
- **Si $ARGUMENTS contiene indicaciones** (ej: "solo migration", "merge a main"): seguir las instrucciones

## 3. Staging

- Agregar archivos especificos por nombre (NO usar `git add .` ni `git add -A`)
- Excluir archivos sensibles (.env, credenciales, locks de editores)
- Si hay cambios no relacionados, dejarlos fuera del commit

## 4. Commit message

Formato: `tipo: descripcion concisa`

Tipos validos:
- `feat`: nueva funcionalidad
- `fix`: correccion de bug
- `docs`: documentacion
- `refactor`: reestructuracion sin cambio de comportamiento
- `test`: tests nuevos o modificados
- `chore`: mantenimiento, dependencias, config

Reglas:
- Primera linea: max 72 caracteres, imperativo
- Body (si es necesario): bullet points describiendo que cambio
- Siempre terminar con: `Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>`
- Usar HEREDOC para el mensaje

## 5. Push y merge (solo si se pide)

- Push a la branch actual
- Si se pide merge: checkout main → merge → push → volver a development
- NUNCA force push sin confirmacion explicita

# Reglas

- NUNCA hacer push automaticamente — solo si el usuario lo pide
- NUNCA hacer merge automaticamente — solo si el usuario lo pide
- NUNCA usar `--no-verify`, `--force`, o `--amend` sin que lo pidan
- Verificar `git status` despues del commit para confirmar
