# Pagination

Paginación de listados largos.

## Cuándo usarlo

- Debajo de tablas y listas de más de 25 elementos. Marca la página actual con `isActive`.

## Qué tienes que pasarle

`Pagination`, `PaginationContent`, `PaginationItem`, `PaginationLink` (`isActive`, `href`), `PaginationPrevious`, `PaginationNext` (“Anterior” y “Siguiente”) y `PaginationEllipsis`.

## Ejemplo

```tsx
<Pagination><PaginationContent>
  <PaginationItem><PaginationPrevious href="?p=1" /></PaginationItem>
  <PaginationItem><PaginationLink href="?p=2" isActive>2</PaginationLink></PaginationItem>
  <PaginationItem><PaginationNext href="?p=3" /></PaginationItem>
</PaginationContent></Pagination>
```
