# Select

Desplegable para elegir una opción de una lista.

## Cuándo usarlo

- Listas de 5 a 15 opciones. Para más, o con búsqueda, usa `Command` dentro de un `Popover`.

## Qué tienes que pasarle

`value` / `defaultValue`, `onValueChange`; `SelectTrigger` (`size`: `default` | `sm`) con `SelectValue placeholder`, `SelectContent` con `SelectGroup`, `SelectLabel`, `SelectItem value` y `SelectSeparator`.

## Ejemplo

```tsx
<Select>
  <SelectTrigger className="w-56"><SelectValue placeholder="Elige una oficina" /></SelectTrigger>
  <SelectContent>
    <SelectItem value="madrid">Madrid</SelectItem>
    <SelectItem value="barcelona">Barcelona</SelectItem>
  </SelectContent>
</Select>
```
