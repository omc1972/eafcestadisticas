# InputGroup

Campo con añadidos dentro: icono, prefijo, sufijo, contador o botón.

## Cuándo usarlo

- Búsquedas con lupa, URLs con prefijo, campos con botón de copiar o de mostrar contraseña.
- Los añadidos van en `InputGroupAddon` con `align` (`inline-start` por defecto, `inline-end`, `block-start`, `block-end`).

## Qué tienes que pasarle

`InputGroupInput` o `InputGroupTextarea` como campo; `InputGroupAddon`, `InputGroupText` y `InputGroupButton` (`size`: `xs` | `sm` | `icon-xs` | `icon-sm`).

## Ejemplo

```tsx
<InputGroup>
  <InputGroupAddon><Search /></InputGroupAddon>
  <InputGroupInput placeholder="Buscar empleado" />
</InputGroup>
```
