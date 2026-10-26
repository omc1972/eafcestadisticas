# ButtonGroup

Agrupa botones relacionados en una sola pieza visual.

## Cuándo usarlo

- Controles que van juntos: paginación anterior/siguiente, vistas Hoy/Semana/Mes, un botón con su menú desplegable.
- Para elegir una opción entre varias con estado, usa mejor `ToggleGroup`.

## Qué tienes que pasarle

Hijos `Button` (o `Input`, `Select`); `orientation` (`horizontal` | `vertical`). `ButtonGroupSeparator` separa botones del mismo color; `ButtonGroupText` añade un texto.

## Ejemplo

```tsx
<ButtonGroup>
  <Button variant="outline">Hoy</Button>
  <Button variant="outline">Semana</Button>
</ButtonGroup>
```
