# Table

Tabla de datos con cabecera, filas, pie y leyenda.

## Cuándo usarlo

- Listados que se comparan por columnas: solicitudes, nóminas, fichajes.
- Cifras alineadas a la derecha con `text-right tabular-nums`; fechas dd/mm/aaaa; miles con punto.
- Estados en `Badge`, acciones de fila en `DropdownMenu` o botones `ghost`.

## Qué tienes que pasarle

`Table` (envuelve en un contenedor con scroll horizontal), `TableHeader`, `TableBody`, `TableFooter`, `TableRow` (`data-state="selected"`), `TableHead`, `TableCell`, `TableCaption`.

## Ejemplo

```tsx
<Table>
  <TableHeader><TableRow><TableHead>Persona</TableHead><TableHead className="text-right">Días</TableHead></TableRow></TableHeader>
  <TableBody><TableRow><TableCell>Lucía Martín</TableCell><TableCell className="text-right tabular-nums">5</TableCell></TableRow></TableBody>
</Table>
```
