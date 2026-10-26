# Calendar

Calendario para elegir una fecha o un rango, en español y con la semana en lunes.

## Cuándo usarlo

- Solo o dentro de un `Popover` como selector de fecha.
- `mode="range"` para vacaciones y periodos.

## Qué tienes que pasarle

Las props de react-day-picker 9: `mode` (`single` | `multiple` | `range`), `selected`, `onSelect`, `disabled`, `defaultMonth`, `numberOfMonths`, `captionLayout` (`label` | `dropdown`). `locale` es español y `weekStartsOn` 1 por defecto.

## Ejemplo

```tsx
const [fecha, setFecha] = React.useState()
<Calendar mode="single" selected={fecha} onSelect={setFecha} className="rounded-lg border" />
```
