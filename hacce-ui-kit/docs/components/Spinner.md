# Spinner

Indicador de carga giratorio.

## Cuándo usarlo

- Dentro de un botón mientras se envía (con el botón desactivado), o junto a un texto “Cargando…”. Para bloques grandes usa `Skeleton`.

## Qué tienes que pasarle

`className` para tamaño y color (`size-6 text-primary`). Lleva `role="status"` y `aria-label="Cargando"`.

## Ejemplo

```tsx
<Button disabled><Spinner /> Enviando</Button>
```
