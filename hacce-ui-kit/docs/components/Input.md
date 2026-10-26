# Input

Campo de texto de una línea.

## Cuándo usarlo

- Siempre con `Label` (o dentro de `Field`) y un `id` que los una.
- Marca el error con `aria-invalid="true"`: el borde pasa a `destructive`. Explica el error con texto debajo.
- Placeholder solo como ejemplo (“nombre@hacce.com”), nunca como etiqueta.

## Qué tienes que pasarle

Todos los atributos de `<input>` (`type`, `value`, `defaultValue`, `onChange`, `disabled`, `aria-invalid`…).

## Ejemplo

```tsx
<Label htmlFor="email">Correo electrónico</Label>
<Input id="email" type="email" placeholder="nombre@hacce.com" />
```
