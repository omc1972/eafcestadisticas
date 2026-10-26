# ToggleGroup

Conjunto de toggles para elegir una o varias opciones.

## Cuándo usarlo

- `type="single"` para cambiar de vista o periodo (Semana, Mes, Año).
- `type="multiple"` para filtros combinables.
- Es la base del `ThemeToggle`.

## Qué tienes que pasarle

`type` (`single` | `multiple`), `value` / `defaultValue`, `onValueChange`, `variant` y `size`; hijos `ToggleGroupItem` con `value`.

## Ejemplo

```tsx
<ToggleGroup type="single" variant="outline" defaultValue="mes">
  <ToggleGroupItem value="semana">Semana</ToggleGroupItem>
  <ToggleGroupItem value="mes">Mes</ToggleGroupItem>
</ToggleGroup>
```
