# Avatar

Foto o iniciales de una persona.

## Cuándo usarlo

- Junto al nombre en cabeceras, comentarios y listas de personas.
- Sin foto, dos iniciales sobre el azul suave `accent`.
- Grupos: solapados con `-space-x-2` y `ring-2 ring-background`.

## Qué tienes que pasarle

`Avatar` (`className` para el tamaño, `size-8` por defecto), `AvatarImage` (`src`, `alt`) y `AvatarFallback` con las iniciales.

## Ejemplo

```tsx
<Avatar>
  <AvatarImage src={foto} alt="Lucía Martín" />
  <AvatarFallback>LM</AvatarFallback>
</Avatar>
```
