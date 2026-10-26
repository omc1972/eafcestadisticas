# Field

Estructura de formulario: etiqueta, control, ayuda y error en orden y con la separación de Hacce.

## Cuándo usarlo

- Construye todos los formularios con `FieldSet` › `FieldLegend` › `FieldGroup` › `Field`.
- `orientation="horizontal"` para Switch y Checkbox con su etiqueta al lado.
- Marca el error en el `Field` con `data-invalid` y en el control con `aria-invalid`, y explícalo en `FieldError`.

## Qué tienes que pasarle

`Field` (`orientation`: `vertical` | `horizontal` | `responsive`), `FieldLabel`, `FieldDescription`, `FieldError` (texto o `errors`), `FieldGroup`, `FieldSet`, `FieldLegend`, `FieldSeparator`, `FieldContent`, `FieldTitle`.

## Ejemplo

```tsx
<FieldSet>
  <FieldLegend>Datos de la solicitud</FieldLegend>
  <FieldGroup>
    <Field>
      <FieldLabel htmlFor="tel">Teléfono</FieldLabel>
      <Input id="tel" />
      <FieldDescription>Solo lo verá tu responsable.</FieldDescription>
    </Field>
  </FieldGroup>
</FieldSet>
```
