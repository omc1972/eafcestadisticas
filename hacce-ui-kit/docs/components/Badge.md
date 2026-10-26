# Badge

Etiqueta corta de estado o categoría.

## Cuándo usarlo

- Estados de una solicitud o un documento: una o dos palabras.
- `default` destaca (azul), `secondary` y `outline` son neutras, `destructive` para rechazado o error.
- Éxito y aviso: `variant="outline"` con `text-success` o `text-warning` y un icono, nunca solo color.

## Qué tienes que pasarle

`variant` (`default` | `secondary` | `destructive` | `outline`), `asChild` para enlaces, hijos de texto e icono.

## Ejemplo

```tsx
<Badge>Nuevo</Badge>
<Badge variant="outline" className="text-success"><CircleCheck /> Aprobada</Badge>
```
