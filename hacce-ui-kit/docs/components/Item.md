# Item

Fila de lista con icono o imagen, título, descripción y acciones.

## Cuándo usarlo

- Listas de documentos, notificaciones, ajustes o personas.
- Agrupa con `ItemGroup` y separa con `ItemSeparator`.

## Qué tienes que pasarle

`Item` (`variant`: `default` | `outline` | `muted`; `size`: `default` | `sm`; `asChild` para enlaces), `ItemMedia` (`variant`: `default` | `icon` | `image`), `ItemContent`, `ItemTitle`, `ItemDescription`, `ItemActions`, `ItemHeader`, `ItemFooter`, `ItemGroup`, `ItemSeparator`.

## Ejemplo

```tsx
<Item variant="outline">
  <ItemMedia variant="icon"><HacceIcon name="documento" size={18} /></ItemMedia>
  <ItemContent><ItemTitle>Nómina de octubre</ItemTitle><ItemDescription>PDF · 182 KB</ItemDescription></ItemContent>
  <ItemActions><Button size="sm" variant="outline">Descargar</Button></ItemActions>
</Item>
```
