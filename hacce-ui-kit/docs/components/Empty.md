# Empty

Estado vacío: qué falta y cómo empezar.

## Cuándo usarlo

- Listas y paneles sin datos todavía, búsquedas sin resultados.
- Título breve, una frase que explique qué aparecerá aquí y una acción principal.
- Icono de marca (`HacceIcon`) a 24 px en `EmptyMedia variant="icon"`.

## Qué tienes que pasarle

`Empty`, `EmptyHeader`, `EmptyMedia` (`variant`: `default` | `icon`), `EmptyTitle`, `EmptyDescription`, `EmptyContent`.

## Ejemplo

```tsx
<Empty>
  <EmptyHeader>
    <EmptyMedia variant="icon"><HacceIcon name="carpeta" /></EmptyMedia>
    <EmptyTitle>Aún no hay documentos</EmptyTitle>
    <EmptyDescription>Cuando subas un justificante aparecerá aquí.</EmptyDescription>
  </EmptyHeader>
  <EmptyContent><Button>Subir documento</Button></EmptyContent>
</Empty>
```
