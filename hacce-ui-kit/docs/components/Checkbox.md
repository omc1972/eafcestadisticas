# Checkbox

Casilla para marcar una o varias opciones independientes.

## Cuándo usarlo

- Listas de opciones combinables y aceptación de condiciones.
- Siempre con `Label` al lado; para activar algo al instante usa `Switch`.

## Qué tienes que pasarle

`checked` / `defaultChecked` (también `"indeterminate"`), `onCheckedChange`, `disabled`, `id`.

## Ejemplo

```tsx
<div className="flex items-center gap-2">
  <Checkbox id="vac" defaultChecked />
  <Label htmlFor="vac">Vacaciones</Label>
</div>
```
