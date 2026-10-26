# ThemeToggle

Selector de modo claro, oscuro o sistema para la cabecera de cualquier app.

## Cuándo usarlo

- En la cabecera o en el menú de usuario, siempre en el mismo sitio.
- `variant="segmented"` en ajustes y cabeceras amplias; `variant="icon"` en barras compactas o móvil (lleva Tooltip).
- Debe estar dentro de `<HacceTheme>`, que guarda la elección en localStorage (`hacce-appearance`).

## Qué tienes que pasarle

`variant` (`"segmented"` | `"icon"`), `labels` (`{ light, dark, system }`, por defecto Claro, Oscuro, Sistema) y `className`.

## Ejemplo

```tsx
<HacceTheme>
  <header className="flex items-center justify-between px-4 py-3">
    <HacceLogo height={28} />
    <ThemeToggle />
  </header>
</HacceTheme>
```
