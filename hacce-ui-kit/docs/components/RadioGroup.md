# RadioGroup

Elegir una sola opción entre pocas (2–5) visibles a la vez.

## Cuándo usarlo

- Modalidad de trabajo, tipo de jornada. Con más de 5 opciones usa `Select`.

## Qué tienes que pasarle

`value` / `defaultValue`, `onValueChange`; hijos `RadioGroupItem` con `value` e `id`, cada uno con su `Label`.

## Ejemplo

```tsx
<RadioGroup defaultValue="hibrido">
  <div className="flex items-center gap-2"><RadioGroupItem value="hibrido" id="h" /><Label htmlFor="h">Híbrido</Label></div>
</RadioGroup>
```
