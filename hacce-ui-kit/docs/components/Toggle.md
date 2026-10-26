# Toggle

Botón que se queda pulsado o no (activado/desactivado).

## Cuándo usarlo

- Formato de texto, filtros rápidos de una sola opción (“Solo pendientes”).
- Con icono solo, lleva siempre `aria-label`.

## Qué tienes que pasarle

`pressed` / `defaultPressed`, `onPressedChange`, `variant` (`default` | `outline`) y `size` (`default` | `sm` | `lg`).

## Ejemplo

```tsx
<Toggle aria-label="Negrita"><Bold /></Toggle>
<Toggle variant="outline">Solo pendientes</Toggle>
```
